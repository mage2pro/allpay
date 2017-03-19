<?php
namespace Dfe\AllPay\W\Nav;
use Dfe\AllPay\W\Event;
// 2017-03-15
/** @method Event e() */
final class Offline extends \Df\PaypalClone\W\Nav {
	/**
	 * 2016-07-20
	 * 2017-01-04
	 * Своим поведением этот метод напоминает мне @see \Df\StripeClone\Method::e2i()
	 * @override
	 * @see \Df\Payment\W\Handler::id()
	 * @used-by \Df\Payment\W\Handler::ii()
	 * @return string
	 */
	protected function id() {return parent::id() . ($this->e()->needCapture() ? '-capture' : '-info');}
}