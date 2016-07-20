<?php
namespace Dfe\AllPay\Response;
use Dfe\AllPay\Method;
use Zend_Date as ZD;
abstract class Offline extends \Dfe\AllPay\Response {
	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Response\Offline::expectedRtnCode()
	 * @return int
	 */
	abstract protected function expectedRtnCodeOffline();

	/**
	 * 2016-07-19
	 * @return string
	 */
	public function expirationS() {
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
	 * 2016-07-20
	 * @return bool
	 */
	public function isPaid() {return !!$this->paidTime();}

	/**
	 * 2016-07-20
	 * @return ZD|null
	 */
	public function paidTime() {
		if (!isset($this->{__METHOD__})) {
			/** @var string $resultS */
			$resultS = $this['PaymentDate'];
			$this->{__METHOD__} = df_n_set(!$resultS ? null :
				df_date_parse($resultS, 'y/MM/dd HH:mm:ss', Method::TIMEZONE)
			);
		}
		return df_n_get($this->{__METHOD__});
	}

	/**
	 * 2016-07-12
	 * @override
	 * @see \Dfe\AllPay\Response::expectedRtnCode()
	 * @used-by \Dfe\AllPay\Response::isSuccessful()
	 * @return int
	 */
	protected function expectedRtnCode() {
		return $this->needCapture() ? parent::expectedRtnCode() : $this->expectedRtnCodeOffline();
	}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\R\Response::handleBefore()
	 * @used-by \Df\Payment\R\Response::handle()
	 * @return void
	 */
	protected function handleBefore() {
		$this->_needCapture = !$this[self::KEY];
		/**
		 * 2016-07-20
		 * Надо обязательно удалить ключ, иначе подпись будет вычислена неправильно:
		 * @see \Df\Payment\R\Response::signer()
		 */
		$this->unsetData(self::KEY);
	}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\R\Response::needCapture()
	 * @used-by \Df\Payment\R\Response::handle()
	 * @return bool
	 */
	protected function needCapture() {return $this->_needCapture;}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\R\Response::responseTransactionId()
	 * @used-by \Df\Payment\R\Response::payment()
	 * @return string
	 */
	protected function responseTransactionId() {
		return implode('-', [parent::responseTransactionId(),
			$this->needCapture() ? 'capture' : 'info'
		]);
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

	/**
	 * 2016-07-20
	 * @var bool
	 */
	private $_needCapture;

	/**
	 * 2016-07-20
	 * @used-by \Dfe\AllPay\Response\Offline::handleBefore()
	 * @used-by \Dfe\AllPay\Controller\Offline\Index::execute()
	 */
	const KEY = 'offline';
}

