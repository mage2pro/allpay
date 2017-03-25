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
		/** @var bool $ex */
		$ex = $this->extended();
		/** @var Event $f */
		/** @var Event $l */
		/** @var bool $paid */
		/** @var array(strig => string) $result */
		$result = [];
		if (!($paid = ($f = $this->e()) != ($l = df_tm($this->m())->responseL())) || $ex) {
			$result[$this->paymentIdLabel()] = $this->paymentId($f);
		}
		$result += $paid
			? ['Paid' => $l->paidTime()->toString($ex ? ZD::DATETIME_LONG : ZD::DATE_LONG)]
			: ['Expiration' => $l->expirationS()]
		;
		return $result;
	}
}