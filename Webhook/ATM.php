<?php
namespace Dfe\AllPay\Webhook;
// 2016-07-20
final class ATM extends Offline {
	/**
	 * 2016-07-20
	 * @override
	 * @see \Dfe\AllPay\Webhook\Offline::statusExpectedOffline()
	 * @used-by \Dfe\AllPay\Webhook\Offline::statusExpected()
	 * @return int
	 * «Successfully gets the number for ATM when value is 2.»
	 */
	protected function statusExpectedOffline() {return 2;}
}