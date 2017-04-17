<?php
namespace Dfe\AllPay;
use Df\Payment\W\Event;
// 2017-04-17
/** @method \Dfe\AllPay\Method m() */
final class Status extends \Df\Payment\Status {
	/**
	 * 2017-04-17
	 * @override
	 * @see \Df\Payment\Status::_p()
	 * @used-by \Df\Payment\Status::p()
	 * @return string|null
	 */
	protected function _p() {return dfc($this, function() {return /** @var Event $ev */
		($ev = df_tmf($this->m())) ? __($ev->tl()) : (
			// 2016-08-13
			// Ситуация, когда покупатель в магазине выбрал оплату в рассрочку,
			// но платёжная система ещё не прислала оповещение о платеже (и способе оплаты).
			// Т.е. покупатель ещё ничего не оплатил,
			// и, возможно, просто закрыт страницу оплаты и уже ничего не оплатит.
			// Формируем заголовок по аналогии с
			// @see \Dfe\AllPay\W\Handler\BankCard::typeLabelByCode()
			!$this->m()->plan() ? null : df_cc_br(__('Bank Card (Installments)'), __('Not paid yet'))
		)
	;});}
}