<?php
namespace Dfe\AllPay;
final class TWD {
	/**
	 * 2016-08-15
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::amount()
	 * @used-by \Dfe\AllPay\Total\Quote::collect()
	 * @param float $a
	 * @param string $cCode
	 * @return float|int
	 */
	static function round($a, $cCode) {return 'TWD' === $cCode ? round($a) : $a;}
}