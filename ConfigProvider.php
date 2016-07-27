<?php
namespace Dfe\AllPay;
use Dfe\AllPay\Settings as S;
use Magento\Checkout\Model\ConfigProviderInterface;
class ConfigProvider implements ConfigProviderInterface {
	/**
	 * 2016-07-01
	 * @override
	 * @see \Magento\Checkout\Model\ConfigProviderInterface::getConfig()
	 * https://github.com/magento/magento2/blob/cf7df72/app/code/Magento/Checkout/Model/ConfigProviderInterface.php#L15-L20
	 * @return array(string => mixed)
	 */
	public function getConfig() {
		return ['payment' => [Method::CODE => [
			'askForBillingAddress' => S::s()->askForBillingAddress()
			,'isActive' => S::s()->enable()
			,'isTest' => S::s()->test()
		]]];
	}
}