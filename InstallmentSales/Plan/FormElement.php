<?php
namespace Dfe\AllPay\InstallmentSales\Plan;
use Df\Framework\Form\Element\Fieldset;
use Dfe\AllPay\InstallmentSales\Plan\Entity as O;
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
	function onFormInitialized() {
		parent::onFormInitialized();
		// 2016-07-30
		// Этот стиль будет применён к элементу <fieldset>.
		$this->addClass('dfe-allpay-installment-plan');
		// 2016-08-10
		// Сегодня опытным путём выяснил, что allPay иное количество платежей не допускает.
		$this->select2Number(O::numPayments, 'Payments', [3, 6, 12, 18, 24]);
		$this->percent(O::rate, 'Interest Rate');
		$this->money(O::fee, 'Fixed Monthly Fee');
		df_fe_init($this, __CLASS__, [], [], 'plan');
	}
}