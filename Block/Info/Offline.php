<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\W\Event\Offline as Event;
use Zend_Date as ZD;
/**   
 * 2016-07-25
 * @see \Dfe\AllPay\Block\Info\ATM 
 * @see \Dfe\AllPay\Block\Info\Barcode
 * @see \Dfe\AllPay\Block\Info\CVS
 */
abstract class Offline extends \Dfe\AllPay\Block\Info {
	/**
	 * 2016-07-25
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @see \Dfe\AllPay\Block\Info\ATM::paymentId()
	 * @see \Dfe\AllPay\Block\Info\Barcode::paymentId()
	 * @see \Dfe\AllPay\Block\Info\CVS::paymentId()
	 */
	abstract protected function paymentId(Event $f):string;

	/**
	 * 2016-07-25
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @see \Dfe\AllPay\Block\Info\ATM::paymentIdLabel()
	 * @see \Dfe\AllPay\Block\Info\Barcode::paymentIdLabel()
	 * @see \Dfe\AllPay\Block\Info\CVS::paymentIdLabel()
	 */
	abstract protected function paymentIdLabel():string;

	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info::custom()
	 * @used-by \Df\Payment\Block\Info::prepareToRendering()
	 * @return array(string => string)
	 */
	final protected function custom():array {
		$ex = $this->extended(); /** @var bool $ex */
		$result = []; /** @var array(strig => string) $result */
		/** @var Event $f */ /** @var Event $l */ /** @var bool $paid */
		if (!($paid = ($f = $this->e()) != ($l = $this->tm()->responseL())) || $ex) {
			$result[$this->paymentIdLabel()] = $this->paymentId($f);
		}
		$result += $paid
			? ['Paid' => $l->paidTime()->toString($ex ? ZD::DATETIME_LONG : ZD::DATE_LONG)]
			: ['Expiration' => $l->expirationS()]
		;
		# 2017-04-14
		# «About ATM payment information:
		# It's lost BankCode(If don't have bankcode can't pay it) ,can you add it?» https://mage2.pro/t/3686/6
		if ($bankCode = $f->r('BankCode')) {
			$result += ['Bank Code' => $bankCode];
		}
		return $result;
	}

	/**
	 * 2017-04-13
	 * ПС работает с перенаправлением покупателя на свою страницу.
	 * Покупатель был туда перенаправлен, однако ПС ещё не прислала оповещение о платеже
	 * (и способе оплаты). Т.е. покупатель ещё ничего не оплатил,
	 * и, возможно, просто закрыл страницу оплаты и уже ничего не оплатит.
	 * @override
	 * @see \Df\Payment\Block\Info::prepareUnconfirmed()
	 * @used-by \Df\Payment\Block\Info::prepareToRendering()
	 */
	final protected function prepareUnconfirmed():void {$this->prepare();}
}