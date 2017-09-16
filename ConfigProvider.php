<?php
namespace Dfe\AllPay;
use Df\Payment\Settings\Options as O;
// 2016-08-04
// @used-by https://github.com/mage2pro/allpay/blob/1.1.33/etc/frontend/di.xml?ts=4#L9
final class ConfigProvider extends \Df\Payment\ConfigProvider {
	/**
	 * 2016-08-04
	 * @override
	 * @see \Df\Payment\ConfigProvider::config()
	 * @used-by \Df\Payment\ConfigProvider::getConfig()
	 * @return array(string => mixed)
	 */
	protected function config() {
		/** @var Settings $s */ $s = $this->s();
		$o = $s->options();	/** @var O $o */
		return [
		'currencyRateFromBaseToCurrent' => df_currency_rate_to_current()
		,'installment' => ['plans' => $s->installmentSales()->plans()->a()]
	   /**
		* 2017-03-05
		* @used-by Df_Payments/withOptions::options()
		* https://github.com/mage2pro/core/blob/2.0.36/Payment/view/frontend/web/withOptions.js?ts=4#L55
		* 2017-09-17
		* We always need the allowed options on the frontend.
		* If an option is intended to be chosen on the PSP side,
		* then we just enumerate the allowed options on the Magento side.
		*/
		,'options' => $o->o(true)
		,'needShowOptions' => $o->needShow()
	] + parent::config();}
}