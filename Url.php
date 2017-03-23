<?php
namespace Dfe\AllPay;
// 2017-03-23
final class Url extends \Df\Payment\Url {
	/**
	 * 2016-08-27
	 * Первый параметр — для test, второй — для live.
	 * @override
	 * @see \Df\Payment\Url::stageNames()
	 * @used-by \Df\Payment\Url::url()
	 * @return string[]
	 */
	protected function stageNames() {return ['-stage', ''];}
}