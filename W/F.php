<?php
namespace Dfe\AllPay\W;
use Dfe\AllPay\W\Event\Offline as O;
// 2017-03-13
/** @method Reader r() */
final class F extends \Df\Payment\W\F {
	/**
	 * 2017-03-13
	 * @override
	 * @see \Df\Payment\W\F::suf()
	 * @used-by \Df\Payment\W\F::c()
	 * @param string $a
	 * @param string|null $t
	 * @return string|string[]|null
	 */
	protected function suf($a, $t) {return
		self::$EVENT !== $a || $this->r()->isBankCard() ? parent::suf($a, $t) : df_class_l(O::class)
	;}
}