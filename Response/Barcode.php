<?php
namespace Dfe\AllPay\Response;
// 2016-07-25
class Barcode extends Offline {
	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Response\Offline::statusExpectedOffline()
	 * @used-by \Dfe\AllPay\Response\Offline::statusExpected()
	 * @return int
	 * «Successfully gets the number for CVS or BARCODE when value is 10100073.»
	 */
	protected function statusExpectedOffline() {return 10100073;}
}