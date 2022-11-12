<?php
namespace Dfe\AllPay;
use Df\Payment\ConfigProvider\IOptions;
/**
 * 2016-08-04
 * @used-by https://github.com/mage2pro/allpay/blob/1.1.33/etc/frontend/di.xml?ts=4#L9
 * @method Settings s()
 * 2022-11-07 @noinspection PhpSuperClassIncompatibleWithInterfaceInspection It is a false positive.
 */
final class ConfigProvider extends \Df\Payment\ConfigProvider  implements IOptions {
	/**
	 * 2017-09-19
	 * We always need the allowed options on the frontend.
	 * If an option is intended to be chosen on the PSP side,
	 * then we just enumerate the allowed options on the Magento side.
	 * @override
	 * @see \Df\Payment\ConfigProvider\IOptions::options()
	 * @used-by \Df\Payment\ConfigProvider::configOptions()
	 * @return array(<value> => <label>)
	 */
	function options():array {return $this->s()->options()->o(true);}

	/**
	 * 2016-08-04
	 * @override
	 * @see \Df\Payment\ConfigProvider::config()
	 * @used-by \Df\Payment\ConfigProvider::getConfig()
	 * @return array(string => mixed)
	 */
	protected function config():array {return [
		'currencyRateFromBaseToCurrent' => df_currency_rate_to_current()
		,'installment' => ['plans' => $this->s()->installmentSales()->plans()->get()]
	] + self::configOptions($this) + parent::config();}
}