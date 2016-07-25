<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\Response\Offline as R;
use Zend_Date as ZD;
/**
 * @method R responseF()
 * @method R responseL()
 */
abstract class Offline extends \Dfe\AllPay\Block\Info {
	/**
	 * 2016-07-25
	 * @used-by \Dfe\AllPay\Block\Info\Offline::custom()
	 * @param R $f
	 * @return string
	 */
	abstract protected function paymentId(R $f);

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
	protected function custom() {
		/** @var R $f */
		$f = $this->responseF();
		/** @var R $l */
		$l = $this->responseL();
		/** @var bool $paid */
		$paid = $f != $l;
		/** @var bool $frontend */
		$frontend = $this->getIsSecureMode();
		/** @var array(strig => string) $result */
		$result = [];
		if (!($paid && $frontend)) {
			$result[$this->paymentIdLabel()] = $this->paymentId($f);
		}
		$result +=
			$paid
			? ['Paid' => $l->paidTime()->toString($frontend ? ZD::DATE_LONG : ZD::DATETIME_LONG)]
			: ['Expiration' => $l->expirationS()]
		;
		return $result;
	}
}

