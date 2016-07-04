<?php
namespace Dfe\AllPay;
use Dfe\AllPay\Settings as S;
use Magento\Payment\Model\Info as I;
use Magento\Payment\Model\InfoInterface as II;
use Magento\Sales\Model\Order\Payment as OP;
// 2016-07-04
class Charge extends \Df\Payment\Charge {
	/**
	 * 2016-07-04
	 * @return array(string => mixed)
	 */
	private function _requestParams() {
		/** @var Settings $s */
		$s = S::s();
		/** @var array(string => mixed) $result */
		$result = [
			// 2016-07-02
			// «Merchant Identification number (provided by allPay)».
			// Varchar(10)
			// Must be filled.
			'MerchantID' => $s->merchantID()
			// 2016-07-02
			// «Merchant trade number».
			// Varchar(20)
			// «Merchant trade number could not be repeated.
			// It is composed with upper and lower cases of English letter and numbers.»
			// Must be filled.
			,'MerchantTradeNo' => $this->o()->getIncrementId()
			// 2016-07-02
			// «Merchant trade date».
			// Varchar(20)
			// «Formatted as yyyy/MM/dd HH:mm:ss».
			// Example: 2012/03/21 15:40:18
			// Must be filled.
			,'MerchantTradeDate' => ''
			// 2016-07-02
			// «Payment type».
			// «Please use aio as its value».
			// Must be filled.
			,'PaymentType' => 'aio'
			// 2016-07-02
			// «Trade amount».
			// «Money».
			// Must be filled.
			,'TotalAmount' => ''
			// 2016-07-02
			// «Trade description».
			// «M».
			// Must be filled.
			,'TradeDesc' => ''
			// 2016-07-02
			// «Item Name».
			// «If there are more than one item name
			// and would like to show cash flow selection page line by line,
			// separate the item name with symbol #.».
			// Must be filled.
			,'ItemName' => ''
			// 2016-07-02
			// «Return URL for payment complete notification».
			// «When a customer made a payment,
			// payment result would be sent by server back end and return to this URL.».
			// Must be filled.
			,'ReturnURL' => ''
			// 2016-07-02
			// «Select default payment type».
			// «allPAy would provide follow payment types, please send it when generating an order:
			// 		Credit: Credit Card.
			// 		WebATM: webATM.
			// 		ATM: physical ATM machine.
			// 		CVS: CVS code.
			// 		BARCODE: BARCODE.
			// 		Tenpay: Tenpay.
			// 		TopUpUsed: consume with account balance.
			// 		ALL: no selected payment type.
			// allPay would show the page to select payment type.
			// Must be filled.
			,'ChoosePayment' => $s->defaultPaymentMethod()
			// 2016-07-02
			// «Verification Code».
			// Must be filled.
			,'CheckMacValue' => ''
			// 2016-07-02
			// «URL for returning pages from Client to merchant».
			// «allPay would show payment complete page.
			// That page would include “back to merchant” button.
			// When a member clicks this button, it would redirect webpage to URL it set up.
			// If this parameter is not set up,
			// allPay payment complete page would not show “back to merchant” button.
			// When redirect webpage, it would simply return the page
			// instead of redirecting payment result to this URL.».
			//
			// [allPay] What is the difference
			// between the «OrderResultURL» and «ClientBackURL» parameters? https://mage2.pro/t/1836/2
			//
			// «ClientBackURL has value and OrderResultURL is empty:
			// The browser will go to AllPay's complete(result) page after payment is complete.
			// There will be a "back to merchant" link on the page.
			// If clicked, the link will go to ClientBackURL you specified.
			//
			// OrderResultURL has value:
			// The browser will go to OrderResultURL instead after payment is complete.
			// So, I guess you should use the parameter
			// to go back magento's order complete page from AllPay.»
			// Could be empty.
			,'ClientBackURL' => ''
			// 2016-07-02
			// «Item URL».
			// [allPay] What is the «ItemURL» payment parameter for? https://mage2.pro/t/1819/2
			// «You can put product URLs in the parameter.
			// In case of multiple URLs, you can use + to concatenate them.
			// "www.allpay.com.tw+www.yahoo.com.tw" <- An example that provided by AllPay
			// BTW, please note the max length is 200.».
			//
			// https://mage2.pro/t/1819/3
			// «After further confirmation with AllPay,
			// so far the parameter is not really used in any scenario.».
			// Could be empty.
			,'ItemURL' => ''
			// 2016-07-02
			// «Remark».
			// «Leave it as blank for now.».
			// Could be empty.
			,'Remark' => ''
			// 2016-07-02
			// «Select the default setup for sub payment».
			// Varchar(20)
			// «M».
			// Could be empty.
			,'ChooseSubPayment' => ''
			// 2016-07-02
			// «M».
			// «M».
			,'STUB' => ''
			// 2016-07-02
			// «M».
			// «M».
			,'STUB' => ''
			// 2016-07-02
			// «M».
			// «M».
			,'STUB' => ''
			// 2016-07-02
			// «M».
			// «M».
			,'STUB' => ''
			// 2016-07-02
			// «M».
			// «M».
			,'STUB' => ''
		];
		return $result;
	}

	/**
	 * 2016-07-04
	 * @param II|I|OP $payment
	 * @return array(string => mixed)
	 */
	public static function request(II $payment) {
		return (new self([self::$P__PAYMENT => $payment]))->_requestParams();
	}
}