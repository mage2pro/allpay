<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\W\Event\Offline as Event;
use Zend_Date as ZD;
/**   
 * 2016-07-25
 * @method Event|string|null responseF(string $k = null)
 * @see \Dfe\AllPay\Block\Info\ATM 
 * @see \Dfe\AllPay\Block\Info\Barcode
 * @see \Dfe\AllPay\Block\Info\CVS
 */
abstract class Offline extends \Dfe\AllPay\Block\Info {
	/**
	 * 2016-07-25
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @param Event $f
	 * @return string
	 */
	abstract protected function paymentId(Event $f);

	/**
	 * 2016-07-25
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @return string
	 */
	abstract protected function paymentIdLabel();

	/**
	 * 2016-07-25
	 * @override
	 * @see \Dfe\AllPay\Block\Info::custom()
	 * @used-by \Dfe\AllPay\Block\Info::_prepareSpecificInformation()
	 * @return array(string => string)
	 */
	final protected function custom() {
		/** @var Event $f */
		$f = $this->responseF();
		/** @var Event $l */
		$l = $this->m()->tm()->responseL();
		/** @var bool $paid */
		$paid = $f != $l;
		/** @var array(strig => string) $result */
		$result = [];
		if (!($paid && $this->isFrontend())) {
			$result[$this->paymentIdLabel()] = $this->paymentId($f);
		}
		$result += $paid
			? ['Paid' => $l->paidTime()->toString($this->isFrontend() ? ZD::DATE_LONG : ZD::DATETIME_LONG)]
			: ['Expiration' => $l->expirationS()]
		;
		return $result;
	}
}