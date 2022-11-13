<?php
namespace Dfe\AllPay\W;
use Df\Framework\W\Result\Text;
# 2017-09-13
final class Responder extends \Df\Payment\W\Responder {
	/**
	 * 2017-01-04
	 * @override
	 * @see \Df\Payment\W\Responder::error()
	 * @used-by \Df\Payment\W\Responder::setError()
	 * @param \Exception|string $e
	 */
	protected function error($e):Text {return Text::i('0|' . df_lxts($e));}

	/**
	 * 2017-01-04
	 * @override
	 * @see \Df\Payment\W\Responder::notForUs()
	 * @used-by \Df\Payment\W\Responder::setNotForUs()
	 */
	protected function notForUs(string $m):Text {return $this->success();}

	/**
	 * 2017-09-13
	 * @override
	 * @see \Df\Payment\W\Responder::success()
	 * @used-by self::notForUs()
	 * @used-by \Df\Payment\W\Responder::get()
	 */
	protected function success():Text {return Text::i('1|OK');}
}