<?php
namespace Dfe\AllPay\Controller\CustomerReturn;
use Dfe\AllPay\Response as R;
use Df\Sales\Model\Order as DfOrder;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment as OP;
use Magento\Sales\Model\Order\Payment\Transaction as T;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-07-14
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return Redirect
	 */
	public function execute() {
		/** @var Order|DfOrder $order */
		$order = df_checkout_session()->getLastRealOrder();
		/** @var Redirect $result */
		if ($order && !$order->isCanceled()) {
			$result = $this->_redirect('checkout/onepage/success');
		}
		else {
			df_checkout_session()->restoreQuote();
			// 2016-05-06
			// «How to redirect a customer to the checkout payment step?» https://mage2.pro/t/1523
			$result = $this->_redirect('checkout', ['_fragment' => 'payment']);
			/**
			 * 2016-07-14
			 * @todo It would be nice to show an explanation message to the customer
			 * when it returns to the store after an unsuccessful payment attempt.
			 */
			/** @var T $t */
			$t = df_trans_by_payment_last($order->getPayment());
			df_checkout_error(df_trans_raw_details($t, 'RtnMsg'));
		}
		return $result;
	}
}


