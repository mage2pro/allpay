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
abstract class Response extends \Df\Payment\R\Response {
	/**
	 * 2016-07-20
	 * @return string
	 */
	public function classSuffix() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = self::classSuffixS($this['PaymentType']);
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-18
	 * @return string
	 */
	public function paymentOptionTitle() {
		if (!isset($this->{__METHOD__})) {
			/** @var string $result */
			$result = $this['PaymentType'];
			df_assert_string_not_empty($result);
			/** @var string[] $a */
			$a = explode('_', $result);
			/** @var int $c */
			$c = count($a);
			if (1 < $c) {
				/** @var string $f */
				$f = $a[0];
				/** @var string|null */
				$resultD = $this->paymentOptionTitleByCode($f, $a[1]);
				if ($resultD) {
					$resultD = __($resultD);
					$result = in_array($f, ['ATM', 'WebATM']) ? df_cc_s(__($f), $resultD) : $resultD;
				}
			}
			$this->{__METHOD__} = $result;
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\R\Response::config()
	 * @used-by \Df\Payment\R\Response::configCached()
	 * @return array(string => mixed)
	 */
	protected function config() {return [
		self::$externalIdKey => 'TradeNo'
		,self::$requestIdKey => 'MerchantTradeNo'
		,self::$signatureKey => 'CheckMacValue'
		,self::$statusKey => 'RtnCode'
	];}

	/**
	 * 2016-08-09
	 * @used-by \Dfe\AllPay\Response::paymentOptionTitle()
	 * @param string $codeFirst
	 * @param string $codeLast
	 * @return string|null
	 */
	protected function paymentOptionTitleByCode($codeFirst, $codeLast) {
		return dfa_deep($this->moduleJson('titles'), [$codeFirst, $codeLast]);
	}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\R\Response::resultSuccess()
	 * @used-by \Df\Payment\R\Response::handle()
	 * @return Text
	 */
	protected function resultSuccess() {return Text::i('1|OK');}

	/**
	 * 2016-08-27
	 * «Value 1 means a payment is paid successfully. The other means failure.»
	 * @override
	 * @see \Df\Payment\R\Response::statusExpected()
	 * @used-by \Df\Payment\R\Response::isSuccessful()
	 * @return string|int
	 */
	protected function statusExpected() {return 1;}

	/**
	 * 2016-07-13
	 * @override
	 * @see \Df\Payment\R\Response::i()
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
			$params[$params[self::$dfTest]] = 1;
		}
		/** @var string $class */
		$class = df_con(static::class, df_cc_class('Response', $classSuffix));
		return self::ic($class, $params);
	}

	/**
	 * 2016-07-26
	 * @override
	 * @see \Df\Payment\R\Response::resultError()
	 * @used-by \Df\Payment\R\Response::handle()
	 * @used-by \Df\Payment\R\Confirm::execute()
	 * @param \Exception $e
	 * @return Text
	 */
	public static function resultError(\Exception $e) {return Text::i('0|' . df_lets($e));}

	/**
	 * 2016-07-28
	 * @used-by \Dfe\AllPay\Response\Offline::paidTime()
	 * @used-by \Dfe\AllPay\Block\Info\BankCard::custom()
	 * @param string|null $timeS
	 * @return ZD|null
	 */
	public static function time($timeS) {
		/** @var array(string|null => ZD|string) $cache */
		static $cache;
		if (!isset($cache[$timeS])) {
			$cache[$timeS] = df_n_set(
				!$timeS ? null : df_date_parse($timeS, 'y/MM/dd HH:mm:ss', Method::TIMEZONE)
			);
		}
		return df_n_get($cache[$timeS]);
	}

	/**
	 * 2016-07-20
	 * @param string $type
	 * @return string
	 */
	private static function classSuffixS($type) {
		/** @var string $result */
		$result = df_first(explode('_', $type));
		return dfa([Option::BANK_CARD => 'BankCard', 'BARCODE' => 'Barcode'], $result, $result);
	}
}


