<?php
namespace Dfe\AllPay\W\Nav;
use Dfe\AllPay\W\Event as Ev;
// 2017-03-15
/** @method Ev e() */
final class Offline extends \Df\PaypalClone\W\Nav {
	/**
	 * 2016-07-20
	 * 2017-01-04 It is implemented similar to @see \Df\StripeClone\Method::e2i()
	 * @override
	 * @see \Df\PaypalClone\W\Nav::type()
	 * @used-by \Df\PaypalClone\W\Nav::id()
	 * @return string
	 */
	protected function type() {return $this->e()->needChangePaymentState() ? Ev::T_CAPTURE : Ev::T_INFO;}
}