<?php
namespace Dfe\AllPay\Block;
use Dfe\AllPay\Method;
use Dfe\AllPay\Webhook as W;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/**
 * 2016-07-13
 * @method Method m()
 * @method W|string|null responseF(string $k = null)
 * @method W|string|null responseL(string $k = null)
 * @see \Dfe\AllPay\Block\Info\BankCard
 * @see \Dfe\AllPay\Block\Info\Offline
 */
abstract class Info extends \Df\PaypalClone\BlockInfo {
	/**
	 * 2016-07-13
	 * @final Unable to use the PHP «final» keyword here because of the M2 code generation.
	 * @return string
	 */
	function paymentOption() {return dfc($this, function() {return
		$this->m()->paymentOptionTitle() ?:  __('Not selected yet')
	;});}

	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Block\Info::_prepareSpecificInformation()
	 * @see \Dfe\AllPay\Block\Info\BankCard::custom()
	 * @see \Dfe\AllPay\Block\Info\Offline::custom()
	 * @return array(string => string)
	 */
	protected function custom() {return [];}

	/**
	 * 2016-07-13
	 * @override
	 * @see \Df\Payment\Block\Info::prepare()
	 * @used-by \Df\Payment\Block\Info::_prepareSpecificInformation()
	 */
	final protected function prepare() {
		$this->si($this->custom());
		$this->siB([
			'allPay Payment ID' => $this->responseF()->externalId()
			,'Magento Payment ID' => $this->responseF()->parentIdRaw()
		]);
	}

	/**
	 * 2016-11-17
	 * @override
	 * @see \Df\Payment\Block\Info::prepareDic()
	 * @used-by \Df\Payment\Block\Info::getSpecificInformation()
	 * @see \Dfe\AllPay\Block\Info\BankCard::prepareDic()
	 * @return void
	 */
	protected function prepareDic() {$this->dic()->add('Payment Option', $this->paymentOption(), -10);}

	/**
	 * 2016-08-13
	 * Сюда мы попадаем в 2 случаях:
	 * 1) Платёж либо находится в состоянии «Review» (случай модулей Stripe и Omise).
	 * 2) Модуль работает с перенаправлением покупателя на страницу платёжной системы,
	 * покупатель был туда перенаправлен, однако платёжная система ещё не прислала
	 * оповещение о платеже (и способе оплаты).
	 * Т.е. покупатель ещё ничего не оплатил,  и, возможно, просто закрыл страницу оплаты
	 * и уже ничего не оплатит (случай модуля allPay).
	 * @override
	 * @see \Df\Payment\Block\Info::siWait()
	 * @used-by \Df\Payment\Block\Info::_prepareSpecificInformation()
	 */
	protected function siWait() {
		if ($this->m()->plan()) {
			$this->si('Payments', $this->m()->plan()->numPayments());
		}
	}
}