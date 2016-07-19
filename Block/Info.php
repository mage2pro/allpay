<?php
namespace Dfe\AllPay\Block;
use Dfe\AllPay\Response as R;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/**
 * @method R responseF()
 * @method R responseL()
 */
class Info extends \Df\Payment\Block\ConfigurableInfo {
	/**
	 * 2016-07-13
	 * @return string
	 */
	public function paymentType() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} =
				!$this->responseF()
				? __('Not paid yet') :
				$this->responseF()->paymentTypeTitle()
			;
		}
		return $this->{__METHOD__};
	}

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
		if ($this->responseF()) {
			$result->addData($this->responseF()->getInformationForBlock());
			if (!$this->getIsSecureMode()) {
				$result->addData([
					'allPay Payment ID' => $this->responseF()->externalId()
					,'Magento Payment ID' => $this->responseF()->requestId()
				]);
			}
		}
		if ($this->isTest()) {
			$result->setData('Mode', __($this->testModeLabel()));
		}
		return $result;
	}
}


