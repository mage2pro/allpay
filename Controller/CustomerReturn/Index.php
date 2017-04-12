<?php
namespace Dfe\AllPay\Controller\CustomerReturn;
// 2017-02-14
/** @final Unable to use the PHP «final» keyword here because of the M2 code generation. */
class Index extends \Df\Payment\CustomerReturn {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\CustomerReturn::message()
	 * @used-by \Df\Payment\CustomerReturn::execute()
	 * @return string
	 */
	final protected function message() {return df_request('RtnMsg');}
}