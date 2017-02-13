<?php
namespace Dfe\AllPay\Controller\CustomerReturn;
// 2017-02-14
/** @final Unable to use the PHP «final» keyword because of the M2 code generation. */
class Index extends \Df\Payment\Action\CustomerReturn {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\Action\CustomerReturn::message()
	 * @used-by \Df\Payment\Action\CustomerReturn::execute()
	 * @return string
	 */
	protected function message() {return $this->transP('RtnMsg');}
}