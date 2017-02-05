<?php
namespace Dfe\AllPay;
/** @method Settings s() */
final class ConfigProvider extends \Df\Payment\ConfigProvider {
	/**
	 * 2016-08-04
	 * @override
	 * @see \Df\Payment\ConfigProvider::config()
	 * @used-by \Df\Payment\ConfigProvider::getConfig()
	 * @return array(string => mixed)
	 */
	protected function config() {return [
		'currencyRateFromBaseToCurrent' => df_currency_rate_to_current()
		,'installment' => ['plans' => $this->s()->installmentSales()->plans()->a()]
		,'options' => $this->s()->options()
		,'optionsLocation' => $this->s()->optionsLocation()
	] + parent::config();}
}