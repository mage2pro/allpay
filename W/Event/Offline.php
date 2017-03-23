<?php
namespace Dfe\AllPay\W\Event;
use Dfe\AllPay\Charge;
use Dfe\AllPay\Source\Option;
use Zend_Date as ZD;
// 2017-03-13
final class Offline extends \Dfe\AllPay\W\Event {
	/**
	 * 2016-07-19
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @return string
	 */
	function expirationS() {return dfc($this, function() {
		/** @var ZD $exp */
		/** @var string $result */
		$result = df_dts($exp = new ZD($this->r('ExpireDate'), 'y/MM/dd'), ZD::DATE_LONG);
		/** @var int $d */
		/** @var string $note */
		$note = 0 > ($d = df_days_left($exp)) ? __('expired') : (
			0 === $d ? __('today') : (1 === $d ? __('1 day left') : __('%1 days left', $d))
		);
		return "{$result} ({$note})";
	});}

	/**
	 * 2017-03-16
	 * @override
	 * @see \Df\PaypalClone\W\Event::needCapture()
	 * @used-by statusExpected()
	 * @used-by \Df\PaypalClone\W\Handler::_handle()
	 * @used-by \Dfe\AllPay\W\Nav\Offline::id()
	 * @return bool
	 */
	function needCapture() {return !df_action_has(Charge::OFFLINE);}

	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @return ZD|null
	 */
	function paidTime() {return self::time($this->r('PaymentDate'));}

	/**
	 * 2016-07-20
	 * «Successfully gets the number for ATM when value is 2.»
	 * «Successfully gets the number for CVS or BARCODE when value is 10100073.»
	 * 2017-03-18
	 * @override
	 * @see \Dfe\AllPay\W\Event::statusExpected()
	 * @used-by \Df\PaypalClone\W\Event::isSuccessful()
	 * @return int
	 */
	protected function statusExpected() {return $this->needCapture() ? parent::statusExpected() : (
		Option::ATM === $this->t() ? 2 : 10100073
	);}
}