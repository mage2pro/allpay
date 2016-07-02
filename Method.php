<?php
namespace Dfe\AllPay;
use Df\Payment\PlaceOrder;
use Dfe\AllPay\Settings as S;
use Exception as E;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException as LE;
use Magento\Payment\Model\Info as I;
use Magento\Payment\Model\InfoInterface as II;
use Magento\Sales\Model\Order as O;
use Magento\Sales\Model\Order\Address as OrderAddress;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Payment as OP;
use Magento\Sales\Model\Order\Payment\Transaction;
class Method extends \Df\Payment\Method {
	/**
	 * 2016-06-29
	 * @override
	 * @see \Df\Payment\Method::capture()
	 *
	 * $amount содержит значение в учётной валюте системы.
	 * https://github.com/magento/magento2/blob/6ce74b2/app/code/Magento/Sales/Model/Order/Payment/Operations/CaptureOperation.php#L37-L37
	 * https://github.com/magento/magento2/blob/6ce74b2/app/code/Magento/Sales/Model/Order/Payment/Operations/CaptureOperation.php#L76-L82
	 *
	 * @param II|I|OP $payment
	 * @param float $amount
	 * @return $this
	 * @throws E|LE
	 */
	public function capture(II $payment, $amount) {
		return $this;
	}

	/**
	 * @override
	 * @see \Df\Payment\Method::getConfigPaymentAction()
	 * @return string
	 *
	 * 2016-05-07
	 * Сюда мы попадаем только из метода @used-by \Magento\Sales\Model\Order\Payment::place()
	 * причём там наш метод вызывается сразу из двух мест и по-разному.
	 *
	 * 2016-06-29
	 * Умышленно возвращаем null.
	 * @used-by \Magento\Sales\Model\Order\Payment::place()
	 * https://github.com/magento/magento2/blob/ffea3cd/app/code/Magento/Sales/Model/Order/Payment.php#L334-L355
	 */
	public function getConfigPaymentAction() {
		/** @var Settings $s */
		$s = S::s();
		/**
		 * 2016-07-01
		 * К сожалению, если передавать в качестве результата ассоциативный массив,
		 * то его ключи почему-то теряются.
		 * Поэтому запаковываем масств в JSON.
		 */
		$this->iiaSet(PlaceOrder::RESPONSE, df_json_encode([
			// 2016-07-02
			// «Merchant Identification number (provided by allPay)».
			// Must be filled.
			'MerchantID' => $s->merchantID()
			// 2016-07-02
			// «Merchant trade number».
			// «Merchant trade number could not be repeated.
			// It is composed with upper and lower cases of English letter and numbers.»
			// Must be filled.
			,'MerchantTradeNo' => $this->o()->getIncrementId()
			// 2016-07-02
			// «Merchant trade date».
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
		]));
		/**
		 * 2016-05-06
		 * Письмо-оповещение о заказе здесь ещё не должно отправляться.
		 * «How is a confirmation email sent on an order placement?» https://mage2.pro/t/1542
		 */
		$this->o()->setCanSendNewEmailFlag(false);
		return null;
	}

	/**
	 * 2016-06-29
	 * @override
	 * @see \Df\Payment\Method::getInfoBlockType()
	 * @return string
	 */
	public function getInfoBlockType() {return \Magento\Payment\Block\Info\Cc::class;}

	/**
	 * 2016-06-29
	 * @override
	 * @see \Df\Payment\Method::setStore()
	 * @param int $storeId
	 * @return void
	 */
	public function setStore($storeId) {
		parent::setStore($storeId);
		S::s()->setScope($storeId);
	}

	/**
	 * 2016-06-29
	 * @used-by Dfe/AllPay/etc/frontend/di.xml
	 * @used-by \Dfe\AllPay\ConfigProvider::getConfig()
	 */
	const CODE = 'dfe_all_pay';
}