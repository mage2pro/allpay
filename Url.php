<?php
namespace Dfe\AllPay;
# 2017-03-23
final class Url extends \Df\Payment\Url {
	/**
	 * 2016-08-27 The method returns a 2-tuple: the first element is for the test mode, the second is for the production mode.
	 * @override
	 * @see \Df\Payment\Url::stageNames()
	 * @used-by \Df\Payment\Url::url()
	 * @return string[]
	 */
	protected function stageNames():array {return ['-stage', ''];}
}