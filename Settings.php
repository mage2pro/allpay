<?php
namespace Dfe\AllPay;
/** @method static Settings s() */
class Settings extends \Df\Payment\Settings {
	/**
	 * 2016-03-09
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Default Payment Method»
	 * @see \Dfe\AllPay\Source\PaymentType::map()
	 * @return string
	 */
	public function defaultPaymentMethod() {return $this->v(__FUNCTION__);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Description»
	 * @return string
	 */
	public function description() {return $this->v(__FUNCTION__);}

	/**
	 * 2016-07-01
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Description on a kiosk's screen»
	 * @return string
	 */
	public function descriptionOnKiosk() {return $this->v(__FUNCTION__);}

	/**
	 * 2016-06-29
	 * @return string
	 */
	public function hashIV() {return $this->test() ? $this->testHashIV() : $this->liveHashIV();}

	/**
	 * 2016-06-29
	 * @return string
	 */
	public function hashKey() {return $this->test() ? $this->testHashKey() : $this->liveHashKey();}

	/**
	 * 2016-06-29
	 * @return string
	 */
	public function merchantID() {
		return $this->test() ? $this->testMerchantID() : $this->liveMerchantID();
	}

	/**
	 * 2016-07-15
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Failure Message»
	 * @return string
	 */
	public function messageFailure() {return $this->v(__FUNCTION__);}

	/**
	 * 2016-07-05
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Allowed Payment Methods»
	 * @return string[]
	 */
	public function methodsAllowed() {return $this->csv(__FUNCTION__);}

	/**
	 * 2016-07-05
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Limit Payment Methods Availability?»
	 * @return bool
	 */
	public function methodsLimit() {return $this->b(__FUNCTION__);}

	/**
	 * 2016-03-15
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Payment Identification Type»
	 * @return string
	 */
	public function paymentIdentificationType() {return $this->v(__FUNCTION__);}

	/**
	 * 2016-07-19
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Wait period for an ATM payment»
	 * @return string
	 */
	public function waitPeriodATM() {return $this->nat(__FUNCTION__);}

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
	 * @return string
	 */
	private function liveHashIV() {return $this->p(__FUNCTION__);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Live ALL IN ONE 介接 HashKey»
	 * Note that we encrypt the live keys, but do not encrypt the test keys.
	 * @return string
	 */
	private function liveHashKey() {return $this->p(__FUNCTION__);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Live Merchant ID (商店代號)»
	 * @return string
	 */
	private function liveMerchantID() {return $this->v(__FUNCTION__);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Test ALL IN ONE 介接 HashIV»
	 * Note that we encrypt the live keys, but do not encrypt the test keys.
	 * @return string
	 */
	private function testHashIV() {return $this->v(__FUNCTION__);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Test ALL IN ONE 介接 HashKey»
	 * Note that we encrypt the live keys, but do not encrypt the test keys.
	 * @return string
	 */
	private function testHashKey() {return $this->v(__FUNCTION__);}

	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Test Merchant ID (商店代號)»
	 * @return string
	 */
	private function testMerchantID() {return $this->v(__FUNCTION__);}
}


