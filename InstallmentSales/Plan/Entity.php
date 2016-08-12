<?php
namespace Dfe\AllPay\InstallmentSales\Plan;
use Dfe\AllPay\Charge;
use Df\Core\Exception as DFE;
class Entity extends \Df\Config\ArrayItem {
	/**
	 * 2016-08-08
	 * @used-by \Dfe\AllPay\Charge::_requestI()
	 * @param float $amountTWD
	 * @return float
	 */
	public function amountTWD($amountTWD) {return round(
		$amountTWD * (1 + $this->rate() / 100) + Charge::toTWD($this->fee()) * $this->numPayments()
	);}

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
	 * @return float
	 */
	private function fee() {return $this->f();}

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