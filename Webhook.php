<?php
namespace Dfe\AllPay;
use Df\Framework\Controller\Result\Text;
use Df\Sales\Model\Order as DfOrder;
use Dfe\AllPay\Source\Option;
use Magento\Framework\Phrase;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Payment as OP;
use Zend_Date as ZD;
/**
 * 2016-07-09
 * The response is documented in the Chapter 7 «Payment Result Notification»
 * on the pages 32-35 of the allPay documentation.
 */
abstract class Webhook extends \Df\PaypalClone\Webhook {
	/**
	 * 2016-07-20
	 * @return string
	 */
	public function classSuffix() {return dfc($this, function() {return
		self::classSuffixS($this->type())
	;});}

	/**
	 * 2016-07-18
	 * @override
	 * @see \Df\Payment\Webhook::typeLabel()
	 * @used-by \Df\Payment\Webhook::log()
	 * @return string
	 */
	final public function typeLabel() {return dfc($this, function() {
		/** @var string $result */
		$result = $this->type();
		df_assert_string_not_empty($result);
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
	protected function config() {return [
		self::$externalIdKey => 'TradeNo'
		,self::$signatureKey => 'CheckMacValue'
		,self::$statusKey => 'RtnCode'
		,self::$typeKey => 'PaymentType'
	];}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\Webhook::resultSuccess()
	 * @used-by \Df\Payment\Webhook::handle()
	 * @return Text
	 */
	protected function resultSuccess() {return Text::i('1|OK');}

	/**
	 * 2016-08-27
	 * «Value 1 means a payment is paid successfully. The other means failure.»
	 * @override
	 * @see \Df\Payment\Webhook::statusExpected()
	 * @used-by \Df\Payment\Webhook::isSuccessful()
	 * @return string|int
	 */
	protected function statusExpected() {return 1;}

	/**
	 * 2016-08-09
	 * @used-by typeLabel()
	 * @param string $codeFirst
	 * @param string $codeLast
	 * @return string|null
	 */
	protected function typeLabelByCode($codeFirst, $codeLast) {return
		dfa_deep($this->moduleJson('labels'), [$codeFirst, $codeLast])
	;}

	/**
	 * 2016-07-13
	 * @override
	 * @see \Df\Payment\Webhook::i()
	 * @param array(string => mixed) $params
	 * @return self
	 */
	public static function i($params) {
		/** @var string|null $classSuffix */
		$classSuffix = dfa($params, 'class', self::classSuffixS(dfa($params, 'PaymentType')));
		if (!$classSuffix) {
			df_error('The request is invalid');
		}
		if (isset($params['class'])) {
			unset($params['class']);
			$params[self::$dfTest] = 1;
		}
		return self::ic(df_con(static::class, df_cc_class('Webhook', $classSuffix)), $params);
	}

	/**
	 * 2016-07-26
	 * @override
	 * @see \Df\Payment\Webhook::resultError()
	 * @used-by \Df\Payment\Webhook::handle()
	 * @used-by \Df\Payment\Action\Webhook::execute()
	 * @param \Exception $e
	 * @return Text
	 */
	public static function resultError(\Exception $e) {return Text::i('0|' . df_lets($e));}

	/**
	 * 2016-07-28
	 * @used-by \Dfe\AllPay\Webhook\Offline::paidTime()
	 * @used-by \Dfe\AllPay\Block\Info\BankCard::custom()
	 * @param string|null $timeS
	 * @return ZD|null
	 */
	public static function time($timeS) {return dfcf(function($timeS) {return
		!$timeS ? null : df_date_parse($timeS, 'y/MM/dd HH:mm:ss', Method::TIMEZONE)
	;}, func_get_args());}

	/**
	 * 2016-07-20
	 * @param string $type
	 * @return string
	 */
	private static function classSuffixS($type) {return dftr(df_first(explode('_', $type)), [
		Option::BANK_CARD => 'BankCard', 'BARCODE' => 'Barcode'
	]);}
}