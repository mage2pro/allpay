<?php
namespace Dfe\AllPay\InstallmentSales\Plan;
class Entity extends \Df\Config\O {
	/**
	 * 2016-07-31
	 * @override
	 * @see \Df\Config\O::getId()
	 * @used-by \Df\Config\A::get()
	 * @return int
	 */
	public function getId() {return $this['count']['value'];}
}