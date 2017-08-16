<?php
namespace Dfe\AllPay\W;
use Dfe\AllPay\Source\Option;
// 2017-03-10
final class Reader extends \Df\Payment\W\Reader {
	/**
	 * 2017-03-13
	 * 2017-03-16
	 * This method should be here, but not in the base event class (@see \Dfe\AllPay\W\Event)
	 * because the method is used by the factory (@see \Dfe\AllPay\W\F) to choose a concrete event class.
	 * @used-by \Dfe\AllPay\W\F::sufEvent()
	 * @used-by \Dfe\AllPay\W\F::sufNav()
	 * @return bool
	 */
	function isOffline() {return !in_array($this->t(), [self::BANK_CARD, Option::WEB_ATM]);}

	/**
	 * 2017-03-10
	 * @override
	 * @see \Df\Payment\W\Reader::kt()
	 * @used-by \Df\Payment\W\Reader::tRaw()
	 * @return string
	 */
	 protected function kt() {return 'PaymentType';}

	/**
	 * 2017-03-12 Converts an event type from the PSP format to our internal format.
	 * @override
	 * @see \Df\Payment\W\Reader::te2i()
	 * @used-by \Df\Payment\W\Reader::t()
	 * @param string $t
	 * @return string
	 */
	protected function te2i($t) {return dftr(df_first(explode('_', $t)), [
		Option::BANK_CARD => self::BANK_CARD, Option::BARCODE => 'Barcode'
	]);}

	/**
	 * 2017-03-13
	 * @used-by isOffline()
	 * @used-by te2i()
	 * @var string
	 */
	const BANK_CARD = 'BankCard';
}