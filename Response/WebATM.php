<?php
namespace Dfe\AllPay\Response;
class WebATM extends \Dfe\AllPay\Response {
	/**
	 * 2016-07-26
	 * @override
	 * @see \Df\Payment\R\Response::needCapture()
	 * @used-by \Df\Payment\R\Response::handle()
	 * @return bool
	 */
	protected function needCapture() {return true;}
}