<?php
namespace Dfe\AllPay\InstallmentSales;
use Df\Config\A;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Magento\Framework\App\ScopeInterface as S;
/** @method static Settings s() */
class Settings extends \Df\Core\Settings {
	/**
	 * 2016-07-31
	 * «Mage2.PRO» → «Payment» → «歐付寶 allPay»→ «Installment Sales» → «Plans»
	 * @param int|null $paymentsCount [optional]
	 * @param null|string|int|S $s [optional]
	 * @return A|Plan|null
	 */
	public function plans($paymentsCount = null, $s = null) {
		/** @var A $result */
		$result = $this->_a(__FUNCTION__, Plan::class, $s);
		return is_null($paymentsCount) ? $result : $result->get($paymentsCount);
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


