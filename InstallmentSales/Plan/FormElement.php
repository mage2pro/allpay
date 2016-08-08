<?php
namespace Dfe\AllPay\InstallmentSales\Plan;
use Df\Framework\Form\Element\Fieldset;
use Df\Framework\Form\Element\Number;
/**
 * 2015-12-27
 * Этот класс не является одиночкой:
 * https://github.com/magento/magento2/blob/2.0.0/lib/internal/Magento/Framework/Data/Form/AbstractForm.php#L155
 */
class FormElement extends Fieldset {
	/**
	 * 2016-07-30
	 * @override
	 * @see \Df\Framework\Form\Element\Fieldset::onFormInitialized()
	 * @used-by \Df\Framework\Plugin\Data\Form\Element\AbstractElement::afterSetForm()
	 * @return void
	 */
	public function onFormInitialized() {
		parent::onFormInitialized();
		// 2016-07-30
		// Этот стиль будет применён к элементу <fieldset>.
		$this->addClass('dfe-allpay-installment-plan');
		$this->number('months', 'Number of Months');
		$this->percent('rate', 'Interest Rate');
		$this->money('fee', 'Fixed Monthly Fee');
		df_fe_init($this, __CLASS__, [], [], 'plan');
	}
}