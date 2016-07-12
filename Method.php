<?php
namespace Dfe\AllPay;
use Df\Payment\PlaceOrder;
use Dfe\AllPay\Settings as S;
use Exception as E;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException as LE;
use Magento\Payment\Model\Info as I;
use Magento\Payment\Model\InfoInterface as II;
use Magento\Sales\Api\Data\TransactionInterface;
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
		df_payment_apply_custom_transaction_id($payment);
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
		/** @var array(string => mixed) $params */
		$params = Charge::request($this->ii());
		/** @var string $uri */
		$uri =
			'https://payment'
			. (S::s()->test() ? '-stage' : '')
			. '.allpay.com.tw/Cashier/AioCheckOut/V2'
		;
		/**
		 * 2016-07-01
		 * К сожалению, если передавать в качестве результата ассоциативный массив,
		 * то его ключи почему-то теряются.
		 * Поэтому запаковываем массив в JSON.
		 */
		$this->iiaSet(PlaceOrder::REQUEST, df_json_encode([
			PlaceOrder::REQUEST_PARAMS => $params, PlaceOrder::REQUEST_URI => $uri
		]));
		/**
		 * 2016-05-06
		 * Письмо-оповещение о заказе здесь ещё не должно отправляться.
		 * «How is a confirmation email sent on an order placement?» https://mage2.pro/t/1542
		 */
		$this->o()->setCanSendNewEmailFlag(false);
		/**
		 * 2016-07-10
		 * Сохраняем информацию о транзакции.
		 */
		$this->saveRequest($params['MerchantTradeNo'], $uri, $params);
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