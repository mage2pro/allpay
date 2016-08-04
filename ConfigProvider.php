<?php
namespace Dfe\AllPay;
use Magento\Checkout\Model\ConfigProviderInterface;
use Dfe\AllPay\InstallmentSales\Settings as InstallmentSalesSettings;
class ConfigProvider implements ConfigProviderInterface {
	/**
	 * 2016-07-01
	 * @override
	 * @see \Magento\Checkout\Model\ConfigProviderInterface::getConfig()
	 * https://github.com/magento/magento2/blob/cf7df72/app/code/Magento/Checkout/Model/ConfigProviderInterface.php#L15-L20
	 * @return array(string => mixed)
	 */
	public function getConfig() {
		/** @var Settings $s */
		$s = Settings::s();
		/** @var InstallmentSalesSettings $i */
		$i = $s->installmentSales();
		return ['payment' => [Method::CODE => [
			'askForBillingAddress' => $s->askForBillingAddress()
			,'isActive' => $s->enable()
			,'isTest' => $s->test()
			,'installment' => ['plans' => $i->plans()->a()]
		]]];
	}
}