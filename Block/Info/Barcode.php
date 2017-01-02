<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\Webhook\Offline as R;
// 2016-07-25
class Barcode extends Offline {
	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info\Offline::paymentId()
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @param R $f
	 * @return string
	 * @see nl2br() для результата вызывать не надо,
	 * потому что ядро вызовет эту функцию автоматически.
	 */
	protected function paymentId(R $f) {return df_cc_n($f->req(['Barcode1', 'Barcode2', 'Barcode3']));}

	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info\Offline::paymentIdLabel()
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @return string
	 */
	protected function paymentIdLabel() {return 'Barcode';}
}