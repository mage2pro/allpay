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
		self::$ALLPAY => 'on the allPay payment page'
		,self::$MAGENTO => 'on the Magento checkout page'
	];}

	/** @var string */
	private static $ALLPAY = 'allpay';
	/** @var string */
	private static $MAGENTO = 'magento';
}