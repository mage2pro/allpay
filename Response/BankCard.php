<?php
namespace Dfe\AllPay\Response;
class BankCard extends \Dfe\AllPay\Response {
	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\R\Response::needCapture()
	 * @used-by \Df\Payment\R\Response::handle()
	 * @return bool
	 */
	protected function needCapture() {return true;}
}

