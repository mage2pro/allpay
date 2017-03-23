<?php
namespace Dfe\AllPay\Init;
// 2017-03-22
/** @method \Dfe\AllPay\Method m() */
final class Action extends \Df\PaypalClone\Init\Action {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\Init\Action::redirectUrl()
	 * @used-by \Df\Payment\Init\Action::action()
	 * @return string
	 */
	protected function redirectUrl() {return 'https://payment{stage}.allpay.com.tw/Cashier/AioCheckOut/V2';}
}