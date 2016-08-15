<?php
namespace Dfe\AllPay;
class TWD {
	/**
	 * 2016-08-15
	 * @param float $amount
	 * @return float
	 */
	public static function fromBase($amount) {return round(df_currency_convert($amount, null, 'TWD'));}

	/**
	 * 2016-08-15
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::amount()
	 * @used-by \Dfe\AllPay\Total\Quote::collect()
	 * @param float $amount
	 * @param string $currencyCode
	 * @return float|int
	 */
	public static function round($amount, $currencyCode) {
		return 'TWD' === $currencyCode ? round($amount) : $amount;
	}
}