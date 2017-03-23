<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\W\Event\BankCard as Event;
use Magento\Framework\Phrase;
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
	 * @used-by \Dfe\AllPay\Block\Info::_prepareSpecificInformation()
	 * @return array(string => string)
	 */
	final protected function custom() {
		/** @var array(strig => string) $result */
		$result = [];
		$result['Card Number'] = df_ccc('******', $this->e('card6no', 'card4no'));
		if ($this->isBackend()) {
			$result['ECI'] = $this->eci();
		}
		$result['Authorization Code'] = $this->e('auth_code');
		if ($this->isBackend()) {$result += [
			'Authorization Time' => $this->e()->authTime()
			// 2016-07-29
			// [allPay] What does mean the «gwsr» response parameter?
			// https://mage2.pro/t/1904
			//
			// [allPay] How to locate a bank card transaction in the «Merchant Back End Platform»
			// using an «allPay Authorization Code» (the «gwsr» response parameter)?
			// https://mage2.pro/t/1911
			//
			// http://creditvendor-stage.allpay.com.tw/DumpAuth/OrderView?TradeID=10547181
			,'allPay Authorization Code' => $this->allpayAuthCode()
		];}
		return df_clean($result);
	}

	/**
	 * 2016-08-09
	 * @override
	 * @see \Dfe\AllPay\Block\Info::prepareDic()
	 * @used-by \Df\Payment\Block\Info::getSpecificInformation()
	 * @return void
	 */
	final protected function prepareDic() {
		parent::prepareDic();
		/** @var Event $e */
		/** @var int $n */
		if (($e = $this->e()) && ($n = $e->numPayments())) {
			$this->dic()->addAfter('Payment Option', 'Payments', $n);
		}
	}

	/**
	 * 2016-07-29
	 * @return string|null
	 */
	private function allpayAuthCode() {
		/** @var string $url */
		$url = 'http://creditvendor{stage}.allpay.com.tw/DumpAuth/OrderView?TradeID=%d';
		/** @var string $gwsr */
		return df_tag_ab($gwsr = $this->e('gwsr'), dfp_url($this, $url, [], $this->isTest(), $gwsr));
	}

	/**
	 * 2016-07-28
	 * https://support.veritrans.co.id/hc/en-us/articles/204161150-What-is-ECI-on-3D-Secure-
	 * https://www.paydollar.com/b2c2/eng/merchant/help/f_onlinehelp_eci.htm
	 * @used-by custom()
	 * @return string|null
	 */
	private function eci() {/** @var string|null $eci */return is_null($eci = $this->e('eci')) ? null :
		sprintf("0{$eci} (%s)", dfa([
			0 => 'Card holder and issuing bank not registered as a 3D Secure'
			,1 => 'One of card holder or issuing bank not registered as a 3D Secure'
			,2 => 'Card holder and issuing bank are 3D Secure. 3dSecure authentication successful'
			,5 => 'Card holder and issuing bank are 3D Secure. 3dSecure authentication successful'
			,6 => 'One of card holder or issuing bank not registered as a 3D Secure'
			,7 => 'Card holder and issuing bank not registered as a 3D Secure'
		], intval($eci), 'Unknown code'))
	;}
}