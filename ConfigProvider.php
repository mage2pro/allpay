<?php
namespace Dfe\AllPay;
// 2016-08-04
// @used-by https://github.com/mage2pro/allpay/blob/1.1.33/etc/frontend/di.xml?ts=4#L9
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
		,'options' => $this->s()->options()->o()
		,'optionsLocation' => $this->s()->optionsLocation()
	] + parent::config();}
}