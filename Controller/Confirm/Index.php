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
			$this->log($_REQUEST);
			$result = Text::i('1|OK');
		}
		catch (\Exception $e) {
			$result = Text::i('0|' . df_le($e)->getMessage());
		}
		return $result;
	}

	/**
	 * 2016-07-06
	 * @param mixed $message
	 * @return void
	 */
	private function log($message) {if (!df_is_it_my_local_pc()) {df_log($message);}}
}

