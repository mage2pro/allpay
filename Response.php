<?php
namespace Dfe\AllPay;
/**
 * 2016-07-09
 * The response is documented in the Chapter 7 «Payment Result Notification»
 * on the pages 32-35 of the allPay documentation.
 */
class Response extends \Df\Payment\R\Response {
	/**
	 * 2016-07-09
	 * @override
	 * @see \Df\Payment\R\Response::isSuccessful()
	 * @return bool
	 */
	protected function isSuccessful() {
		/**
		 * 2016-07-09
		 * «Value 1 means a payment is paid successfully. The other means failure.»
		 */
		return 1 === intval($this['RtnCode']);
	}
	/**
	 * 2016-07-09
	 * @override
	 * @see \Df\Payment\R\Response::message()
	 * @return string
	 */
	protected function message() {}
	/**
	 * 2016-07-09
	 * @override
	 * @see \Df\Payment\R\Response::transactionType()
	 * @return string
	 */
	public function transactionType() {}
}


