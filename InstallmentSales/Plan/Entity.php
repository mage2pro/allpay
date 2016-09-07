<?php
namespace Dfe\AllPay\InstallmentSales\Plan;
use Dfe\AllPay\TWD;
use Df\Core\Exception as DFE;
class Entity extends \Df\Config\ArrayItem {
	/**
	 * 2016-08-13
	 * @used-by \Dfe\AllPay\Total\Quote::collect()
	 * @param float $amount
	 * $amount вполне может быть равно 0,
	 * потому что метод @used-by \Dfe\AllPay\Total\Quote::collect() вызывается отдельно
	 * для адресов shipping и billing, и для адреса billing, как правило, totals равны 0:
	 * смотрите комментарий к методу @used-by \Dfe\AllPay\Total\Quote::collect()
	 * Если же $amount равно 0, то мы не можем использовать наш обычный алгоритм,
	 * потому что он добавит к 0 фиксированную наценку,
	 * а нам же надо просто вернуть 0.
	 * @param string $currencyCode
	 * @return float|int
	 */
	public function amount($amount, $currencyCode) {return
		!$amount ? 0 : TWD::round(
			$amount * (1 + $this->rate() / 100) + $this->fee($currencyCode) * $this->numPayments()
			,$currencyCode
		)
	;}

	/**
	 * 2016-07-31
	 * @override
	 * @see \Df\Config\ArrayItem::getId()
	 * @used-by \Df\Config\A::get()
	 * @return int
	 */
	public function getId() {return $this->numPayments();}

	/**
	 * 2016-08-08
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::getId()
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::sortWeight()
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::validate()
	 * @used-by \Dfe\AllPay\Charge::_requestI()
	 * @return int
	 */
	public function numPayments() {return $this->nat();}

	/**
	 * 2016-08-07
	 * @override
	 * @see \Df\Config\ArrayItem::sortWeight()
	 * @used-by \Df\Config\Backend\ArrayT::processI()
	 * @return int
	 */
	public function sortWeight() {return $this->numPayments();}

	/**
	 * 2016-08-02
	 * @override
	 * @see \Df\Config\O::validate()
	 * @used-by \Df\Config\Backend\Serialized::validate()
	 * @return void
	 * @throws DFE
	 */
	public function validate() {df_assert($this->numPayments());}

	/**
	 * 2016-08-08
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::amountTWD()
	 * @param string|null $currencyCode [optional]
	 * @return float
	 */
	private function fee($currencyCode = null) {
		/** @var float $result */
		$result = $this->f();
		return !$currencyCode ? $result : df_currency_convert($result, null, $currencyCode);
	}

	/**
	 * 2016-08-08
	 * @used-by \Dfe\AllPay\InstallmentSales\Plan\Entity::amountTWD()
	 * @return float
	 */
	private function rate() {return $this->f();}

	const fee = 'fee';
	const numPayments = 'numPayments';
	const rate = 'rate';
}