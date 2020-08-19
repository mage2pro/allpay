<?php
namespace Dfe\AllPay\W\Event;
use Dfe\AllPay\Source\Option;
use Zend_Date as ZD;
# 2017-03-13
final class BankCard extends \Dfe\AllPay\W\Event {
	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Block\Info\BankCard::custom()
	 * @return ZD|null
	 */
	function authTime() {return self::time($this->r('process_date'));}

	/**
	 * 2016-08-09
	 * 2017-03-23 Если оплата произведена единоразово, то метод вернёт 0.
	 * @used-by tlByCode()
	 * @used-by \Dfe\AllPay\Block\Info\BankCard::prepareDic()
	 * @return int
	 */
	function numPayments() {return intval($this->r('stage'));}

	/**
	 * 2016-08-09
	 * @override
	 * @see \Dfe\AllPay\W\Event::tlByCode()
	 * @used-by \Dfe\AllPay\W\Event::tl_()
	 * @param string $f
	 * @param string $l
	 * @return string|null
	 */
	protected function tlByCode($f, $l) {return df_cc_s(
		parent::tlByCode(df_assert_eq(Option::BANK_CARD, $f), $l)
		,!$this->numPayments() ? '' : '(Installments)'
	);}
}