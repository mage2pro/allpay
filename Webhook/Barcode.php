<?php
namespace Dfe\AllPay\Webhook;
// 2016-07-25
class Barcode extends Offline {
	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Webhook\Offline::statusExpectedOffline()
	 * @used-by \Dfe\AllPay\Webhook\Offline::statusExpected()
	 * @return int
	 * «Successfully gets the number for CVS or BARCODE when value is 10100073.»
	 */
	protected function statusExpectedOffline() {return 10100073;}
}