<?php
namespace Dfe\AllPay\W;
use Df\Framework\Controller\Result\Text;
use Df\Sales\Model\Order as DfOrder;
use Magento\Sales\Model\Order\Payment as OP;
/**
 * 2016-07-09
 * The response is documented in the Chapter 7 «Payment Result Notification»
 * on the pages 32-35 of the allPay documentation.
 * @see \Dfe\AllPay\W\Handler\Offline
 * @see \Dfe\AllPay\W\Handler\BankCard
 * @see \Dfe\AllPay\W\Handler\WebATM
 */
abstract class Handler extends \Df\PaypalClone\W\Confirmation {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\W\Handler::config()
	 * @used-by \Df\Payment\W\Handler::configCached()
	 * @return array(string => mixed)
	 */
	final protected function config() {return [
		self::$externalIdKey => 'TradeNo'
		,self::$signatureKey => 'CheckMacValue'
		// 2016-08-27
		// «Value 1 means a payment is paid successfully. The other means failure.»
		,self::$statusExpected => 1
		,self::$statusKey => 'RtnCode'
	];}

	/**
	 * 2017-03-06
	 * @override
	 * @see \Df\Payment\W\Handler::parentIdRawKey()
	 * @used-by \Df\Payment\W\Handler::parentIdRaw()
	 * @return string
	 */
	final protected function parentIdRawKey() {return 'MerchantTradeNo';}

	/**
	 * 2017-01-04
	 * @override
	 * @see \Df\Payment\W\Handler::resultNotForUs()
	 * @used-by \Df\Payment\W\Handler::handle()
	 * @param string|null $message [optional]
	 * @return Text
	 */
	final protected function resultNotForUs($message = null) {return $this->result();}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\W\Handler::result()
	 * @used-by \Df\Payment\W\Handler::handle()
	 * @return Text
	 */
	final protected function result() {return Text::i('1|OK');}

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