<?php
namespace Dfe\AllPay;
use Dfe\AllPay\Block\Info;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Dfe\AllPay\Source\PaymentIdentificationType as Identification;
use Magento\Framework\DataObject;
use Magento\Framework\Phrase;
use Magento\Sales\Model\Order as O;
use Magento\Sales\Model\Order\Address as OrderAddress;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Payment as OP;
use Magento\Sales\Model\Order\Payment\Transaction;
/**
 * @method Response|string|null responseF(string $key = null)
 * @method Response|string|null responseL(string $key = null)
 */
class Method extends \Df\Payment\R\Method {
	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\R\Method::getInfoBlockType()
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
			$this->{__METHOD__} = df_con($this, $suffix, Info::class);
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-08-13
	 * @used-by \Dfe\AllPay\Block\Info::_prepareSpecificInformation()
	 * @used-by \Dfe\AllPay\Charge::plan()
	 * @used-by \Dfe\AllPay\Method::paymentOptionTitle()
	 *
	 * @return Plan|null
	 */
	public function plan() {
		if (!isset($this->{__METHOD__})) {
			/** @var int|null $id */
			$id = $this->iia(self::$II_PLAN);
			$this->{__METHOD__} = df_n_set(!$id ? null : $this->s()->installmentSales()->plans($id));
		}
		return df_n_get($this->{__METHOD__});
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
				$this->responseF() ? __($this->responseF()->paymentOptionTitle()) : (
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
	 * 2016-08-27
	 * Первый параметр — для test, второй — для live.
	 * @override
	 * @see \Df\Payment\R\Method::stageNames()
	 * @used-by \Df\Payment\R\Method::getConfigPaymentAction()
	 * @used-by \Df\Payment\R\Refund::stageNames()
	 * @return string[]
	 */
	public function stageNames() {return ['-stage', ''];}

	/**
	 * 2016-08-08
	 * @override
	 * @see \Df\Payment\Method::iiaKeys()
	 * @used-by \Df\Payment\Method::assignData()
	 * @return string[]
	 */
	protected function iiaKeys() {return [self::II_OPTION, self::$II_PLAN];}

	/**
	 * 2016-08-27
	 * @used-by \Df\Payment\R\Method::getConfigPaymentAction()
	 * @override
	 * @see \Df\Payment\R\Method::stageNames()
	 * @return string
	 */
	protected function redirectUrl() {return 'https://payment{stage}.allpay.com.tw/Cashier/AioCheckOut/V2';}

	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\R\Method::stageNames()
	 * @used-by \Df\Payment\R\Method::getConfigPaymentAction()
	 * @return string
	 */
	protected function transId() {return Identification::id($this->o());}

	/**
	 * 2016-08-15
	 * @used-by \Dfe\AllPay\Method::iiaKeys()
	 * @used-by \Dfe\AllPay\Charge
	 */
	const II_OPTION = 'option';

	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Charge::_requestI()
	 * @used-by \Dfe\AllPay\Response\Offline::paidTime()
	 */
	const TIMEZONE = 'Asia/Taipei';

	/**
	 * 2016-08-08
	 * @used-by \Dfe\AllPay\Method::iiaKeys()
	 * @used-by \Dfe\AllPay\Method::plan()
	 */
	private static $II_PLAN = 'plan';
}