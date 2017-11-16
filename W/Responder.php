<?php
namespace Dfe\AllPay\W;
use Df\Framework\W\Response\Text;
// 2017-09-13
final class Responder extends \Df\Payment\W\Responder {
	/**
	 * 2017-01-04
	 * @override
	 * @see \Df\Payment\W\Responder::error()
	 * @used-by \Df\Payment\W\Responder::setError()
	 * @param \Exception $e
	 * @return Text
	 */
	protected function error(\Exception $e) {return Text::i('0|' . df_lets($e));}

	/**
	 * 2017-01-04
	 * @override
	 * @see \Df\Payment\W\Responder::notForUs()
	 * @used-by \Df\Payment\W\Responder::setNotForUs()
	 * @param string|null $message [optional]
	 * @return Text
	 */
	protected function notForUs($message = null) {return $this->success();}

	/**
	 * 2017-09-13
	 * @override
	 * @see \Df\Payment\W\Responder::success()
	 * @used-by notForUs()
	 * @used-by \Df\Payment\W\Responder::get()
	 * @return Text
	 */
	protected function success() {return Text::i('1|OK');}
}