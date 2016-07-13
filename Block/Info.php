<?php
namespace Dfe\AllPay\Block;
use Magento\Framework\DataObject;
class Info extends \Df\Payment\Block\ConfigurableInfo {
	/**
	 * 2016-07-13
	 * @override
	 * @see \Magento\Framework\View\Element\Template::getTemplate()
	 * @return string
	 */
	public function getTemplate() {
		return 'frontend' === $this->getArea() ? 'Dfe_AllPay::info.phtml' : parent::getTemplate();
	}

	/**
	 * 2016-07-13
	 * @return string
	 */
	public function paymentType() {return 'Bank Card';}

	/**
	 * 2016-07-13
	 * @override
	 * @see \Magento\Payment\Block\ConfigurableInfo::_prepareSpecificInformation()
	 * @used-by \Magento\Payment\Block\Info::getSpecificInformation()
	 * @param DataObject|null $transport
	 * @return DataObject
	 */
	protected function _prepareSpecificInformation($transport = null) {
		/** @var DataObject $result */
		$result = parent::_prepareSpecificInformation($transport);
		$result->addData(['Payment Type' => $this->paymentType()]);
		$this->markTestMode($result);
		return $result;
	}
}


