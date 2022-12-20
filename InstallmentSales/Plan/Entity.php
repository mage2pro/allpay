<?php
namespace Dfe\AllPay\InstallmentSales\Plan;
use Dfe\AllPay\TWD;
use Df\Core\Exception as DFE;
final class Entity extends \Df\Config\ArrayItem {
	/**
	 * 2016-08-13
	 * $a вполне может быть равно 0,
	 * потому что метод @used-by \Dfe\AllPay\Total\Quote::collect() вызывается отдельно для адресов shipping и billing,
	 * и для адреса billing, как правило, totals равны 0:
	 * смотрите комментарий к методу @used-by \Dfe\AllPay\Total\Quote::collect()
	 * Если же $a равно 0, то мы не можем использовать наш обычный алгоритм,
	 * потому что он добавит к 0 фиксированную наценку, а нам же надо просто вернуть 0.
	 * @used-by \Dfe\AllPay\Total\Quote::collect()
	 * @return float|int
	 */
	function amount(float $a, string $cCode) {return !$a ? 0 : TWD::round(
		$a * (1 + $this->rate() / 100) + $this->fee($cCode) * $this->numPayments()
		,$cCode
	);}

	/**
	 * 2016-07-31
	 * 2022-10-23
	 * (string) is the same as @see strval():
	 * https://www.php.net/manual/en/language.types.string.php#language.types.string.casting
	 * https://stackoverflow.com/a/7372007
	 * @override
	 * @see \Df\Config\ArrayItem::id()
	 * @used-by \Df\Config\A::get()
	 */
	function id():string {return (string)$this->numPayments();}

	/**
	 * 2016-08-08
	 * @used-by self::id()
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::getId()
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::sortWeight()
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::validate()
	 * @used-by \Dfe\AllPay\Charge::_requestI()
	 */
	function numPayments():int {return $this->nat();}

	/**
	 * 2016-08-07
	 * @override
	 * @see \Df\Config\ArrayItem::sortWeight()
	 * @used-by \Df\Config\Backend\ArrayT::processI()
	 */
	function sortWeight():int {return $this->numPayments();}

	/**
	 * 2016-08-02
	 * @override
	 * @see \Df\Config\O::validate()
	 * @used-by \Df\Config\Backend\Serialized::validate()
	 * @throws DFE
	 */
	function validate():void {df_assert($this->numPayments());}

	/**
	 * 2016-08-08
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::amountTWD()
	 */
	private function fee(string $currencyC = ''):float {
		$r = $this->f(); /** @var float $r */
		return !$currencyC ? $r : df_currency_convert($r, null, $currencyC);
	}

	/**
	 * 2016-08-08
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::amountTWD()
	 */
	private function rate():float {return $this->f();}

	/** 2018-04-19 @used-by \Dfe\AllPay\InstallmentSales\Plan\FE::onFormInitialized() */
	const fee = 'fee';
	/** 2018-04-19 @used-by \Dfe\AllPay\InstallmentSales\Plan\FE::onFormInitialized() */
	const numPayments = 'numPayments';
	/** 2018-04-19 @used-by \Dfe\AllPay\InstallmentSales\Plan\FE::onFormInitialized() */
	const rate = 'rate';
}