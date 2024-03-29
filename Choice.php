<?php
namespace Dfe\AllPay;
use Df\Payment\W\Event;
# 2017-04-17
/** @method \Dfe\AllPay\Method m() */
final class Choice extends \Df\Payment\Choice {
	/**
	 * 2017-04-17
	 * @override
	 * @see \Df\Payment\Choice::title()
	 * @used-by \Df\Payment\Block\Info::choiceT()
	 * @used-by \Df\Payment\Observer\DataProvider\SearchResult::execute()
	 */
	function title():string {return dfc($this, function():string {return /** @var Event $ev */
		($ev = $this->responseF()) ? __($ev->tl()) : (
			# 2016-08-13
			# Ситуация, когда покупатель в магазине выбрал оплату в рассрочку,
			# но платёжная система ещё не прислала оповещение о платеже (и способе оплаты).
			# Т.е. покупатель ещё ничего не оплатил,
			# и, возможно, просто закрыт страницу оплаты и уже ничего не оплатит.
			# Формируем заголовок по аналогии с
			# @see \Dfe\AllPay\W\Event\BankCard::tlByCode()
			!$this->m()->plan() ? '' : df_cc_br(__('Bank Card (Installments)'), __('Not yet paid'))
		)
	;});}
}