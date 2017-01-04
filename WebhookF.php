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
		$s = dfa($extra, 'class', W::classSuffixS(dfa($req, self::KEY_TYPE)));
		return $s ? df_con($module, df_cc_class('Webhook', $s)) : df_error('The request is invalid.');
	}

	/**
	 * 2017-01-04
	 * @used-by _class()
	 * @used-by \Dfe\AllPay\Webhook::type()
	 */
	const KEY_TYPE = 'PaymentType';
}