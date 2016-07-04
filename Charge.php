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
	private function _request() {
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
			// Varchar(20)
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
			// Varchar(200)
			// Must be filled.
			,'TradeDesc' => ''
			// 2016-07-02
			// «Item Name».
			// Varchar(200)
			// «If there are more than one item name
			// and would like to show cash flow selection page line by line,
			// separate the item name with symbol #.».
			// Must be filled.
			,'ItemName' => ''
			// 2016-07-02
			// «Return URL for payment complete notification».
			// Varchar(200)
			// «When a customer made a payment,
			// payment result would be sent by server back end and return to this URL.».
			// Must be filled.
			,'ReturnURL' => ''
			// 2016-07-02
			// «Select default payment type».
			// Varchar(20)
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
			// Varchar
			// Must be filled.
			,'CheckMacValue' => ''
			// 2016-07-02
			// «URL for returning pages from Client to merchant».
			// Varchar(200)
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
			// Varchar(200)
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
			// Varchar(100)
			// «Leave it as blank for now.».
			// Could be empty.
			,'Remark' => ''
			// 2016-07-02
			// «Select the default setup for sub payment».
			// Varchar(20)
			// «M».
			// Could be empty.
			,'ChooseSubPayment' => ''
			// 2016-07-04
			// «Payment result URL returned by Client end».
			// Varchar(200)
			// «After a payment is made,
			// and then allPay would redirectly webpage again to this
			// URL with payment result parameter.
			// If this parameter is left as blank, it would show payment complete on allPay webpage.
			// If one would show payment complete webpage on his own site,
			// set up the URL in this parameter.
			// (Some of the webATM banks would stay at their own webpages
			// after a trade is made successfully.
			// It would not redirect webpages to allPay;
			// thus, allPay would not redirect webpages to the URL this parameter set up.)
			// If this parameter is set up, ClientBackURL parameter would be disable.».
			// Could be empty.
			//
			// [allPay] What is the difference
			// between the «OrderResultURL» and «ClientBackURL» parameters? https://mage2.pro/t/1836/2
			,'OrderResultURL' => ''
			// 2016-07-04
			// «If there is a need for an extra payment information».
			// Varchar(1)
			// «Set up payment complete notification,
			// return information of order query,
			// and decide if there is a for an extra payment information
			// (for return information, please refer to Additional Return Parameter).
			// Default as N, not reply extra information.
			// When the parameter is Y, then reply with extra information.».
			// Could be empty.
			,'NeedExtraPaidInfo' => 'N'
			// 2016-07-04
			// «Device Source».
			// Varchar(10)
			// «This parameter would set different layout of payment type selection webpage
			// according to the value it takes.».
			// Could be empty.
			//
			// [allPay] What are the possible values for the «DeviceSource» parameter?
			//  https://mage2.pro/t/1825
			,'DeviceSource' => 'P'
			// 2016-07-04
			// «Ignore payment type».
			// Varchar(100)
			// «When using ALL as ChoosePayment, user could select not to show his payment type.
			// If there are more than one payment type, separate them by symbol #.».
			// An example: ATM#WebATM
			// Could be empty.
			,'IgnorePayment' => ''
			// 2016-07-04
			// «Merchant platform identification number(provided by allPay)».
			// Varchar(10)
			// «This parameter is for project based merchants.
			// The others should leave this as blank.
			// If it is working with a project based merchant,
			// use the MerchantID which seller has appointed with.
			// If there are values in both AllPayID and AccountID, PlatformID could not be left as blank.».
			// Could be empty.
			,'PlatformID' => ''
			// 2016-07-04
			// «Electronic invoice remark».
			// Varchar(1)
			// «This parameter would help generating an invoice after payment is made.
			// If would like to generated an invoice, set Y as its value.».
			// Could be empty.
			,'InvoiceMark' => ''
			// 2016-07-04
			// «Whether or not to hold the allocation».
			// Int
			// «Whether or not to hold the allocation.
			// If no, take 0 (default value) as its value.
			// If yes, take 1 as its value.
			// Meaning of values listed below:
			// 		0:	allPay according to the contract has allocated the payment to merchant
			// 			after buyer made his payment (this is set as default value).
			// 		1:	after buyer made his payment
			// 			it needs to call “Merchant Allocation/Refund Request” API
			// 			so that allPay could make the payment to merchant.
			// 			If merchant does not request for allocation,
			// 			this order would be kept in allPay until merchant apply for its allocation.
			// This is not suitable for paying by “Credit Card” and “Tenpay.”».
			// Could be empty.
			,'HoldTradeAMT' => 0
			// 2016-07-04
			// «CheckMacValue encryption type».
			// Int
			// 		0:	MD5 (default setting)
			//		1:	SHA256
			// Could be empty.
			,'EncryptType' => 0
			// 2016-07-04
			// «Effective payment period».
			// Int
			// «At most 60 days; at least 1 day.
			// Defaulted as 3 days if this is left as blank.».
			// Could be empty.
			,'ExpireDate' => 3
			// 2016-07-04
			// «Payment related information returned by Server end».
			// Varchar(200)
			// «allPay would return the payment related information webpage
			// as a Server end to merchant after an order is generated (not after a payment is made).
			// It includes not only bank code, virtual account, and expiration date (yyyy/MM/dd).
			// It would also show related payment information on allPay.».
			// Could be empty.
			,'PaymentInfoURL' => ''
			// 2016-07-04
			// «Payment related information returned by Client end».
			// Varchar(200)
			// «allPay would return the payment related information webpage
			// as a Client end to merchant after an order is generated (not after a payment is made).
			// It would include the bank code, virtual account, and expiration date (yyyy/MM/dd).
			// If this value is left as empty, it would show the order generated page in allPay webpage.
			// If would like to show this page in your site, please set up the URL.
			// If this parameter is set up, ClientBackURL parameter would be disable.».
			// Could be empty.
			,'ClientRedirectURL' => ''
		];
		return $result;
	}

	/**
	 * 2016-07-04
	 * @param II|I|OP $payment
	 * @return array(string => mixed)
	 */
	public static function request(II $payment) {
		return (new self([self::$P__PAYMENT => $payment]))->_request();
	}
}