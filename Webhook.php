<?php
namespace Dfe\AllPay;
use Df\Framework\Controller\Result\Text;
use Df\Sales\Model\Order as DfOrder;
use Dfe\AllPay\Source\Option;
use Magento\Sales\Model\Order\Payment as OP;
use Zend_Date as ZD;
/**
 * 2016-07-09
 * The response is documented in the Chapter 7 «Payment Result Notification»
 * on the pages 32-35 of the allPay documentation.
 * @see \Dfe\AllPay\Webhook\Offline
 * @see \Dfe\AllPay\Webhook\BankCard
 * @see \Dfe\AllPay\Webhook\WebATM
 */
abstract class Webhook extends \Df\PaypalClone\Confirmation {
	/**
	 * 2016-07-20
	 * @return string
	 */
	final function classSuffix() {return dfc($this, function() {return self::classSuffixS($this->type());});}

	/**
	 * 2016-07-18
	 * @override
	 * @see \Df\Payment\Webhook::typeLabel()
	 * @used-by \Df\Payment\Webhook::log()
	 * @return string
	 */
	final function typeLabel() {return dfc($this, function() {
		/** @var string $result */
		$result = $this->type();
		df_assert_sne($result);
		/** @var string[] $a */
		$a = explode('_', $result);
		/** @var int $c */
		$c = count($a);
		if (1 < $c) {
			/** @var string $f */
			$f = $a[0];
			/** @var string|null */
			$resultD = $this->typeLabelByCode($f, $a[1]);
			if ($resultD) {
				$resultD = __($resultD);
				$result = in_array($f, ['ATM', 'WebATM']) ? df_cc_s(__($f), $resultD) : $resultD;
			}
		}
		return $result;
	});}

	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\Webhook::config()
	 * @used-by \Df\Payment\Webhook::configCached()
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
	 * @see \Df\Payment\Webhook::parentIdRawKey()
	 * @used-by \Df\Payment\Webhook::parentIdRaw()
	 * @return string
	 */
	final protected function parentIdRawKey() {return 'MerchantTradeNo';}

	/**
	 * 2017-01-04
	 * @override
	 * @see \Df\Payment\Webhook::resultNotForUs()
	 * @used-by \Df\Payment\Webhook::handle()
	 * @param string|null $message [optional]
	 * @return Text
	 */
	final protected function resultNotForUs($message = null) {return $this->result();}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\Webhook::result()
	 * @used-by \Df\Payment\Webhook::handle()
	 * @return Text
	 */
	final protected function result() {return Text::i('1|OK');}

	/**
	 * 2017-01-04
	 * @override
	 * @see \Df\PaypalClone\Confirmation::type()
	 * @used-by \Df\Payment\Webhook::typeLabel()
	 * @used-by \Dfe\AllPay\Webhook::classSuffix()
	 * @used-by \Dfe\AllPay\Webhook::typeLabel()
	 * @return string
	 */
	final protected function type() {return $this->req(WebhookF::KEY_TYPE);}

	/**
	 * 2016-08-09
	 * @used-by typeLabel()  
	 * @see \Dfe\AllPay\Webhook\BankCard::typeLabelByCode()
	 * @param string $codeFirst
	 * @param string $codeLast
	 * @return string|null
	 */
	protected function typeLabelByCode($codeFirst, $codeLast) {return
		dfa_deep(df_module_json($this, 'labels'), [$codeFirst, $codeLast])
	;}

	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\WebhookF::_class()
	 * @param string $type
	 * @return string
	 */
	static function classSuffixS($type) {return dftr(df_first(explode('_', $type)), [
		Option::BANK_CARD => 'BankCard', 'BARCODE' => 'Barcode'
	]);}

	/**
	 * 2016-07-26
	 * @override
	 * @see \Df\Payment\Webhook::resultError()
	 * @used-by \Dfe\AllPay\Controller\Confirm\Index::error()
	 * @used-by \Df\Payment\Webhook::handle()
	 * @param \Exception $e
	 * @return Text
	 */
	static function resultError(\Exception $e) {return Text::i('0|' . df_lets($e));}

	/**
	 * 2016-07-28
	 * @used-by \Dfe\AllPay\Webhook\Offline::paidTime()
	 * @used-by \Dfe\AllPay\Block\Info\BankCard::custom()
	 * @param string|null $timeS
	 * @return ZD|null
	 */
	static function time($timeS) {return dfcf(function($timeS) {return
		!$timeS ? null : df_date_parse($timeS, 'y/MM/dd HH:mm:ss', Method::TIMEZONE)
	;}, func_get_args());}
}