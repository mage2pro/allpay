<?php
# 2016-06-29
namespace Dfe\AllPay;
use Df\Config\Source\WaitPeriodType;
use Df\Payment\Settings\Options as O;
use Dfe\AllPay\InstallmentSales\Settings as SettingsI;
use Dfe\AllPay\Source\Option as OptionSource;
/** @method static Settings s() */
final class Settings extends \Df\Payment\Settings {
	/**
	 * 2016-07-01 «Description on a kiosk's screen»
	 */
	function descriptionOnKiosk():string {return $this->v();}

	/**
	 * 2016-06-29 «ALL IN ONE 介接 HashIV»
	 * Note that we encrypt the live keys, but do not encrypt the test keys.
	 */
	function hashIV():string {return $this->testablePV();}

	/**
	 * 2016-06-29 «LL IN ONE 介接 HashKey»
	 * Note that we encrypt the live keys, but do not encrypt the test keys.
	 */
	function hashKey():string {return $this->testablePV();}

	/**
	 * 2016-07-31
	 * @used-by \Dfe\AllPay\ConfigProvider::config()
	 * @used-by \Dfe\AllPay\Method::plan()
	 * @used-by \Dfe\AllPay\Total\Quote::collect()
	 */
	function installmentSales():SettingsI {return $this->child(SettingsI::class);}

	/**
	 * 2017-03-03
	 * @used-by \Dfe\AllPay\ConfigProvider::config()
	 * @return O
	 */
	function options() {return $this->_options(OptionSource::class);}

	/**
	 * 2016-07-19 «Wait period for an ATM payment»
	 * @used-by \Dfe\AllPay\Charge::pCharge()
	 * @return int
	 */
	function waitPeriodATM() {return WaitPeriodType::calculate($this);}
}