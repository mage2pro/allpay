<?php
namespace Dfe\AllPay\Controller\Confirm;
use Dfe\AllPay\Response as R;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-07-04
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return \Df\Framework\Controller\Result\Text
	 */
	public function execute() {return R::i($_REQUEST)->handle();}
}


