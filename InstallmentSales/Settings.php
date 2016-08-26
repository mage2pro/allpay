<?php
namespace Dfe\AllPay\InstallmentSales;
use Df\Config\A;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
/** @method static Settings s() */
final class Settings extends \Df\Core\Settings {
	/**
	 * 2016-07-31
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay»→ «Installment Sales» → «Plans»
	 * @param int|null $numPayments [optional]
	 * @return A|Plan|null
	 */
	public function plans($numPayments = null) {
		/** @var A $result */
		$result = $this->_a(Plan::class);
		return is_null($numPayments) ? $result : $result->get(intval($numPayments));
	}

	/**
	 * 2016-07-31
	 * @override
	 * @see \Df\Core\Settings::prefix()
	 * @used-by \Df\Core\Settings::v()
	 * @return string
	 */
	protected function prefix() {return 'df_payment/all_pay/installment_sales/';}
}


