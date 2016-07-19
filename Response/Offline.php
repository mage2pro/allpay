<?php
namespace Dfe\AllPay\Response;
use Magento\Sales\Model\Order;
use Zend_Date as ZD;
abstract class Offline extends \Dfe\AllPay\Response {
	/**
	 * 2016-07-12
	 * @override
	 * @see \Dfe\AllPay\Response::handleInternal()
	 * @used-by \Dfe\AllPay\Response::handle()
	 * @return void
	 */
	public function handleInternal() {}

	/**
	 * 2016-07-19
	 * @return string
	 */
	protected function expirationS() {
		if (!isset($this->{__METHOD__})) {
			/** @var string $result */
			$result = df_dts($this->expiration(), ZD::DATE_LONG);
			/** @var int $daysLeft */
			$daysLeft = df_days_left($this->expiration());
			/** @var string $note */
			$note =
				0 > $daysLeft
				? __('expired')
				: (
					0 === $daysLeft
					? __('today')
					: (
						1 === $daysLeft
						? __('1 day left')
					    :  __('%1 days left', $daysLeft)
					)
				)
			;
			$this->{__METHOD__} = "{$result} ({$note})";
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-19
	 * @return ZD
	 */
	private function expiration() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = new ZD($this['ExpireDate'], 'y/MM/dd');
		}
		return $this->{__METHOD__};
	}
}

