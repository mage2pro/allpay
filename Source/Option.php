<?php
namespace Dfe\AllPay\Source;
/** @method static Option s() */
final class Option extends \Df\Config\SourceT {
	/**
	 * @override
	 * @see \Df\Config\Source::map()
	 * @used-by \Df\Config\Source::toOptionArray()
	 * @return array(string => string)
	 */
	protected function map() {return [
		self::BANK_CARD => 'Bank Card'
		// 2016-07-02
		// Я так понял, что это чисто тайваньская штука:
		// пользователь вставляет картридер в свой (!) компьютер,
		// и проводит транзакцию своей физической банковской картой.
		// https://webatm.post.gov.tw
		// «[allPay] What is a «WebATM» payment?» https://mage2.pro/t/1838
		,'WebATM' => 'Web ATM'
		,'ATM' => 'Physical ATM machine'
		// 2016-07-02
		// «[allPay] What is a «CVS» payment?» https://mage2.pro/t/1828
		// https://mage2.pro/t/1827/2
		,'CVS' => 'CVS code'
		// 2016-07-02
		// «[allPay] What is a «BARCODE» payment?» https://mage2.pro/t/BARCODE
		// https://mage2.pro/t/1827/2
		,'BARCODE' => 'Barcode'
		// 2016-07-02
		// http://global.tenpay.com
		,'Tenpay' => 'Tenpay (WeChat)'
		// 2016-07-02
		,'TopUpUsed' => 'allPay Account'
	];}

	/**
	 * 2016-08-08
	 * @used-by \Dfe\AllPay\Charge::pChoosePayment()
	 * @used-by \Dfe\AllPay\Webhook::classSuffixS()
	 * @used-by \Dfe\AllPay\Source\Option::map()
	 */
	const BANK_CARD = 'Credit';
}