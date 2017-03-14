<?php
namespace Dfe\AllPay\W\Handler;
// 2016-07-20
final class ATM extends Offline {
	/**
	 * 2016-07-20
	 * @override
	 * @see \Dfe\AllPay\W\Handler\Offline::statusExpectedOffline()
	 * @used-by \Dfe\AllPay\W\Handler\Offline::statusExpected()
	 * @return int
	 * «Successfully gets the number for ATM when value is 2.»
	 */
	protected function statusExpectedOffline() {return 2;}
}