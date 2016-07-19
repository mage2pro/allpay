<?php
namespace Dfe\AllPay\Response;
use Magento\Sales\Model\Order;
class ATM extends \Dfe\AllPay\Response {
	/**
	 * 2016-07-19
	 * @override
	 * @see \Dfe\AllPay\Response::getInformationForBlock()
	 * @used-by \Dfe\AllPay\Block\Info::_prepareSpecificInformation()
	 * @return array(strig => string)
	 */
	public function getInformationForBlock() {
		return ['Account Number' => $this['vAccount']];
	}

	/**
	 * 2016-07-12
	 * @override
	 * @see \Dfe\AllPay\Response::handleInternal()
	 * @used-by \Dfe\AllPay\Response::handle()
	 * @return void
	 */
	public function handleInternal() {}

	/**
	 * 2016-07-12
	 * @override
	 * @see \Dfe\AllPay\Response::expectedRtnCode()
	 * @used-by \Dfe\AllPay\Response::isSuccessful()
	 * @return int
	 * «Successfully gets the number for ATM when value is 2.»
	 */
	protected function expectedRtnCode() {return 2;}
}

