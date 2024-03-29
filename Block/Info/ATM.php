<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\W\Event\Offline as Event;
# 2016-07-25
/** @final Unable to use the PHP «final» keyword here because of the M2 code generation. */
class ATM extends Offline {
	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info\Offline::paymentId()
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 */
	final protected function paymentId(Event $f):string {return $f->r('vAccount');}

	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info\Offline::paymentIdLabel()
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 */
	final protected function paymentIdLabel():string {return 'Account Number';}
}