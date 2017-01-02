<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\Method;
use Dfe\AllPay\Webhook\BankCard as R;
use Magento\Framework\Phrase;
/**
 * @method R|string|null responseF(string $key = null)
 * @method R|string|null responseL(string $key = null)
 */
class BankCard extends \Dfe\AllPay\Block\Info {
	/**
	 * 2016-07-28
	 * @override
	 * @see \Dfe\AllPay\Block\Info::custom()
	 * @used-by \Dfe\AllPay\Block\Info::_prepareSpecificInformation()
	 * @return array(string => string)
	 */
	protected function custom() {
		/** @var array(strig => string) $result */
		$result = [];
		$result['Card Number'] = $this->cardNumber();
		if ($this->isBackend()) {
			$result['ECI'] = $this->eciS();
		}
		$result['Authorization Code'] = $this->r('auth_code');
		if ($this->isBackend()) {
			$result += [
				'Authorization Time' => R::time($this->r('process_date'))
				/**
				 * 2016-07-29
				 * [allPay] What does mean the «gwsr» response parameter?
				 * https://mage2.pro/t/1904
				 *
				 * [allPay] How to locate a bank card transaction in the «Merchant Back End Platform»
				 * using an «allPay Authorization Code» (the «gwsr» response parameter)?
				 * https://mage2.pro/t/1911
				 *
				 * http://creditvendor-stage.allpay.com.tw/DumpAuth/OrderView?TradeID=10547181
				 */
				,'allPay Authorization Code' => $this->allpayAuthCode()
			];
		}
		return df_clean($result);
	}

	/**
	 * 2016-08-09
	 * @override
	 * @see \Dfe\AllPay\Block\Info::prepareDic()
	 * @used-by \Df\Payment\Block\Info::getSpecificInformation()
	 * @return void
	 */
	protected function prepareDic() {
		parent::prepareDic();
		if ($this->responseF() && $this->responseF()->isInstallment()) {
			$this->dic()->addAfter('Payment Option', 'Payments', $this->numPayments());
		}
	}

	/**
	 * 2016-07-29
	 * @return string|null
	 */
	private function allpayAuthCode() {
		/** @var string $template */
		$template = 'http://creditvendor{stage}.allpay.com.tw/DumpAuth/OrderView?TradeID=%d';
		return df_tag_ab($this->r('gwsr'), $this->m()->url($template, $this->isTest(), $this->r('gwsr')));
	}

	/** @return string */
	private function cardNumber() {return df_ccc('******', $this->r('card6no', 'card4no'));}

	/**
	 * 2016-07-28
	 * https://support.veritrans.co.id/hc/en-us/articles/204161150-What-is-ECI-on-3D-Secure-
	 * https://www.paydollar.com/b2c2/eng/merchant/help/f_onlinehelp_eci.htm
	 * @return int|null
	 */
	private function eci() {return dfc($this, function() {
		/** @var int|null $result */
		$result = $this->r('eci');
		return is_null($result) ? $result : intval($result);
	});}

	/**
	 * 2016-07-28
	 * @return Phrase
	 */
	private function eciMeaning() {return __(dfa([
		0 => 'Card holder and issuing bank not registered as a 3D Secure'
		,1 => 'One of card holder or issuing bank not registered as a 3D Secure'
		,2 => 'Card holder and issuing bank are 3D Secure. 3dSecure authentication successful'
		,5 => 'Card holder and issuing bank are 3D Secure. 3dSecure authentication successful'
		,6 => 'One of card holder or issuing bank not registered as a 3D Secure'
		,7 => 'Card holder and issuing bank not registered as a 3D Secure'
	], $this->eci(), 'Unknown code'));}

	/**
	 * 2016-07-28
	 * @return string|null
	 */
	private function eciS() {return
		is_null($this->eci()) ? null : "0{$this->eci()} ({$this->eciMeaning()})"
	;}

	/**
	 * 2016-08-12
	 * @return int
	 */
	private function numPayments() {return intval($this->r('stage'));}
	
	/**
	 * 2016-07-28
	 * @param string[] ...$k
	 * @return string|null|array(string => mixed)
	 */
	private function r(...$k) {return $this->responseF()->req(1 === count($k) ? df_first($k) : $k);}
}