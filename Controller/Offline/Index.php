<?php
namespace Dfe\AllPay\Controller\Offline;
use Dfe\AllPay\Response as R;
use Dfe\AllPay\Response\Offline;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-07-20
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return \Df\Framework\Controller\Result\Text
	 */
	public function execute() {return R::i([Offline::KEY => true] + $_REQUEST)->handle();}
}


