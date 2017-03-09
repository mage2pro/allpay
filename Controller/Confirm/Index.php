<?php
namespace Dfe\AllPay\Controller\Confirm;
use Dfe\AllPay\Webhook as W;
// 2017-02-14
/** @see \Dfe\AllPay\Controller\Offline\Index */
class Index extends \Df\Payment\WebhookA {
	/**
	 * 2017-01-02
	 * @override
	 * @see \Df\Payment\WebhookA::error()
	 * @used-by \Df\Payment\WebhookA::execute()
	 * @param \Exception $e
	 * @return $this
	 */
	final protected function error(\Exception $e) {return W::resultError($e);}
}