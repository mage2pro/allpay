<?php
namespace Dfe\AllPay\Response;
class ATM extends Offline {
	/**
	 * 2016-07-19
	 * @override
	 * @see \Dfe\AllPay\Response::getInformationForBlock()
	 * @used-by \Dfe\AllPay\Block\Info::_prepareSpecificInformation()
	 * @return array(strig => string)
	 */
	public function getInformationForBlock() {return [
		'Account Number' => $this['vAccount']
		,'Expiration' => $this->expirationS()
	];}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Dfe\AllPay\Response\Offline::expectedRtnCodeOffline()
	 * @used-by \Dfe\AllPay\Response\Offline::expectedRtnCode()
	 * @return int
	 * «Successfully gets the number for ATM when value is 2.»
	 */
	protected function expectedRtnCodeOffline() {return 2;}
}

