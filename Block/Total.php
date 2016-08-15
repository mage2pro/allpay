<?php
namespace Dfe\AllPay\Block;
use Dfe\AllPay\Total\Quote as TQuote;
class Total extends \Df\Sales\Block\Order\Total {
	/**
	 * 2016-08-13
	 * @override
	 * @see \Df\Sales\Block\Order\Total::initTotals()
	 * @used-by \Magento\Sales\Block\Order\Totals::_beforeToHtml()
	 * @return void
	 */
	public function initTotals() {
		/** @var float|null $v */
		/** @var float|null $b */
		list($v, $b) = TQuote::iiGet($this->payment());
		if ($v) {
			/**
			 * 2016-08-14
			 * «grand_total» определено здесь: @see \Magento\Sales\Block\Order\Totals::_initTotals()
			 * https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Sales/Block/Order/Totals.php#L150-L158
			 */
			$this->addBefore('dfe_allpay', 'Installment Fee', $v, $b, 'grand_total');
		}
	}
}


