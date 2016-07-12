<?php
namespace Dfe\AllPay\Controller\Confirm;
use Dfe\AllPay\Method;
use Dfe\AllPay\Response as R;
use Dfe\AllPay\Response\ATM;
use Dfe\AllPay\Response\BankCard;
use Df\Framework\Controller\Result\Text;
use Df\Sales\Model\Order as DfOrder;
use Df\Sales\Model\Order\Payment as DfPayment;
use Magento\Payment\Model\Method\AbstractMethod as M;
use Magento\Sales\Api\Data\OrderPaymentInterface as IOP;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment as OP;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-07-04
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return Text
	 */
	public function execute() {return $this->r()->handle();}

	/**
	 * 2016-07-09
	 * @return R
	 */
	private function r() {
		if (!isset($this->{__METHOD__})) {
			$this->{__METHOD__} = R::ic($this->rC(), df_is_it_my_local_pc() ? true : $_REQUEST);
		}
		return $this->{__METHOD__};
	}

	/**
	 * 2016-07-12
	 * @return string
	 */
	private function rC() {
		/** @var string $paymentType */
		$paymentType = df_first(explode('_',
			df_is_it_my_local_pc() ? 'Credit_CreditCard' : df_request('PaymentType')
		));
		return dfa(['ATM' => ATM::class, 'Credit' => BankCard::class], $paymentType);
	}
}


