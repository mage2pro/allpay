<?php
namespace Dfe\AllPay\Webhook;
use Zend_Date as ZD;
abstract class Offline extends \Dfe\AllPay\Webhook {
	/**
	 * 2016-07-20
	 * @used-by statusExpected()
	 * @return int
	 */
	abstract protected function statusExpectedOffline();

	/**
	 * 2016-07-19
	 * @return string
	 */
	public function expirationS() {return dfc($this, function() {
		/** @var string $result */
		$result = df_dts($this->expiration(), ZD::DATE_LONG);
		/** @var int $daysLeft */
		$daysLeft = df_days_left($this->expiration());
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
	 * @return bool
	 */
	public function isPaid() {return !!$this->paidTime();}

	/**
	 * 2017-01-02
	 * @used-by \Dfe\AllPay\Controller\Offline\Index::prepare()
	 * @param bool $v
	 * @return void
	 */
	public function needCaptureSet($v) {$this->_needCapture = $v;}

	/**
	 * 2016-07-20
	 * @return ZD|null
	 */
	public function paidTime() {return self::time($this->req('PaymentDate'));}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\Webhook::id()
	 * @used-by \Df\Payment\Webhook::payment()
	 * @return string
	 */
	final protected function id() {return implode('-', [
		parent::id(), $this->needCapture() ? 'capture' : 'info'
	]);}

	/**
	 * 2016-07-20
	 * 2017-01-02
	 * Результат этого метода зависит от контроллера:
	 * если контроллер — @see \Dfe\AllPay\Controller\Offline\Index,
	 * то needCapture() должен вернуть false,
	 * а если контроллер — класс @see \Dfe\AllPay\Controller\Confirm\Index,
	 * то needCapture() должен вернуть true.
	 * @override
	 * @see \Df\PaypalClone\Confirmation::needCapture()
	 * @used-by \Df\PaypalClone\Confirmation::_handle()
	 * @used-by statusExpected()
	 * @return bool
	 */
	protected function needCapture() {return $this->_needCapture;}

	/**
	 * 2016-08-27
	 * «Value 1 means a payment is paid successfully. The other means failure.»
	 * @override
	 * @see \Df\PaypalClone\Webhook::statusExpected()
	 * @used-by \Df\PaypalClone\Webhook::isSuccessful()
	 * @return string|int
	 */
	protected function statusExpected() {return
		$this->needCapture() ? parent::statusExpected() : $this->statusExpectedOffline()
	;}

	/**
	 * 2016-07-19
	 * @return ZD
	 */
	private function expiration() {return dfc($this, function() {return
		new ZD($this->req('ExpireDate'), 'y/MM/dd')
	;});}

	/**
	 * 2016-07-20
	 * 2017-01-02
	 * Значение этого поля зависит от контроллера:
	 * если контроллер — @see \Dfe\AllPay\Controller\Offline\Index,
	 * то needCapture() должен вернуть false,
	 * а если контроллер — класс @see \Dfe\AllPay\Controller\Confirm\Index,
	 * то needCapture() должен вернуть true.
	 * @used-by needCapture()
	 * @used-by needCaptureSet()
	 * @var bool
	 */
	private $_needCapture = true;
}