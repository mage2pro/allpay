<?php
namespace Dfe\AllPay\Webhook;
class WebATM extends \Dfe\AllPay\Webhook {
	/**
	 * 2016-07-26
	 * @override
	 * @see \Df\Payment\Webhook::needCapture()
	 * @used-by \Df\Payment\Webhook::handle()
	 * @return bool
	 */
	protected function needCapture() {return true;}
}