<?php
namespace Dfe\AllPay\Response;
// 2016-07-25
class CVS extends Offline {
	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Response\Offline::expectedRtnCodeOffline()
	 * @used-by \Dfe\AllPay\Response\Offline::expectedRtnCode()
	 * @return int
	 * «Successfully gets the number for CVS or BARCODE when value is 10100073.»
	 */
	protected function expectedRtnCodeOffline() {return 10100073;}
}

