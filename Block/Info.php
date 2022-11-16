<?php
namespace Dfe\AllPay\Block;
use Dfe\AllPay\Method;
use Dfe\AllPay\W\Event;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/**
 * 2016-07-13
 * @method Event|string|null e(...$k)
 * @method Method m()
 * @see \Dfe\AllPay\Block\Info\BankCard
 * @see \Dfe\AllPay\Block\Info\Offline
 * 2017-03-14
 * Этот класс намеренно НЕ АБСТРАКТНЫЙ!
 * Мы его используем в том случае, когда ПС ещё не прислала нам никаких оповещений,
 * и у нас @see e() в этом случае возвращает null.
 */
class Info extends \Df\Payment\Block\Info {
	/**
	 * 2016-07-20
	 * @used-by self::prepare()
	 * @see \Dfe\AllPay\Block\Info\BankCard::custom()
	 * @see \Dfe\AllPay\Block\Info\Offline::custom()
	 * @return array(string => string)
	 */
	protected function custom():array {return [];}

	/**
	 * 2016-07-13
	 * @override
	 * @see \Df\Payment\Block\Info::prepare()
	 * @used-by \Df\Payment\Block\Info::prepareToRendering()
	 * @used-by \Dfe\AllPay\Block\Info\Offline::prepareUnconfirmed()
	 */
	final protected function prepare():void {
		$this->si($this->custom());
		$this->siEx(['allPay Payment ID' => $this->e()->idE(), 'Magento Payment ID' => $this->e('MerchantTradeNo')]);
	}

	/**
	 * 2016-11-17
	 * @override
	 * @see \Df\Payment\Block\Info::prepareDic()
	 * @used-by \Df\Payment\Block\Info::getSpecificInformation()
	 * @see \Dfe\AllPay\Block\Info\BankCard::prepareDic()
	 */
	protected function prepareDic():void {$this->dic()->add('Payment Option', $this->choiceT(), -10);}
}