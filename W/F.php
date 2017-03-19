<?php
namespace Dfe\AllPay\W;
use Dfe\AllPay\Source\Option;
// 2017-03-13
/** @method Reader r() */
final class F extends \Df\Payment\W\F {
	/**
	 * 2017-03-15
	 * 2017-03-16 Для WebATM используем базовый класс @see \Dfe\AllPay\W\Event
	 * @used-by \Df\Payment\W\F::c()
	 * @see \Dfe\AllPay\W\Event
	 * @see \Dfe\AllPay\W\Event\BankCard
	 * @see \Dfe\AllPay\W\Event\Offline
	 * @param string|null $t
	 * @return string
	 */
	protected function sufEvent($t) {return Option::WEB_ATM === $t ? '' : (
		$this->r()->isOffline() ? 'Offline' : $t
	);}

	/**
	 * 2017-03-16
	 * @used-by \Df\Payment\W\F::c()
	 * @see \Dfe\AllPay\W\Nav\Offline
	 * @param string|null $t
	 * @return string
	 */
	protected function sufNav($t) {return $this->r()->isOffline() ? 'Offline' : '';}
}