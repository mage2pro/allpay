<?php
namespace Dfe\AllPay;
use Df\Payment\W\Event;
use Dfe\AllPay\Block\Info;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Magento\Sales\Model\Order as O;
use Magento\Sales\Model\Order\Address as OrderAddress;
use Magento\Sales\Model\Order\Payment as OP;
// 2016-07-20
final class Method extends \Df\PaypalClone\Method {
	/**
	 * 2016-07-20
	 * 2017-03-12 Используем @uses df_cts(), чтобы избавиться от окончания «\Interceptor».
	 * 2017-03-14
	 * @uses df_tmf() вернёт null, если ПС ещё не присылала нам оповещений.
	 * В этом случае наш метод вернёт обобщённый класс @see \Dfe\AllPay\Block\Info
	 * @override
	 * @see \Df\PaypalClone\Method::getInfoBlockType()
	 * @used-by \Magento\Payment\Helper\Data::getInfoBlock()
	 * @return string
	 */
	function getInfoBlockType() {/** @var Event $ev */ return df_cc_class(
		df_cts(Info::class), ($ev = df_tmf($this)) ? $ev->t() : null
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
	 * @used-by \Dfe\AllPay\Choice::title()
	 * @return Plan|null
	 */
	function plan() {return dfc($this, function() {/** @var int|string|null $id */ return
		!ctype_digit($id = $this->option()) ? null : $this->s()->installmentSales()->plans($id)
	;});}

	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::amountFactor()
	 * @used-by \Df\Payment\Method::amountFormat()
	 * @used-by \Df\Payment\Method::amountParse()
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
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Charge::_requestI()
	 * @used-by \Dfe\AllPay\W\Event\Offline::paidTime()
	 */
	const TIMEZONE = 'Asia/Taipei';

	/**
	 * 2016-08-15
	 * @used-by iiaKeys()
	 * @used-by plan()
	 */
	private static $II_OPTION = 'option';
}