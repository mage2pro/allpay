<?php
namespace Dfe\AllPay\W\Event;
use Zend_Date as ZD;
/**
 * 2017-03-13
 * This event class is used for all the payment options except bank card.
 * @see \Dfe\AllPay\W\F::suf()
 */
final class Offline extends \Dfe\AllPay\W\Event {
	/**
	 * 2016-07-19
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @return string
	 */
	function expirationS() {return dfc($this, function() {
		/** @var ZD $exp */
		$exp = new ZD($this->r('ExpireDate'), 'y/MM/dd');
		/** @var string $result */
		$result = df_dts($exp, ZD::DATE_LONG);
		/** @var int $daysLeft */
		$daysLeft = df_days_left($exp);
		/** @var string $note */
		$note = 0 > $daysLeft ? __('expired') : (
			0 === $daysLeft ? __('today') : (
				1 === $daysLeft ? __('1 day left') :
					__('%1 days left', $daysLeft)
			)
		);
		return "{$result} ({$note})";
	});}

	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @return ZD|null
	 */
	function paidTime() {return self::time($this->r('PaymentDate'));}
}