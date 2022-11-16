<?php
namespace Dfe\AllPay;
final class TWD {
	/**
	 * 2016-08-15
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::amount()
	 * @used-by \Dfe\AllPay\Total\Quote::collect()
	 * @return float|int
	 */
	static function round(float $a, string $cCode) {return 'TWD' === $cCode ? round($a) : $a;}
}