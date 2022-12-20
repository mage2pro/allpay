<?php
namespace Dfe\AllPay;
use Df\Payment\W\Event;
use Dfe\AllPay\Block\Info;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Magento\Sales\Model\Order as O;
use Magento\Sales\Model\Order\Address as OrderAddress;
use Magento\Sales\Model\Order\Payment as OP;
# 2016-07-20
final class Method extends \Df\PaypalClone\Method {
	/**
	 * 2016-07-20
	 * 2017-03-12 Используем @uses df_cts(), чтобы избавиться от окончания «\Interceptor».
	 * 2017-03-14
	 * @uses df_tmf() вернёт null, если ПС ещё не присылала нам оповещений.
	 * В этом случае наш метод вернёт обобщённый класс @see \Dfe\AllPay\Block\Info
	 * @override
	 * @see \Df\PaypalClone\Method::getInfoBlockType()
	 * @used-by \Magento\Payment\Helper\Data::getInfoBlock():
	 *		public function getInfoBlock(InfoInterface $info, LayoutInterface $layout = null) {
	 *			$layout = $layout ?: $this->_layout;
	 *			$blockType = $info->getMethodInstance()->getInfoBlockType();
	 *			$block = $layout->createBlock($blockType);
	 *			$block->setInfo($info);
	 *			return $block;
	 *		}
	 * https://github.com/magento/magento2/blob/2.2.0-RC1.6/app/code/Magento/Payment/Helper/Data.php#L182-L196
	 */
	function getInfoBlockType():string {/** @var Event $ev */ return df_cc_class(
		df_cts(Info::class), ($ev = df_tmf($this)) ? $ev->t() : ''
	);}

	/**
	 * 2017-03-05
	 * @used-by self::plan()
	 * @used-by \Dfe\AllPay\Charge::isSingleOptionChosen()
	 * @used-by \Dfe\AllPay\Charge::pChoosePayment()
	 * @return string|null
	 */
	function option() {return $this->iia(self::$II_OPTION);}

	/**
	 * 2016-08-13
	 * @used-by \Dfe\AllPay\Block\Info\BankCard::prepareUnconfirmed()
	 * @used-by \Dfe\AllPay\Charge::plan()
	 * @used-by \Dfe\AllPay\Choice::title()
	 * @return Plan|null
	 */
	function plan() {return dfc($this, function() {/** @var int|string|null $id */ return
		!df_is_nat($id = $this->option()) ? null : $this->s()->installmentSales()->plans($id)
	;});}

	/**
	 * 2016-11-13
	 * @override
	 * @see \Df\Payment\Method::amountFactor()
	 * @used-by \Df\Payment\Method::amountFormat()
	 * @used-by \Df\Payment\Method::amountParse()
	 */
	protected function amountFactor():int {return 1;}

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
	protected function iiaKeys():array {return [self::$II_OPTION];}

	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Charge::_requestI()
	 * @used-by \Dfe\AllPay\W\Event\Offline::paidTime()
	 */
	const TIMEZONE = 'Asia/Taipei';

	/**
	 * 2016-08-15 https://github.com/mage2pro/core/blob/2.12.17/Payment/view/frontend/web/withOptions.js#L56-L72
	 * @used-by self::iiaKeys()
	 * @used-by self::plan()
	 */
	private static $II_OPTION = 'option';
}