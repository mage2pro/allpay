<?php
namespace Dfe\AllPay\Source;
use Magento\Sales\Model\Order as O;
/**
 * 2016-07-19
 * @method static WaitPeriodType s()
 */
class WaitPeriodType extends \Df\Config\SourceT {
	/**
	 * 2016-07-19
	 * @override
	 * @see \Df\Config\Source::map()
	 * @used-by \Df\Config\Source::toOptionArray()
	 * @return array(string => string)
	 */
	protected function map() {return [
		self::CALENDAR_DAYS => 'Calendar Days'
		,self::WORKING_DAYS => 'Working Days'
	];}

	const CALENDAR_DAYS = 'calendar_days';
	const WORKING_DAYS = 'working_days';
}