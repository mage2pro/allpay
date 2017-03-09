<?php
// 2017-01-02
namespace Dfe\AllPay;
use Dfe\AllPay\Webhook as W;
final class WebhookF extends \Df\Payment\WebhookF {
	/**
	 * 2017-01-02
	 * @override
	 * @see \Df\Payment\WebhookF::_class()
	 * @used-by \Df\Payment\WebhookF::i()
	 * @return string
	 * @throws \Df\Core\Exception
	 */
	protected function _class() {return df_con($this->module(), df_cc_class('Webhook',
		$this->assertType($this->extra('class', W::classSuffixS($this->req(self::KEY_TYPE))))
	));}

	/**
	 * 2017-01-04
	 * @used-by _class()
	 * @used-by \Dfe\AllPay\Webhook::type()
	 */
	const KEY_TYPE = 'PaymentType';
}