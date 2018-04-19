<?php
namespace Dfe\AllPay\InstallmentSales\Plan;
use Dfe\AllPay\InstallmentSales\Plan\Entity as O;
/**
 * 2016-07-30
 * @final Unable to use the PHP «final» keyword here because of the M2 code generation. 
 * This class is not a singleton:
 * @see \Magento\Framework\Data\Form\AbstractForm::addField():
 * 		$element = $this->_factoryElement->create($type, ['data' => $config]);
 * https://github.com/magento/magento2/blob/2.2.0-RC1.8/lib/internal/Magento/Framework/Data/Form/AbstractForm.php#L137-L159
 */
class FE extends \Df\Framework\Form\Element\Fieldset {
	/**
	 * 2016-07-30
	 * @override
	 * @see \Df\Framework\Form\Element\Fieldset::onFormInitialized()
	 * @used-by \Df\Framework\Plugin\Data\Form\Element\AbstractElement::afterSetForm()
	 */
	final function onFormInitialized() {
		parent::onFormInitialized();
		// 2016-07-30 This CSS class will be applied to the <fieldset> DOM node.
		$this->addClass('dfe-allpay-installment-plan');
		// 2016-08-10
		// Today I have found by an experiment,
		// that allPay does not allow the installment options out of the range below.
		$this->select2Number(O::numPayments, 'Payments', [3, 6, 12, 18, 24]);
		$this->percent(O::rate, 'Interest Rate');
		$this->money(O::fee, 'Fixed Monthly Fee');
		df_fe_init($this, __CLASS__, [], [], 'plan');
	}
}