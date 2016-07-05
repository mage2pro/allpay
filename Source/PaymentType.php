<?php
namespace Dfe\AllPay\Source;
class PaymentType extends \Df\Config\SourceT {
	/**
	 * 2016-03-09
	 * https://stripe.com/docs/testing#cards
	 * https://mage2.pro/t/900
	 * @override
	 * @see \Df\Config\Source::map()
	 * @used-by \Df\Config\Source::toOptionArray()
	 * @return array(string => string)
	 */
	protected function map() {
		return [
			'Credit' => 'Bank Card'
			// 2016-07-02
			// Я так понял, что это чисто тайваньская штука:
			// пользователь вставляет картридер в свой (!) компьютер,
			// и проводит транзакцию своей физической банковской картой.
			// https://webatm.post.gov.tw
			// «[allPay] What is a «WebATM» payment?» https://mage2.pro/t/1838
			,'WebATM' => 'WebATM'
			,'ATM' => 'Physical ATM machine'
			// 2016-07-02
			// «[allPay] What is a «CVS» payment?» https://mage2.pro/t/1828
			// https://mage2.pro/t/1827/2
			,'CVS' => 'CVS code'
			// 2016-07-02
			// «[allPay] What is a «BARCODE» payment?» https://mage2.pro/t/BARCODE
			// https://mage2.pro/t/1827/2
			,'BARCODE' => 'BARCODE'
			// 2016-07-02
			// http://global.tenpay.com
			,'Tenpay' => 'Tenpay (WeChat)'
			// 2016-07-02
			,'TopUpUsed' => '歐付寶 allPay account'
		];
	}

	/** @return self */
	public static function s() {static $r; return $r ? $r : $r = df_o(__CLASS__);}
}