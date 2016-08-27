<?php
namespace Dfe\AllPay\Controller\CustomerReturn;
class Index extends \Df\Payment\R\CustomerReturn {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\R\CustomerReturn::message()
	 * @used-by \Df\Payment\R\CustomerReturn::execute()
	 * @return string
	 */
	protected function message() {return $this->transP('RtnMsg');}
}