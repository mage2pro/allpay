<?php
namespace Dfe\AllPay\Block\Info;
use Dfe\AllPay\Response\ATM as R;
use Zend_Date as ZD;
/**
 * @method R responseF()
 * @method R responseL()
 */
class ATM extends Offline {
	/**
	 * 2016-07-20
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
		/** @var array(strig => string) $result */
		$result = ['Account Number' => $f['vAccount']];
		if ($f != $l) {
			$result['Paid'] = $l->paidTime()->toString(
				$this->getIsSecureMode() ? ZD::DATE_LONG : ZD::DATETIME_LONG
			);
		}
		else {
			$result['Expiration'] = $l->expirationS();
		}
		return $result;
	}
}

