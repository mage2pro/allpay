<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\W\Event\Offline as Event;
# 2016-07-25
/** @final Unable to use the PHP «final» keyword here because of the M2 code generation. */
class Barcode extends Offline {
	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info\Offline::paymentId()
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @param Event $f
	 * @return string
	 * @see nl2br() для результата вызывать не надо, потому что ядро вызовет эту функцию автоматически.
	 */
	final protected function paymentId(Event $f) {return df_cc_n($f->r(['Barcode1', 'Barcode2', 'Barcode3']));}

	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info\Offline::paymentIdLabel()
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @return string
	 */
	final protected function paymentIdLabel() {return 'Barcode';}
}