<?php
namespace Dfe\AllPay;
final class TWD {
	/**
	 * 2016-08-15
	 * @param float $amount
	 * @param string|null $cCode [optional]
	 * @return float
	 */
	static function from($amount, $cCode = null) {return round(df_currency_convert(
		$amount, $cCode, 'TWD'
	));}

	/**
	 * 2016-08-15
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::amount()
	 * @used-by \Dfe\AllPay\Total\Quote::collect()
	 * @param float $amount
	 * @param string $cCode
	 * @return float|int
	 */
	static function round($amount, $cCode) {return 'TWD' === $cCode ? round($amount) : $amount;}
}