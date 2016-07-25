<?php
namespace Dfe\AllPay\Response;
class ATM extends Offline {
	/**
	 * 2016-07-20
	 * @override
	 * @see \Dfe\AllPay\Response\Offline::expectedRtnCodeOffline()
	 * @used-by \Dfe\AllPay\Response\Offline::expectedRtnCode()
	 * @return int
	 * «Successfully gets the number for ATM when value is 2.»
	 */
	protected function expectedRtnCodeOffline() {return 2;}
}

