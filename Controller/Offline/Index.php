<?php
namespace Dfe\AllPay\Controller\Offline;
use Dfe\AllPay\Response\Offline;
class Index extends \Dfe\AllPay\Controller\Confirm\Index {
	/**
	 * 2016-07-26
	 * @override
	 * @see \Dfe\AllPay\Controller\Confirm\Index::additionalParams()
	 * @used-by \Dfe\AllPay\Controller\Confirm\Index::execute()
	 * @return array(string => string)
	 */
	protected function additionalParams() {return [Offline::KEY => true];}
}


