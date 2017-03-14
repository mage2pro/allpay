<?php
namespace Dfe\AllPay\Controller\Confirm;
use Dfe\AllPay\W\Handler as W;
// 2017-02-14
/** @see \Dfe\AllPay\Controller\Offline\Index */
class Index extends \Df\Payment\W\Action {
	/**
	 * 2017-01-02
	 * @override
	 * @see \Df\Payment\W\Action::error()
	 * @used-by \Df\Payment\W\Action::execute()
	 * @param \Exception $e
	 * @return $this
	 */
	final protected function error(\Exception $e) {return W::resultError($e);}
}