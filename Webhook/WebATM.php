<?php
namespace Dfe\AllPay\Webhook;
// 2016-07-26
final class WebATM extends \Dfe\AllPay\Webhook {
	/**
	 * 2016-07-26
	 * @override
	 * @see \Df\PaypalClone\Confirmation::needCapture()
	 * @used-by \Df\PaypalClone\Confirmation::_handle()
	 * @return bool
	 */
	protected function needCapture() {return true;}
}