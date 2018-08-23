<?php
namespace Dfe\AllPay\Source;
/**
 * 2016-07-02
 * @used-by \Dfe\AllPay\Settings::options()
 * @method static Option s()
 */
final class Option extends \Df\Config\Source {
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
		,self::WEB_ATM => 'Web ATM'
		,self::ATM => 'Physical ATM machine'
		// 2016-07-02
		// «[allPay] What is a «CVS» payment?» https://mage2.pro/t/1828
		// https://mage2.pro/t/1827/2
		,'CVS' => 'CVS code'
		// 2016-07-02
		// «[allPay] What is a «BARCODE» payment?» https://mage2.pro/t/BARCODE
		// https://mage2.pro/t/1827/2
		,self::BARCODE => 'Barcode'
		// 2016-07-02
		// http://global.tenpay.com
		,'Tenpay' => 'Tenpay (WeChat)'
		// 2016-07-02
		,'TopUpUsed' => 'allPay Account'
	];}

	/**
	 * 2017-03-19
	 * @used-by \Dfe\AllPay\Source\Option::map()
	 * @used-by \Dfe\AllPay\W\Event\Offline::statusExpected()
	 */
	const ATM = 'ATM';

	/**
	 * 2016-08-08
	 * @used-by \Dfe\AllPay\Charge::pChoosePayment()
	 * @used-by \Dfe\AllPay\Source\Option::map()
	 * @used-by \Dfe\AllPay\W\Reader::te2i()
	 */
	const BANK_CARD = 'Credit';

	/**
	 * 2017-03-12
	 * @used-by \Dfe\AllPay\Source\Option::map()
	 * @used-by \Dfe\AllPay\W\Reader::te2i()
	 */
	const BARCODE = 'BARCODE';

	/**
	 * 2017-03-16
	 * @used-by \Dfe\AllPay\Source\Option::map()  
	 * @used-by \Dfe\AllPay\W\F::sufEvent()
	 * @used-by \Dfe\AllPay\W\Reader::isOffline()
	 */
	const WEB_ATM = 'WebATM';
}