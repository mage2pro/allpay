<?php
namespace Dfe\AllPay\Controller\Offline;
use Df\Payment\W\Handler;
use Dfe\AllPay\W\Handler\Offline;
// 2017-02-14
/** @final Unable to use the PHP «final» keyword here because of the M2 code generation. */
class Index extends \Dfe\AllPay\Controller\Confirm\Index {
	/**
	 * 2017-01-02
	 * Нельзя, чтобы метод @see \Dfe\AllPay\W\Handler\Offline::needCapture() тупо возвращал false:
	 * на самом деле, результат этого метода должен зависеть от контроллера:
	 * если контроллер — наш класс, то needCapture() должен вернуть false,
	 * а если контроллер — класс @see \Dfe\AllPay\Controller\Confirm\Index,
	 * то needCapture() должен вернуть true.
	 * @override
	 * @see \Df\Payment\W\Action::prepare()
	 * @used-by \Df\Payment\W\Action::execute()
	 * @param Handler|Offline $h
	 * @return void
	 */
	protected function prepare(Handler $h) {$h->needCaptureSet(false);}
}