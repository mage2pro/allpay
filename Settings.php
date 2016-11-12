<?php
namespace Dfe\AllPay;
use Dfe\AllPay\Source\Option;
use Dfe\AllPay\Source\WaitPeriodType;
use Zend_Date as ZD;
/** @method static Settings s() */
final class Settings extends \Df\Payment\Settings {
	/**
	 * 2016-06-29
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Description»
	 * @return string
	 */
	public function description() {return $this->v();}

	/**
	 * 2016-07-01
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Description on a kiosk's screen»
	 * @return string
	 */
	public function descriptionOnKiosk() {return $this->v();}

	/**
	 * 2016-06-29
	 * «ALL IN ONE 介接 HashIV»
	 * Note that we encrypt the live keys, but do not encrypt the test keys.
	 * @return string
	 */
	public function hashIV() {return $this->testablePV();}

	/**
	 * 2016-06-29
	 * «LL IN ONE 介接 HashKey»
	 * Note that we encrypt the live keys, but do not encrypt the test keys.
	 * @return string
	 */
	public function hashKey() {return $this->testablePV();}

	/**
	 * 2016-07-31
	 * @return InstallmentSales\Settings
	 */
	public function installmentSales() {return $this->child(InstallmentSales\Settings::class);}

	/**
	 * 2016-06-29
	 * @return string
	 */
	public function merchantID() {return $this->testable();}

	/**
	 * 2016-07-05
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Allowed Payment Options»
	 * @return string[]
	 */
	public function optionsAllowed() {return $this->csv();}

	/**
	 * 2016-08-07
	 * @return array(string => string)
	 */
	public function options() {return
		Option::s()->options(!$this->optionsLimit() ? null : $this->optionsAllowed())
	;}

	/**
	 * 2016-07-05
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Limit Payment Options Availability?»
	 * @return bool
	 */
	public function optionsLimit() {return $this->b();}

	/**
	 * 2016-08-15
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Where to ask for a payment option?»
	 * @return string
	 */
	public function optionsLocation() {return $this->v();}

	/**
	 * 2016-07-17
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Payment Identification Type»
	 * @return string
	 */
	public function paymentIdentificationType() {return $this->v();}

	/**
	 * 2016-07-19
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Wait period for an ATM payment»
	 * @return int
	 */
	public function waitPeriodATM() {return dfc($this, function($f) {
		/** @var int $result */
		$result = $this->nat($f);
		if (WaitPeriodType::WORKING_DAYS === $this->waitPeriodType()) {
			$result = df_num_calendar_days_by_num_working_days(ZD::now(), $result, $this->scope());
		}
		return $result;
	}, [__FUNCTION__]);}

	/**
	 * 2016-07-17
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay» → «Wait Period Type»
	 * @return string
	 */
	public function waitPeriodType() {return $this->v();}
}