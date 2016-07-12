<?php
namespace Dfe\AllPay\Response;
use Dfe\AllPay\Method;
use Df\Sales\Model\Order\Payment as DfPayment;
use Magento\Payment\Model\Method\AbstractMethod as M;
use Magento\Sales\Api\Data\OrderPaymentInterface as IOP;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment as OP;
class BankCard extends \Dfe\AllPay\Response {
	/**
	 * 2016-07-12
	 * @override
	 * @see \Dfe\AllPay\Response::handleInternal()
	 * @used-by \Dfe\AllPay\Response::handle()
	 * @return void
	 */
	public function handleInternal() {
		/** @var IOP|OP $payment */
		$payment = $this->payment();
		/** @var Method $method */
		$method = $payment->getMethodInstance();
		$method->setStore($this->order()->getStoreId());
		DfPayment::processActionS($payment, M::ACTION_AUTHORIZE_CAPTURE, $this->order());
		DfPayment::updateOrderS(
			$payment
			, $this->order()
			, Order::STATE_PROCESSING
			, $this->order()->getConfig()->getStateDefaultStatus(Order::STATE_PROCESSING)
			, $isCustomerNotified = true
		);
	}

	/**
	 * 2016-07-12
	 * @override
	 * @see \Dfe\AllPay\Response::expectedRtnCode()
	 * @used-by \Dfe\AllPay\Response::isSuccessful()
	 * @return int
	 * «Value 1 means a payment is paid successfully. The other means failure.»
	 */
	protected function expectedRtnCode() {return 1;}

	/**
	 * 2016-07-12
	 * The response is documented in the Chapter 7 «Payment Result Notification»
	 * on the pages 32-35 of the allPay documentation.
	 * @override
	 * @see \Df\Payment\R\Response::testData()
	 * @return array(string => string)
	 */
	protected function testData() {return [
		/**
		 * 2016-07-09
		 * «Verification code»
		 * Varchar
		 * «Merchants need to identify itself through verifying CheckMacValue.
		 * Please refer to Verification Code Mechanism in appendix.»
		 */
		'CheckMacValue' => '219B8DE333411BC56985E22AF9666CB2'
		/**
		 * 2016-07-09
		 * «Merchant Identification number (provided by allPay)»
		 * Varchar(10)
		 */
		,'MerchantID' => '2000132'
		/**
		 * 2016-07-09
		 * «Merchant trade number»
		 * Varchar(20)
		 * «When order is generated, it would send allPay coorperator
		 * a trade number with upper and lower cases of English letters and numbers.»
		 */
		,'MerchantTradeNo' => '8276965766'
		/**
		 * 2016-07-09
		 * This parameter is undocumented.
		 */
		,'PayAmt' => 5410
		/**
		 * 2016-07-09
		 * «Payment date»
		 * Varchar(20)
		 * «Formated as yyyy/MM/dd HH:mm:ss»
		 */
		,'PaymentDate' => '2016/07/11 07:30:34'
		/**
		 * 2016-07-09
		 * «Payment type selected by member»
		 * Varchar(20)
		 * «Please refer to Table of Replying Payment Type»
		 */
		,'PaymentType' => 'Credit_CreditCard'
		/**
		 * 2016-07-09
		 * «Access fee»
		 * Money
		 */
		,'PaymentTypeChargeFee' => 54
		/**
		 * 2016-07-09
		 * This parameter is undocumented.
		 */
		,'RedeemAmt' => 0
		/**
		 * 2016-07-09
		 * «Trade status»
		 * Int
		 * «Value 1 means a payment is paid successfully. The other means failure.»
		 */
		,'RtnCode' => 1
		/**
		 * 2016-07-09
		 * «Trade message»
		 * Varchar(200)
		 */
		,'RtnMsg' => '交易成功'
		/**
		 * 2016-07-09
		 * «If it is simulated payment.»
		 * Int
		 * «Value 1 means simulated payment.
		 * Value 0 means not simulated payment.
		 * For more convenient API testing, seller can use back-end platform to simulate payment.
		 * When the value of SimulatePaid is 1, RtnCode would be 1 also.
		 * This means the order is using simulated payment
		 * instead of a customer makes an actualy payment.
		 * Hence, allPay will not allocate payment to merchant.
		 * Please do not release any items on this order to avoid any related losts.»
		 */
		,'SimulatePaid' => 0
		/**
		 * 2016-07-09
		 * «Trade amount»
		 * Money
		 * «If an allPay member select paying by installment through credit card,
		 * this trade amount would be returned to seller with InstallmentAmount
		 * when this order is generated.»
		 */
		,'TradeAmt' => 5410
		/**
		 * 2016-07-09
		 * «Date of order generated»
		 * Varchar(20)
		 * «Formated as yyyy/MM/dd HH:mm:ss»
		 */
		,'TradeDate' => '2016/07/11 07:30:04'
		/**
		 * 2016-07-09
		 * «allPay trade number»
		 * Varchar(20)
		 * «Please keep the connection between allPay trade number and MerchantTradeNo.»
		 */
		,'TradeNo' => '1607110730046559'
	];}
}

