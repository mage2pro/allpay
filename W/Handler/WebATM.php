<?php
namespace Dfe\AllPay\W\Handler;
// 2016-07-26
final class WebATM extends \Dfe\AllPay\W\Handler {
	/**
	 * 2016-07-26
	 * @override
	 * @see \Df\PaypalClone\W\Confirmation::needCapture()
	 * @used-by \Df\PaypalClone\W\Confirmation::_handle()
	 * @return bool
	 */
	protected function needCapture() {return true;}
}