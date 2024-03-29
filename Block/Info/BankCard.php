<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Dfe\AllPay\W\Event\BankCard as Event;
/**  
 * 2016-07-28 
 * @final Unable to use the PHP «final» keyword here because of the M2 code generation.
 * @method Event|string|null e(...$k)
 */
class BankCard extends \Dfe\AllPay\Block\Info {
	/**
	 * 2016-07-28
	 * @override
	 * @see \Dfe\AllPay\Block\Info::custom()
	 * @used-by \Dfe\AllPay\Block\Info::prepare()
	 * @return array(string => string)
	 */
	final protected function custom():array {
		$r = ['Card Number' => df_ccc('******', $this->e('card6no', 'card4no'))]; /** @var array(strig => string) $r */
		if ($ex = $this->extended()) {  /** @var bool $ex */
			$r['ECI'] = $this->eci();
		}
		$r['Authorization Code'] = $this->e('auth_code');
		if ($ex) {$r += [
			'Authorization Time' => $this->e()->authTime()
			# 2016-07-29
			# 1) [allPay] What does mean the «gwsr» response parameter? https://mage2.pro/t/1904
			# 2) [allPay] How to locate a bank card transaction in the «Merchant Back End Platform»
			# using an «allPay Authorization Code» (the «gwsr» response parameter)?
			# https://mage2.pro/t/1911
			# 3) http://creditvendor-stage.allpay.com.tw/DumpAuth/OrderView?TradeID=10547181
			,'allPay Authorization Code' => $this->allpayAuthCode()
		];}
		return df_clean($r);
	}

	/**
	 * 2016-08-09
	 * 2017-07-20
	 * A similar implementation for the Moip extension:
	 * https://github.com/mage2pro/moip/blob/0.8.3/Block/Info.php#L26
	 * @override
	 * @see \Dfe\AllPay\Block\Info::prepareDic()
	 * @used-by \Df\Payment\Block\Info::getSpecificInformation()
	 */
	final protected function prepareDic():void {
		parent::prepareDic();
		/** @var Event $e */ /** @var int $n */
		if (($e = $this->e()) && ($n = $e->numPayments())) {
			$this->dic()->addAfter('Payment Option', 'Payments', $n);
		}
	}

	/**
	 * 2016-08-13
	 * ПС работает с перенаправлением покупателя на свою страницу.
	 * Покупатель был туда перенаправлен, однако ПС ещё не прислала оповещение о платеже
	 * (и способе оплаты). Т.е. покупатель ещё ничего не оплатил,
	 * и, возможно, просто закрыл страницу оплаты и уже ничего не оплатит.
	 * 2017-07-20
	 * A similar implementation for the Moip extension:
	 * https://github.com/mage2pro/moip/blob/0.8.3/Block/Info.php#L26
	 * @override
	 * @see \Df\Payment\Block\Info::prepareUnconfirmed()
	 * @used-by \Df\Payment\Block\Info::prepareToRendering()
	 */
	final protected function prepareUnconfirmed():void {
		if (/** @var Plan $p*/$p = $this->m()->plan()) {
			$this->si('Payments', $p->numPayments());
		}
	}

	/**
	 * 2016-07-29
	 * @used-by self::custom()
	 */
	private function allpayAuthCode():string {return df_tag_ab(
		 $gwsr = $this->e('gwsr') /** @var string $gwsr */
		,dfp_url_api($this, 'http://creditvendor{stage}.allpay.com.tw/DumpAuth/OrderView?TradeID=%d', [], $this->isTest(), $gwsr)
	);}

	/**
	 * 2016-07-28
	 * https://support.veritrans.co.id/hc/en-us/articles/204161150-What-is-ECI-on-3D-Secure-
	 * https://www.paydollar.com/b2c2/eng/merchant/help/f_onlinehelp_eci.htm
	 * @used-by self::custom()
	 */
	private function eci():string {/** @var string|null $eci */return is_null($eci = $this->e('eci')) ? '' :
		df_desc("0{$eci}", dfa([
			0 => 'Card holder and issuing bank not registered as a 3D Secure'
			,1 => 'One of card holder or issuing bank not registered as a 3D Secure'
			,2 => 'Card holder and issuing bank are 3D Secure. 3dSecure authentication successful'
			,5 => 'Card holder and issuing bank are 3D Secure. 3dSecure authentication successful'
			,6 => 'One of card holder or issuing bank not registered as a 3D Secure'
			,7 => 'Card holder and issuing bank not registered as a 3D Secure'
		], intval($eci), 'Unknown code'))
	;}
}