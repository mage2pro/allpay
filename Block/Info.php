<?php
namespace Dfe\AllPay\Block;
use Dfe\AllPay\InstallmentSales\Plan\Entity as Plan;
use Dfe\AllPay\Method;
use Dfe\AllPay\Response as R;
use Dfe\AllPay\Settings as S;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/**
 * @method Method method()
 * @method R responseF()
 * @method R responseL()
 */
class Info extends \Df\Payment\Block\ConfigurableInfo {
	/**
	 * 2016-07-13
	 * @return string
	 */
	public function paymentOption() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = $this->method()->paymentOptionTitle() ?:  __('Not selected yet');
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
		$result->addData(['Payment Option' => $this->paymentOption()]);
		if (!$this->responseF()) {
			// 2016-08-13
			// Ситуация, когда покупатель в магазине выбрал оплату в рассрочку,
			// но платёжная система ещё не прислала оповещение о платеже (и способе оплаты).
			// Т.е. покпатель ещё ничего не оплатил,
			// и, возможно, просто закрыт страницу оплаты и уже ничего не оплатит.
			if ($this->method()->plan()) {
				$result['Payments'] = $this->method()->plan()->numPayments();
			}
		}
		else {
			$result->addData($this->custom());
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

	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Block\Info::_prepareSpecificInformation()
	 * @return array(string => string)
	 */
	protected function custom() {return [];}
}


