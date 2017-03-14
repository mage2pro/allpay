<?php
namespace Dfe\AllPay\W\Handler;
// 2016-07-20
final class BankCard extends \Dfe\AllPay\W\Handler {
	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\PaypalClone\W\Confirmation::needCapture()
	 * @used-by \Df\PaypalClone\W\Confirmation::_handle()
	 * @return bool
	 */
	protected function needCapture() {return true;}
}