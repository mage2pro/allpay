<?php
namespace Dfe\AllPay;
use Dfe\AllPay\Block\Info;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Dfe\AllPay\Settings as S;
use Df\Payment\PlaceOrder;
use Exception as E;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException as LE;
use Magento\Framework\Phrase;
use Magento\Payment\Model\Info as I;
use Magento\Payment\Model\InfoInterface as II;
use Magento\Sales\Model\Order as O;
use Magento\Sales\Model\Order\Address as OrderAddress;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Payment as OP;
use Magento\Sales\Model\Order\Payment\Transaction;
/**
 * @method Response responseF()
 * @method Response responseL()
 */
class Method extends \Df\Payment\Method {
	/**
	 * @override
	 * @see \Df\Payment\Method::getConfigPaymentAction()
	 * @return string
	 *
	 * 2016-05-07
	 * Сюда мы попадаем только из метода @used-by \Magento\Sales\Model\Order\Payment::place()
	 * причём там наш метод вызывается сразу из двух мест и по-разному.
	 *
	 * 2016-06-29
	 * Умышленно возвращаем null.
	 * @used-by \Magento\Sales\Model\Order\Payment::place()
	 * https://github.com/magento/magento2/blob/ffea3cd/app/code/Magento/Sales/Model/Order/Payment.php#L334-L355
	 */
	public function getConfigPaymentAction() {
		/** @var array(string => mixed) $params */
		$params = Charge::request($this->ii());
		/** @var string $url */
		$url = self::url('https://payment{-stage}.allpay.com.tw/Cashier/AioCheckOut/V2');
		/**
		 * 2016-07-01
		 * К сожалению, если передавать в качестве результата ассоциативный массив,
		 * то его ключи почему-то теряются.
		 * Поэтому запаковываем массив в JSON.
		 *
		 * 2016-07-13
		 * @used-by https://code.dmitry-fedyuk.com/m2e/allpay/blob/0.8.4/view/frontend/web/item.js#L91
		 */
		$this->iiaSet(PlaceOrder::DATA, df_json_encode(['params' => $params, 'uri' => $url]));
		/**
		 * 2016-05-06
		 * Письмо-оповещение о заказе здесь ещё не должно отправляться.
		 * «How is a confirmation email sent on an order placement?» https://mage2.pro/t/1542
		 */
		$this->o()->setCanSendNewEmailFlag(false);
		/**
		 * 2016-07-10
		 * Сохраняем информацию о транзакции.
		 */
		$this->saveRequest($params['MerchantTradeNo'], $url, $params);
		return null;
	}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\Method::getInfoBlockType()
	 * @used-by \Magento\Payment\Helper\Data::getInfoBlock()
	 * @return string
	 */
	public function getInfoBlockType() {
		if (!isset($this->{__METHOD__})) {
			/** @var string $suffix */
			$suffix = 'Block\Info';
			if ($this->responseF()) {
				$suffix = df_cc_class($suffix, $this->responseF()->classSuffix());
			}
			$this->{__METHOD__} = df_convention($this, $suffix, Info::class);
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-28
	 * @override
	 * @see \Df\Payment\Method::titleDetailed()
	 * @used-by \Df\Payment\Observer\DataProvider\SearchResult::execute()
	 * @return string
	 */
	public function titleDetailed() {
		return df_cc_br(parent::titleDetailed(), $this->paymentOptionTitle());
	}

	/**
	 * 2016-08-13
	 * @used-by \Dfe\AllPay\Method::titleDetailed()
	 * @return string|Phrase|null
	 */
	public function paymentOptionTitle() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = df_n_set(
				$this->responseF() ? __($this->responseF()->paymentOptionTitle())
					: (
						// 2016-08-13
						// Ситуация, когда покупатель в магазине выбрал оплату в рассрочку,
						// но платёжная система ещё не прислала оповещение о платеже (и способе оплаты).
						// Т.е. покупатель ещё ничего не оплатил,
						// и, возможно, просто закрыт страницу оплаты и уже ничего не оплатит.
						// Формируем заголовок по аналогии с
						// @see \Dfe\AllPay\Response\BankCard::paymentOptionTitleByCode()
						$this->plan() ? df_cc_br(__('Bank Card (Installments)'), __('Not paid yet'))
							: null
					)
			);
		}
		return df_n_get($this->{__METHOD__});
	}

	/**
	 * 2016-08-08
	 * @override
	 * @see \Df\Payment\Method::iiaKeys()
	 * @used-by \Df\Payment\Method::assignData()
	 * @return string[]
	 */
	protected function iiaKeys() {return [self::II_PLAN];}

	/**
	 * 2016-08-13
	 * @return Plan|null
	 */
	private function plan() {
		if (!isset($this->{__METHOD__})) {
			/** @var int|null $id */
			$id = $this->iia('plan');
			$this->{__METHOD__} = df_n_set(!$id ? null : $this->s()->installmentSales()->plans($id));
		}
		return df_n_get($this->{__METHOD__});
	}

	/**
	 * 2016-08-08
	 * @used-by \Dfe\AllPay\Method::iiaKeys()
	 * @used-by \Dfe\AllPay\Charge
	 */
	const II_PLAN = 'plan';

	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Charge::_requestI()
	 * @used-by \Dfe\AllPay\Response\Offline::paidTime()
	 */
	const TIMEZONE = 'Asia/Taipei';

	/**
	 * 2016-07-29
	 * @param string $template
	 * @param bool $test [optional]
	 * @param mixed[] ...$params [optional]
	 * @return string
	 */
	public static function url($template, $test = null, ...$params) {
		$test = is_null($test) ? S::s()->test() : $test;
		return vsprintf(str_replace('{-stage}', $test ? '-stage' : '', $template), $params);
	}
}