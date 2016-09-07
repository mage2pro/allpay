<?php
namespace Dfe\AllPay;
class TWD {
	/**
	 * 2016-08-15
	 * @param float $amount
	 * @param string|null $currencyCode [optional]
	 * @return float
	 */
	public static function from($amount, $currencyCode = null) {return
		round(df_currency_convert($amount, $currencyCode, 'TWD'))
	;}

	/**
	 * 2016-08-15
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::amount()
	 * @used-by \Dfe\AllPay\Total\Quote::collect()
	 * @param float $amount
	 * @param string $currencyCode
	 * @return float|int
	 */
	public static function round($amount, $currencyCode) {return
		'TWD' === $currencyCode ? round($amount) : $amount
	;}
}