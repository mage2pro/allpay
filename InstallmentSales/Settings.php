<?php
namespace Dfe\AllPay\InstallmentSales;
use Df\Config\A;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
# 2016-07-31
/** @method static Settings s() */
final class Settings extends \Df\Config\Settings {
	/**
	 * 2016-07-31 «Mage2.PRO» → «Payment» → «歐付寶 O'Pay (allPay)»→ «Installment Sales» → «Plans»
	 * @used-by \Dfe\AllPay\ConfigProvider::config()
	 * @used-by \Dfe\AllPay\Method::plan()
	 * @used-by \Dfe\AllPay\Total\Quote::collect()
	 * @param int|null $numPayments [optional]
	 * @return A|Plan|null
	 */
	function plans($numPayments = null) {
		$r = $this->_a(Plan::class); /** @var A $r */
		return is_null($numPayments) ? $r : $r->get(intval($numPayments));
	}

	/**
	 * 2016-07-31
	 * @override
	 * @see \Df\Config\Settings::prefix()
	 * @used-by \Df\Config\Settings::v()
	 */
	protected function prefix():string {return 'df_payment/all_pay/installment_sales';}
}