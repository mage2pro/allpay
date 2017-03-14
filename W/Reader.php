<?php
namespace Dfe\AllPay\W;
use Dfe\AllPay\Source\Option;
// 2017-03-10
final class Reader extends \Df\Payment\W\Reader {
	/**
	 * 2017-03-13
	 * @used-by \Dfe\AllPay\W\F::suf()
	 * @return bool
	 */
	function isBankCard() {return self::$BANK_CARD === $this->t();}

	/**
	 * 2017-03-10
	 * @override
	 * @see \Df\Payment\W\Reader::kt()
	 * @used-by \Df\Payment\W\Reader::t()
	 * @return string
	 */
	 protected function kt() {return 'PaymentType';}

	/**
	 * 2017-03-12
	 * Converts an event type from the PSP format to our internal format.
	 * @override
	 * @see \Df\Payment\W\Reader::te2i()
	 * @used-by \Df\Payment\W\Reader::t()
	 * @param string $t
	 * @return string
	 */
	protected function te2i($t) {return dftr(df_first(explode('_', $t)), [
		Option::BANK_CARD => self::$BANK_CARD, Option::BARCODE => 'Barcode'
	]);}

	/**
	 * 2017-03-13
	 * @used-by isBankCard()
	 * @used-by te2i()
	 * @var string
	 */
	private static $BANK_CARD = 'BankCard';
}