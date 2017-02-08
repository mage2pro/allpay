<?php
namespace Dfe\AllPay;
use Dfe\AllPay\Block\Info;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Magento\Framework\Phrase;
use Magento\Sales\Model\Order as O;
use Magento\Sales\Model\Order\Address as OrderAddress;
use Magento\Sales\Model\Order\Payment as OP;
/**
 * @method Webhook|string|null responseF(string $key = null)
 * @method Webhook|string|null responseL(string $key = null)
 */
final class Method extends \Df\PaypalClone\Method\Normal {
	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\PaypalClone\Method::getInfoBlockType()
	 * @used-by \Magento\Payment\Helper\Data::getInfoBlock()
	 * @return string
	 */
	public function getInfoBlockType() {return dfc($this, function() {
		/** @var Webhook $r */
		$r = $this->responseF();
		return df_con($this, df_ccc('\\', 'Block\Info', !$r ? null : $r->classSuffix()), Info::class);
	});}

	/**
	 * 2016-08-13
	 * @used-by \Dfe\AllPay\Block\Info::_prepareSpecificInformation()
	 * @used-by \Dfe\AllPay\Charge::plan()
	 * @used-by \Dfe\AllPay\Method::paymentOptionTitle()
	 * @return Plan|null
	 */
	public function plan() {return dfc($this, function() {
		/** @var int|null $id */
		$id = $this->iia(self::$II_PLAN);
		return !$id ? null : $this->s()->installmentSales()->plans($id);
	});}

	/**
	 * 2016-07-28
	 * @override
	 * @see \Df\Payment\Method::titleDetailed()
	 * @used-by \Df\Payment\Observer\DataProvider\SearchResult::execute()
	 * @return string
	 */
	public function titleDetailed() {return
		df_cc_br(parent::titleDetailed(), $this->paymentOptionTitle())
	;}

	/**
	 * 2016-08-13
	 * @used-by \Dfe\AllPay\Method::titleDetailed()
	 * @return string|Phrase|null
	 */
	public function paymentOptionTitle() {return dfc($this, function() {return
		$this->responseF() ? __($this->responseF()->typeLabel()) : (
			// 2016-08-13
			// Ситуация, когда покупатель в магазине выбрал оплату в рассрочку,
			// но платёжная система ещё не прислала оповещение о платеже (и способе оплаты).
			// Т.е. покупатель ещё ничего не оплатил,
			// и, возможно, просто закрыт страницу оплаты и уже ничего не оплатит.
			// Формируем заголовок по аналогии с
			// @see \Dfe\AllPay\Webhook\BankCard::typeLabelByCode()
			!$this->plan() ? null : df_cc_br(__('Bank Card (Installments)'), __('Not paid yet'))
		)
	;});}

	/**
	 * 2016-08-27
	 * Первый параметр — для test, второй — для live.
	 * @override
	 * @see \Df\PaypalClone\Method\Normal::stageNames()
	 * @used-by \Df\PaypalClone\Method\Normal::url()
	 * @used-by \Df\PaypalClone\Refund::stageNames()
	 * @return string[]
	 */
	public function stageNames() {return ['-stage', ''];}

	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::amountFactor()
	 * @used-by \Df\Payment\Method::amountFormat()
	 * @return int
	 */
	protected function amountFactor() {return 1;}

	/**
	 * 2017-02-08
	 * @override
	 * Результат — в рублях, не в копейках.
	 * I did not find such information on the allpay.com.tw website.
	 * «Does allPay have minimum and maximum amount limitations on a single payment?»
	 * https://mage2.pro/t/2688
	 * https://mail.google.com/mail/u/0/#sent/15a1f2dc4506f42e
	 * @see \Df\Payment\Method::amountLimits()
	 * @used-by \Df\Payment\Method::isAvailable()
	 * @return null
	 */
	protected function amountLimits() {return null;}

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
	 * @override
	 * @see \Df\PaypalClone\Method\Normal::redirectUrl()
	 * @used-by \Df\PaypalClone\Method\Normal::getConfigPaymentAction()
	 * @return string
	 */
	protected function redirectUrl() {return
		'https://payment{stage}.allpay.com.tw/Cashier/AioCheckOut/V2'
	;}

	/**
	 * 2016-08-15
	 * @used-by \Dfe\AllPay\Method::iiaKeys()
	 * @used-by \Dfe\AllPay\Charge
	 */
	const II_OPTION = 'option';

	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Charge::_requestI()
	 * @used-by \Dfe\AllPay\Webhook\Offline::paidTime()
	 */
	const TIMEZONE = 'Asia/Taipei';

	/**
	 * 2016-08-08
	 * @used-by \Dfe\AllPay\Method::iiaKeys()
	 * @used-by \Dfe\AllPay\Method::plan()
	 */
	private static $II_PLAN = 'plan';
}