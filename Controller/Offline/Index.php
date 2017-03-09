<?php
namespace Dfe\AllPay\Controller\Offline;
use Df\Payment\Webhook as W;
use Dfe\AllPay\Webhook\Offline;
// 2017-02-14
/** @final Unable to use the PHP «final» keyword here because of the M2 code generation. */
class Index extends \Dfe\AllPay\Controller\Confirm\Index {
	/**
	 * 2017-01-02
	 * Нельзя, чтобы метод @see \Dfe\AllPay\Webhook\Offline::needCapture() тупо возвращал false:
	 * на самом деле, результат этого метода должен зависеть от контроллера:
	 * если контроллер — наш класс, то needCapture() должен вернуть false,
	 * а если контроллер — класс @see \Dfe\AllPay\Controller\Confirm\Index,
	 * то needCapture() должен вернуть true.
	 * @override
	 * @see \Df\Payment\Action\Webhook::prepare()
	 * @used-by \Df\Payment\Action\Webhook::execute()
	 * @param W|Offline $w
	 * @return void
	 */
	protected function prepare(W $w) {$w->needCaptureSet(false);}
}