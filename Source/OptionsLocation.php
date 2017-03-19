<?php
namespace Dfe\AllPay\Source;
// 2016-08-15
final class OptionsLocation extends \Df\Config\SourceT {
	/**
	 * 2016-08-15
	 * @override
	 * @see \Df\Config\Source::map()
	 * @used-by \Df\Config\Source::toOptionArray()
	 * @return array(string => string)
	 */
	protected function map() {return [
		'allpay' => 'on the allPay payment page', self::MAGENTO => 'on the Magento checkout page'
	];}

	/**
	 * 2017-03-19
	 * @used-by \Dfe\AllPay\ConfigProvider::config()
	 * @used-by \Dfe\AllPay\Source\OptionsLocation::map()
	 */
	const MAGENTO = 'magento';
}