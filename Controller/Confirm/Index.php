<?php
namespace Dfe\AllPay\Controller\Confirm;
use Df\Framework\Controller\Result\Text;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-07-04
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return Text
	 */
	public function execute() {
		/** @var Text $result */
		try {
			$result = Text::i('1|OK');
		}
		catch (\Exception $e) {
			$result = Text::i('0|' . df_le($e)->getMessage());
		}
		return $result;
	}
}


