<?php
namespace Dfe\AllPay\Controller\Confirm;
use Dfe\AllPay\Response as R;
use Df\Framework\Controller\Result\Text;
use Df\Sales\Model\Order as DfOrder;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment as OP;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-07-04
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return Text
	 */
	public function execute() {return R::i(df_is_it_my_local_pc() ? false : $_REQUEST)->handle();}
}


