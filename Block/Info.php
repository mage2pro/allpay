<?php
namespace Dfe\AllPay\Block;
use Dfe\AllPay\Response;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order\Payment\Transaction as T;
class Info extends \Df\Payment\Block\ConfigurableInfo {
	/**
	 * 2016-07-13
	 * @override
	 * @see \Magento\Framework\View\Element\Template::getTemplate()
	 * @see \Magento\Payment\Block\Info::$_template
	 * @return string
	 */
	public function getTemplate() {
		return 'frontend' === $this->getArea() ? 'Dfe_AllPay::info.phtml' : parent::getTemplate();
	}

	/**
	 * 2016-07-13
	 * Whether the payment system has confirmed the payment?
	 * Прислала ли платёжная система подтверждение успешности платежа?
	 * @return bool
	 */
	public function isConfirmed() {return $this->transC() && $this->transCS();}

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
		$result->addData([
			'Payment Type' => $this->paymentType()
			,'Confirmed' => $this->isConfirmed() ? 'Yes' : 'No'
		]);
		$this->markTestMode($result);
		return $result;
	}

	/**
	 * 2016-07-13
	 * @return T|null
	 */
	private function transCS() {
		if (!isset($this->{__METHOD__})) {
			/** @var T $result */
			$result = null;
			/** @var T[] $valid */
			$valid = array_filter($this->transC(), function(T $t) {
				return Response::i(df_trans_raw_details($t))->validAndSuccessful();
			});
			/** @var int $count */
			$count = count($valid);
			df_assert_lt(2, $count);
			$this->{__METHOD__} = df_n_set(df_first($valid));
		}
		return df_n_get($this->{__METHOD__});
	}
}


