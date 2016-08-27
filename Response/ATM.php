<?php
namespace Dfe\AllPay\Response;
class ATM extends Offline {
	/**
	 * 2016-07-20
	 * @override
	 * @see \Dfe\AllPay\Response\Offline::statusExpectedOffline()
	 * @used-by \Dfe\AllPay\Response\Offline::statusExpected()
	 * @return int
	 * «Successfully gets the number for ATM when value is 2.»
	 */
	protected function statusExpectedOffline() {return 2;}
}