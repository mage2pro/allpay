<?php
namespace Dfe\AllPay\W;
use Df\Framework\Controller\Result\Text;
use Df\Sales\Model\Order as DfOrder;
use Magento\Sales\Model\Order\Payment as OP;
// 2016-07-09
final class Handler extends \Df\PaypalClone\W\Confirmation {
	/**
	 * 2017-01-04
	 * @override
	 * @see \Df\Payment\W\Handler::resultNotForUs()
	 * @used-by \Df\Payment\W\Handler::handle()
	 * @param string|null $message [optional]
	 * @return Text
	 */
	protected function resultNotForUs($message = null) {return $this->result();}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\W\Handler::result()
	 * @used-by \Df\Payment\W\Handler::handle()
	 * @return Text
	 */
	protected function result() {return Text::i('1|OK');}

	/**
	 * 2016-07-26
	 * @override
	 * @see \Df\Payment\W\Handler::resultError()
	 * @used-by \Dfe\AllPay\Controller\Confirm\Index::error()
	 * @used-by \Df\Payment\W\Handler::handle()
	 * @param \Exception $e
	 * @return Text
	 */
	static function resultError(\Exception $e) {return Text::i('0|' . df_lets($e));}
}