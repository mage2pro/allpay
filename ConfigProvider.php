<?php
namespace Dfe\AllPay;
use Df\PaypalClone\Source\OptionsLocation as OL;
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
		// 2017-03-05
		// @used-by Df_Payments/withOptions::options()
		// https://github.com/mage2pro/core/blob/2.0.36/Payment/view/frontend/web/withOptions.js?ts=4#L55
		,'options' => $this->s()->options()->o()
		,'needShowOptions' => OL::MAGENTO === $this->s()->optionsLocation()
	] + parent::config();}
}