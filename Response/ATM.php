<?php
namespace Dfe\AllPay\Response;
use Magento\Sales\Model\Order;
class ATM extends \Dfe\AllPay\Response {
	/**
	 * 2016-07-12
	 * @override
	 * @see \Dfe\AllPay\Response::handleInternal()
	 * @used-by \Dfe\AllPay\Response::handle()
	 * @return void
	 */
	public function handleInternal() {}

	/**
	 * 2016-07-12
	 * @override
	 * @see \Dfe\AllPay\Response::expectedRtnCode()
	 * @used-by \Dfe\AllPay\Response::isSuccessful()
	 * @return int
	 * «Successfully gets the number for ATM when value is 2.»
	 */
	protected function expectedRtnCode() {return 2;}

	/**
	 * 2016-07-12
	 * The response is documented in the Chapter 7 «Payment Result Notification»
	 * on the pages 32-35 of the allPay documentation.
	 * @override
	 * @see \Df\Payment\R\Response::testData()
	 * @param bool $isSuccess
	 * @return array(string => string)
	 */
	protected function testData($isSuccess) {return [
		/**
		 * 2016-07-12
		 * «Bank code»
		 * Varchar(3)
		 */
		'BankCode' => '005'
		/**
		 * 2016-07-12
		 * «Verification code»
		 * Varchar
		 * «Merchants need to identify itself through verifying CheckMacValue.
		 * Please refer to Verification Code Mechanism in appendix.»
		 */
		,'CheckMacValue' => '51031E28B188636234FAF7F04B0DED0B'
		/**
		 * 2016-07-12
		 * «Verification code»
		 * Varchar(10)
		 * «Format as yyyy/MM/dd.»
		 */
		,'ExpireDate' => '2016/07/15'
		/**
		 * 2016-07-12
		 * «Merchant Identification number (provided by allPay)»
		 * Varchar(10)
		 */
		,'MerchantID' => '2000132'
		/**
		 * 2016-07-12
		 * «Merchant trade number»
		 * Varchar(20)
		 * «When order is generated, it would send allPay coorperator
		 * a trade number with upper and lower cases of English letters and numbers.»
		 */
		,'MerchantTradeNo' => 'b762519887'
		/**
		 * 2016-07-12
		 * This parameter is undocumented.
		 */
		,'PayAmt' => 4315
		/**
		 * 2016-07-12
		 * «Payment date»
		 * Varchar(20)
		 * «Formated as yyyy/MM/dd HH:mm:ss»
		 */
		,'PaymentDate' => '2016/07/11 07:30:34'
		/**
		 * 2016-07-12
		 * «Payment type selected by member»
		 * Varchar(20)
		 * «Please refer to Table of Replying Payment Type»
		 */
		,'PaymentType' => 'ATM_LAND'
		/**
		 * 2016-07-12
		 * This parameter is undocumented.
		 */
		,'RedeemAmt' => 0
		/**
		 * 2016-07-12
		 * «Trade status»
		 * Int
		 * «Successfully gets the number for ATM when value is 2.»
		 */
		,'RtnCode' => 2
		/**
		 * 2016-07-12
		 * «Trade message»
		 * Varchar(200)
		 */
		,'RtnMsg' => 'Get VirtualAccount Succeeded'
		/**
		 * 2016-07-12
		 * «Trade amount»
		 * Money
		 * «If an allPay member select paying by installment through credit card,
		 * this trade amount would be returned to seller with InstallmentAmount
		 * when this order is generated.»
		 */
		,'TradeAmt' => 4315
		/**
		 * 2016-07-12
		 * «Date of order generated»
		 * Varchar(20)
		 * «Formated as yyyy/MM/dd HH:mm:ss»
		 */
		,'TradeDate' => '2016/07/12 06:53:08'
		/**
		 * 2016-07-12
		 * «allPay trade number»
		 * Varchar(20)
		 * «Please keep the connection between allPay trade number and MerchantTradeNo.»
		 */
		,'TradeNo' => '1607120652267712'
		/**
		 * 2016-07-12
		 * «Virtual payment account»
		 * Varchar(16)
		 */
		,'vAccount' => '5211619735028140'
	];}
}

