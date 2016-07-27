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
	public function execute() {
		try {$result = R::i($this->additionalParams() + $_REQUEST)->handle();}
		catch (\Exception $e) {$result = R::resultErrorStatic($e);}
		return $result;
	}

	/**
	 * 2016-07-26
	 * @used-by \Dfe\AllPay\Controller\Confirm\Index::execute()
	 * @return array(string => string)
	 */
	protected function additionalParams() {return [];}
}