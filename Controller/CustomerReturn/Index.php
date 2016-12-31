<?php
namespace Dfe\AllPay\Controller\CustomerReturn;
class Index extends \Df\PaypalClone\CustomerReturn {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\PaypalClone\CustomerReturn::message()
	 * @used-by \Df\PaypalClone\CustomerReturn::execute()
	 * @return string
	 */
	protected function message() {return $this->transP('RtnMsg');}
}