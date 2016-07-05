<?php
namespace Dfe\AllPay;
use Magento\Framework\App\ScopeInterface as S;
class Settings extends \Df\Core\Settings {
	/**
	 * 2016-03-09
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Default Payment Method»
	 * @see \Dfe\AllPay\Source\PaymentType::map()
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	public function defaultPaymentMethod($s = null) {return $this->v(__FUNCTION__, $s);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Description»
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	public function description($s = null) {return $this->v(__FUNCTION__, $s);}

	/**
	 * 2016-07-01
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Description on a kiosk's screen»
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	public function descriptionOnKiosk($s = null) {return $this->v(__FUNCTION__, $s);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Enable?»
	 * @param null|string|int|S $s [optional]
	 * @return bool
	 */
	public function enable($s = null) {return $this->b(__FUNCTION__, $s);}

	/**
	 * 2016-06-29
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	public function hashIV($s = null) {
		return $this->test($s) ? $this->testHashIV($s) : $this->liveHashIV($s);
	}

	/**
	 * 2016-06-29
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	public function hashKey($s = null) {
		return $this->test($s) ? $this->testHashKey($s) : $this->liveHashKey($s);
	}

	/**
	 * 2016-06-29
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	public function merchantID($s = null) {
		return $this->test($s) ? $this->testMerchantID($s) : $this->liveMerchantID($s);
	}

	/**
	 * 2016-07-05
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Allowed Payment Methods»
	 * @param null|string|int|S $s [optional]
	 * @return string[]
	 */
	public function methodsAllowed($s = null) {return $this->csv(__FUNCTION__, $s);}

	/**
	 * 2016-07-05
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Limit Payment Methods Availability?»
	 * @param null|string|int|S $s [optional]
	 * @return bool
	 */
	public function methodsLimit($s = null) {return $this->b(__FUNCTION__, $s);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Test Mode?»
	 * @param null|string|int|S $s [optional]
	 * @return bool
	 */
	public function test($s = null) {return $this->b(__FUNCTION__, $s);}

	/**
	 * 2016-07-01
	 * @override
	 * @used-by \Df\Core\Settings::v()
	 * @return string
	 */
	protected function prefix() {return 'df_payment/all_pay/';}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Live ALL IN ONE 介接 HashIV»
	 * Note that we encrypt the live keys, but do not encrypt the test keys.
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	private function liveHashIV($s = null) {return $this->p(__FUNCTION__, $s);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Live ALL IN ONE 介接 HashKey»
	 * Note that we encrypt the live keys, but do not encrypt the test keys.
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	private function liveHashKey($s = null) {return $this->p(__FUNCTION__, $s);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Live Merchant ID (商店代號)»
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	private function liveMerchantID($s = null) {return $this->v(__FUNCTION__, $s);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Test ALL IN ONE 介接 HashIV»
	 * Note that we encrypt the live keys, but do not encrypt the test keys.
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	private function testHashIV($s = null) {return $this->v(__FUNCTION__, $s);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Test ALL IN ONE 介接 HashKey»
	 * Note that we encrypt the live keys, but do not encrypt the test keys.
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	private function testHashKey($s = null) {return $this->v(__FUNCTION__, $s);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Test Merchant ID (商店代號)»
	 * @param null|string|int|S $s [optional]
	 * @return string
	 */
	private function testMerchantID($s = null) {return $this->v(__FUNCTION__, $s);}

	/** @return self */
	public static function s() {static $r; return $r ? $r : $r = df_o(__CLASS__);}
}


