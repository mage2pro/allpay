<?php
namespace Dfe\AllPay;
use Dfe\AllPay\InstallmentSales\Settings as InstallmentSalesSettings;
/** @method Settings s() */
class ConfigProvider extends \Df\Payment\ConfigProvider {
	/**
	 * 2016-08-04
	 * @override
	 * @see \Df\Payment\ConfigProvider::custom()
	 * @used-by \Df\Payment\ConfigProvider::getConfig()
	 * @return array(string => mixed)
	 */
	protected function custom() {
		/** @var InstallmentSalesSettings $i */
		$i = $this->s()->installmentSales();
		return [
			'askForBillingAddress' => $this->s()->askForBillingAddress()
			,'currencyRateFromBaseToTWD' => df_currency_base()->getRate('TWD')
			,'installment' => ['plans' => $i->plans()->a()]
		];
	}

	/**
	 * 2016-08-06
	 * @override
	 * @see \Df\Payment\ConfigProvider::route()
	 * @used-by \Df\Payment\ConfigProvider::getConfig()
	 * @return string
	 */
	protected function route() {return 'dfe-allpay';}
}