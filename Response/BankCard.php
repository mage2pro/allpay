<?php
namespace Dfe\AllPay\Response;
use Dfe\AllPay\Method;
use Df\Sales\Model\Order\Payment as DfPayment;
use Magento\Payment\Model\Method\AbstractMethod as M;
use Magento\Sales\Api\Data\OrderPaymentInterface as IOP;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment as OP;
class BankCard extends \Dfe\AllPay\Response {
	/**
	 * 2016-07-12
	 * @override
	 * @see \Dfe\AllPay\Response::handleInternal()
	 * @used-by \Dfe\AllPay\Response::handle()
	 * @return void
	 */
	public function handleInternal() {
		/** @var IOP|OP $payment */
		$payment = $this->payment();
		/** @var Method $method */
		$method = $payment->getMethodInstance();
		$method->setStore($this->order()->getStoreId());
		DfPayment::processActionS($payment, M::ACTION_AUTHORIZE_CAPTURE, $this->order());
		DfPayment::updateOrderS(
			$payment
			, $this->order()
			, Order::STATE_PROCESSING
			, $this->order()->getConfig()->getStateDefaultStatus(Order::STATE_PROCESSING)
			, $isCustomerNotified = true
		);
	}

	/**
	 * 2016-07-12
	 * @override
	 * @see \Dfe\AllPay\Response::expectedRtnCode()
	 * @used-by \Dfe\AllPay\Response::isSuccessful()
	 * @return int
	 * «Value 1 means a payment is paid successfully. The other means failure.»
	 */
	protected function expectedRtnCode() {return 1;}
}

