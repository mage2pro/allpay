<?php
namespace Dfe\AllPay\W;
use Dfe\AllPay\W\Event\BankCard as B;
use Dfe\AllPay\W\Event\Offline as O;
// 2017-03-13
/** @method Reader r() */
final class F extends \Df\Payment\W\F {
	/**
	 * 2017-03-15
	 * @used-by \Df\Payment\W\F::suf()
	 * @param string|null $t
	 * @return string
	 */
	protected function sufEvent($t) {return df_class_l($this->r()->isBankCard() ? B::class : O::class);}
}