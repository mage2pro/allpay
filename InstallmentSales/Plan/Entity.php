<?php
namespace Dfe\AllPay\InstallmentSales\Plan;
use Df\Core\Exception as DFE;
class Entity extends \Df\Config\ArrayItem {
	/**
	 * 2016-07-31
	 * @override
	 * @see \Df\Config\ArrayItem::getId()
	 * @used-by \Df\Config\A::get()
	 * @return int
	 */
	public function getId() {return $this['count'];}

	/**
	 * 2016-08-02
	 * @override
	 * @see \Df\Config\O::validate()
	 * @used-by \Df\Config\Backend\Serialized::validate()
	 * @return void
	 * @throws DFE
	 */
	public function validate() {df_assert(!is_array($this['count']));}
}