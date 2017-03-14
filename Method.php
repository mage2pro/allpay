<?php
namespace Dfe\AllPay;
use Df\Payment\W\Event;
use Dfe\AllPay\Block\Info;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Magento\Framework\Phrase;
use Magento\Sales\Model\Order as O;
use Magento\Sales\Model\Order\Address as OrderAddress;
use Magento\Sales\Model\Order\Payment as OP;
/**
 * 2016-07-20
 * @method Event|string|null responseF(string $k = null)
 * @method Event|string|null responseL(string $k = null)
 */
final class Method extends \Df\PaypalClone\Method\Normal {
	/**
	 * 2016-07-20
	 * 2017-03-12
	 * Используем @uses df_cts(), чтобы избавиться от окончания «\Interceptor».
	 * 2017-03-14
	 * @see responseF() вернёт null, ПС ещё не присылала нам оповещений.
	 * В этом случае наш метод вернёт обобщённый класс @see \Dfe\AllPay\Block\Info
	 * @override
	 * @see \Df\PaypalClone\Method::getInfoBlockType()
	 * @used-by \Magento\Payment\Helper\Data::getInfoBlock()
	 * @return string
	 */
	function getInfoBlockType() {return df_cc_class(
		df_cts(Info::class), ($r = $this->responseF()) ? $r->t() : null
	);}

	/**
	 * 2017-03-05
	 * @used-by plan()
	 * @used-by \Dfe\AllPay\Charge::isSingleOptionChosen()
	 * @used-by \Dfe\AllPay\Charge::pChoosePayment()
	 * @return string|null
	 */
	function option() {return $this->iia(self::$II_OPTION);}

	/**
	 * 2016-08-13
	 * @used-by \Dfe\AllPay\Block\Info::_prepareSpecificInformation()
	 * @used-by \Dfe\AllPay\Charge::plan()
	 * @used-by \Dfe\AllPay\Method::paymentOptionTitle()
	 * @return Plan|null
	 */
	function plan() {return dfc($this, function() {/** @var int|string|null $id */ return
		!ctype_digit($id = $this->option()) ? null : $this->s()->installmentSales()->plans($id)
	;});}

	/**
	 * 2016-07-28
	 * @override
	 * @see \Df\Payment\Method::titleDetailed()
	 * @used-by \Df\Payment\Observer\DataProvider\SearchResult::execute()
	 * @return string
	 */
	function titleDetailed() {return df_cc_br(parent::titleDetailed(), $this->paymentOptionTitle());}

	/**
	 * 2016-08-13
	 * @used-by \Dfe\AllPay\Method::titleDetailed()
	 * @return string|Phrase|null
	 */
	function paymentOptionTitle() {return dfc($this, function() {return
		$this->responseF() ? __($this->responseF()->tl()) : (
			// 2016-08-13
			// Ситуация, когда покупатель в магазине выбрал оплату в рассрочку,
			// но платёжная система ещё не прислала оповещение о платеже (и способе оплаты).
			// Т.е. покупатель ещё ничего не оплатил,
			// и, возможно, просто закрыт страницу оплаты и уже ничего не оплатит.
			// Формируем заголовок по аналогии с
			// @see \Dfe\AllPay\W\Handler\BankCard::typeLabelByCode()
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
	function stageNames() {return ['-stage', ''];}

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
	 * The result should be in the basic monetary unit (like dollars), not in fractions (like cents).
	 * «Does allPay have minimum and maximum amount limitations on a single payment?»
	 * https://mage2.pro/t/2688
	 * @see \Df\Payment\Method::amountLimits()
	 * @used-by \Df\Payment\Method::isAvailable()
	 * @return null
	 */
	protected function amountLimits() {return [null, 30000];}

	/**
	 * 2016-08-08
	 * @override
	 * @see \Df\Payment\Method::iiaKeys()
	 * @used-by \Df\Payment\Method::assignData()
	 * @return string[]
	 */
	protected function iiaKeys() {return [self::$II_OPTION];}

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
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Charge::_requestI()
	 * @used-by \Dfe\AllPay\W\Handler\Offline::paidTime()
	 */
	const TIMEZONE = 'Asia/Taipei';

	/**
	 * 2016-08-15
	 * @used-by iiaKeys()
	 * @used-by plan()
	 */
	private static $II_OPTION = 'option';
}