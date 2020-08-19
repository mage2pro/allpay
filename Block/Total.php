<?php
namespace Dfe\AllPay\Block;
use Dfe\AllPay\Total\Quote as TQuote;
# 2016-08-13
/** @final Unable to use the PHP «final» keyword here because of the M2 code generation. */
class Total extends \Df\Sales\Block\Order\Total {
	/**
	 * 2016-08-13
	 * 2016-08-14
	 * «grand_total» определено здесь: @see \Magento\Sales\Block\Order\Totals::_initTotals()
	 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Sales/Block/Order/Totals.php#L150-L158
	 * @final Unable to use the PHP «final» keyword here because of the M2 code generation.
	 * @override
	 * @see \Df\Sales\Block\Order\Total::initTotals()
	 * @used-by \Magento\Sales\Block\Order\Totals::_beforeToHtml()
	 *		protected function _beforeToHtml() {
	 *			$this->_initTotals();
	 *			foreach ($this->getLayout()->getChildBlocks($this->getNameInLayout()) as $child) {
	 *				if (method_exists($child, 'initTotals') && is_callable([$child, 'initTotals'])) {
	 *					$child->initTotals();
	 *				}
	 *			}
	 *			return parent::_beforeToHtml();
	 *		}
	 * https://github.com/magento/magento2/blob/2.2.0-RC1.8/app/code/Magento/Sales/Block/Order/Totals.php#L51-L65
	 */
	function initTotals() {
		list($v, $b) = TQuote::iiGet($this->op()); /** @var float|null $v */ /** @var float|null $b */
		if ($v) {
			$this->addBefore('dfe_allpay', 'Installment Fee', $v, $b, 'grand_total');
		}
	}
}