<?php
namespace Dfe\AllPay\Block;
use Magento\Framework\DataObject;
class Info extends \Df\Payment\Block\ConfigurableInfo {
	/**
	 * 2016-07-12
	 * @override
	 * @see \Df\Payment\Block\ConfigurableInfo::_isSandbox()
	 * @used-by \Df\Payment\Block\ConfigurableInfo::isSandbox()
	 * @return bool
	 */
	protected function _isSandbox() {return false;}
}


