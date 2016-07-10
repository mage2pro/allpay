<?php
namespace Dfe\AllPay;
/**
 * 2016-07-09
 * The response is documented in the Chapter 7 «Payment Result Notification»
 * on the pages 32-35 of the allPay documentation.
 */
class Response extends \Df\Payment\R\Response {
	/**
	 * 2016-07-10
	 * @see \Df\Payment\R\Response::externalIdKey()
	 * @used-by \Df\Payment\R\Response::externalId()
	 * @return string
	 */
	protected function externalIdKey() {return 'TradeNo';}

	/**
	 * 2016-07-09
	 * @override
	 * @see \Df\Payment\R\Response::isSuccessful()
	 * @used-by \Df\Payment\R\Response::validate()
	 * @return bool
	 * «Value 1 means a payment is paid successfully. The other means failure.»
	 */
	protected function isSuccessful() {return 1 === intval($this['RtnCode']);}

	/**
	 * 2016-07-09
	 * @override
	 * @see \Df\Payment\R\Response::messageKey()
	 * @used-by \Df\Payment\R\Response::message()
	 * @return string
	 * «Trade message»
	 */
	protected function messageKey() {return 'RtnMsg';}

	/**
	 * 2016-07-09
	 * @override
	 * @see \Df\Payment\R\Response::requestIdKey()
	 * @used-by \Df\Payment\R\Response::requestId()
	 * @return string
	 * «Merchant trade number»
	 * Varchar(20)
	 * «When order is generated, it would send allPay coorperator
	 * a trade number with upper and lower cases of English letters and numbers.»
	 */
	protected function requestIdKey() {return 'MerchantTradeNo';}

	/**
	 * 2016-07-10
	 * @override
	 * @see \Df\Payment\R\Response::signatureKey()
	 * @used-by \Df\Payment\R\Response::signatureProvided()
	 * @return string
	 */
	protected function signatureKey() {return 'CheckMacValue';}
}


