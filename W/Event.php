<?php
namespace Dfe\AllPay\W;
use Dfe\AllPay\Method;
use Zend_Date as ZD;
/**
 * 2016-07-09
 * The response is documented in the Chapter 7 «Payment Result Notification»
 * on the pages 32-35 of the allPay documentation: https://mage2.pro/t/2837
 * 2017-03-13
 * @see \Dfe\AllPay\W\Event\BankCard
 * @see \Dfe\AllPay\W\Event\Offline
 * 2017-03-16 Этот класс намеренно НЕ абстрактный, потому что он напрямую используется для WebATM.
 */
class Event extends \Df\PaypalClone\W\Event {
	/**
	 * 2017-03-16
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_idE()
	 * @used-by \Df\PaypalClone\W\Event::idE()
	 */
	final protected function k_idE():string {return 'TradeNo';}
	
	/**
	 * 2017-03-16
	 * @override
	 * @see \Df\Payment\W\Event::k_pid()
	 * @used-by \Df\Payment\W\Event::pid()
	 */
	final protected function k_pid():string {return 'MerchantTradeNo';}

	/**
	 * 2017-03-18
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_signature()
	 * @used-by \Df\PaypalClone\W\Event::signatureProvided()
	 */
	final protected function k_signature():string {return 'CheckMacValue';}

	/**
	 * 2017-03-18
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_status()
	 * @used-by \Df\PaypalClone\W\Event::status()
	 */
	final protected function k_status():string {return 'RtnCode';}

	/**
	 * 2016-08-27 «Value 1 means a payment is paid successfully. The other means failure.»
	 * 2017-03-18
	 * @override
	 * @see \Df\PaypalClone\W\Event::statusExpected()
	 * @used-by \Df\PaypalClone\W\Event::isSuccessful() 
	 * @see \Dfe\AllPay\W\Event\Offline::statusExpected()
	 */
	protected function statusExpected():int {return 1;}

	/**
	 * 2017-03-10
	 * @override
	 * @see \Df\Payment\W\Event::tl_()
	 * @used-by \Df\Payment\W\Event::tl()
	 * @param string $t
	 */
	final protected function tl_($t):string {return
		/** @var string[] $a */ /** @var string|null $l */
		2 > count($a = explode('_', df_param_sne($t, 0))) || !($l = $this->tlByCode($f = $a[0], $a[1]))
		? $t : (!in_array($f, ['ATM', 'WebATM']) ? __($l) : df_cc_s(__($f), __($l)))
	;}

	/**
	 * 2016-08-09
	 * @used-by self::tl_()
	 * @see \Dfe\AllPay\W\Event\BankCard::tlByCode()
	 * @param string $f
	 * @param string $l
	 * @return string|null
	 */
	protected function tlByCode($f, $l) {return dfa_deep(df_module_json($this, 'labels'), [$f, $l]);}

	/**
	 * 2017-03-13
	 * @override
	 * @see \Df\Payment\W\Event::useRawTypeForLabel()
	 * @used-by \Df\Payment\W\Event::tl()
	 */
	final protected function useRawTypeForLabel():bool {return true;}

	/**
	 * 2016-07-28
	 * @used-by \Dfe\AllPay\W\Event\BankCard::authTime()
	 * @used-by \Dfe\AllPay\W\Event\Offline::paidTime()
	 * @param string|null $timeS
	 * @return ZD|null
	 */
	final protected static function time($timeS) {return dfcf(function($timeS) {return
		!$timeS ? null : df_date_parse($timeS, true, 'y/MM/dd HH:mm:ss', Method::TIMEZONE)
	;}, [$timeS]);}
}