<?php
// 2017-01-02
namespace Dfe\AllPay;
use Df\Core\Exception as DFE;
use Dfe\AllPay\Webhook as W;
class WebhookF extends \Df\Payment\WebhookF {
	/**
	 * 2017-01-02
	 * @override
	 * @see \Df\Payment\WebhookF::_class()
	 * @used-by \Df\Payment\WebhookF::i()
	 * @param string|object $module
	 * @param array(string => mixed) $req
	 * @param array(string => mixed) $extra [optional]
	 * @return string
	 * @throws DFE
	 */
	final protected function _class($module, array $req, array $extra = []) {
		/** @var string|null $s */
		$s = dfa($extra, 'class', W::classSuffixS(dfa($req, 'PaymentType')));
		return $s ? df_con($module, df_cc_class('Webhook', $s)) : df_error('The request is invalid.');
	}
}