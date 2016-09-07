<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\Response\Offline as R;
// 2016-07-25
class CVS extends Offline {
	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info\Offline::paymentId()
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @param R $f
	 * @return string
	 */
	protected function paymentId(R $f) {return $f['PaymentNo'];}

	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info\Offline::paymentIdLabel()
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @return string
	 */
	protected function paymentIdLabel() {return 'Payment Number';}
}