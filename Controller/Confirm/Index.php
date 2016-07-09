<?php
namespace Dfe\AllPay\Controller\Confirm;
use Df\Framework\Controller\Result\Text;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-07-04
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return Text
	 */
	public function execute() {
		/** @var Text $result */
		try {
			$this->log($_REQUEST);
			$result = Text::i('1|OK');
		}
		catch (\Exception $e) {
			$result = Text::i('0|' . df_le($e)->getMessage());
		}
		return $result;
	}

	/**
	 * 2016-07-06
	 * @param mixed $message
	 * @return void
	 */
	private function log($message) {if (!df_is_it_my_local_pc()) {df_log($message);}}

	/**
	 * 2016-07-09
	 * The response is documented in the Chapter 7 «Payment Result Notification»
	 * on the pages 32-35 of the allPay documentation.
	 * @return array(string => string|int)
	 */
	private function requestTest() {return [
		/**
		 * 2016-07-09
		 * «Verification code»
		 * Varchar
		 * «Merchants need to identify itself through verifying CheckMacValue.
		 * Please refer to Verification Code Mechanism in appendix.»
		 */
		'CheckMacValue' => 'C5BD7738CCD3DBAB3E0786E1B684DF23'
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
		,'MerchantTradeNo' => '9764195038'
		/**
		 * 2016-07-09
		 * This parameter is undocumented.
		 */
		,'PayAmt' => 3802
		/**
		 * 2016-07-09
		 * «Payment date»
		 * Varchar(20)
		 * «Formated as yyyy/MM/dd HH:mm:ss»
		 */
		,'PaymentDate' => '2016/07/09 20:31:40'
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
		,'PaymentTypeChargeFee' => 38
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
		,'TradeAmt' => 3802
		/**
		 * 2016-07-09
		 * «Date of order generated»
		 * Varchar(20)
		 * «Formated as yyyy/MM/dd HH:mm:ss»
		 */
		,'TradeDate' => '2016/07/09 20:30:50'
		/**
		 * 2016-07-09
		 * «allPay trade number»
		 * Varchar(20)
		 * «Please keep the connection between allPay trade number and MerchantTradeNo.»
		 */
		,'TradeNo' => '1607092030504442'
	];}
}


