<?php
namespace Dfe\AllPay\W\Handler;
// 2016-07-25
final class Barcode extends Offline {
	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\W\Handler\Offline::statusExpectedOffline()
	 * @used-by \Dfe\AllPay\W\Handler\Offline::statusExpected()
	 * @return int
	 * «Successfully gets the number for CVS or BARCODE when value is 10100073.»
	 */
	protected function statusExpectedOffline() {return 10100073;}
}