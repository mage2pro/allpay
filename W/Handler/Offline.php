<?php
namespace Dfe\AllPay\W\Handler;
/**
 * 2016-07-19
 * @see \Dfe\AllPay\W\Handler\ATM
 * @see \Dfe\AllPay\W\Handler\Barcode
 * @see \Dfe\AllPay\W\Handler\CVS
 */
abstract class Offline extends \Dfe\AllPay\W\Handler {
	/**
	 * 2016-07-20
	 * @used-by statusExpected()
	 * @return int
	 */
	abstract protected function statusExpectedOffline();

	/**
	 * 2017-01-02
	 * @used-by \Dfe\AllPay\Controller\Offline\Index::prepare()
	 * @param bool $v
	 * @return void
	 */
	function needCaptureSet($v) {$this->_needCapture = $v;}
	
	/**
	 * 2016-07-20
	 * 2017-01-04
	 * Своим поведением этот метод напоминает мне @see \Df\StripeClone\Method::e2i()
	 * @override
	 * @see \Df\Payment\W\Handler::id()
	 * @used-by \Df\Payment\W\Handler::ii()
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
	 * @see \Df\PaypalClone\W\Confirmation::needCapture()
	 * @used-by \Df\PaypalClone\W\Confirmation::_handle()
	 * @used-by statusExpected()
	 * @return bool
	 */
	protected function needCapture() {return $this->_needCapture;}

	/**
	 * 2016-08-27
	 * «Value 1 means a payment is paid successfully. The other means failure.»
	 * @override
	 * @see \Df\PaypalClone\W\Handler::statusExpected()
	 * @used-by \Df\PaypalClone\W\Handler::isSuccessful()
	 * @return string|int
	 */
	protected function statusExpected() {return
		$this->needCapture() ? parent::statusExpected() : $this->statusExpectedOffline()
	;}

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