<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\Webhook\Offline as R;
// 2016-07-25
/** @final */
class ATM extends Offline {
	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info\Offline::paymentId()
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @param R $f
	 * @return string
	 */
	protected function paymentId(R $f) {return $f->req('vAccount');}

	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info\Offline::paymentIdLabel()
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @return string
	 */
	protected function paymentIdLabel() {return 'Account Number';}
}