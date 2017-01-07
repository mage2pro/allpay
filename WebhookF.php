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
	 * @return string
	 * @throws DFE
	 */
	final protected function _class() {
		/** @var string|null $s */
		$s = $this->extra('class', W::classSuffixS($this->req(self::KEY_TYPE)));
		$this->assertType($s);
		return df_con($this->module(), df_cc_class('Webhook', $s));
	}

	/**
	 * 2017-01-04
	 * @used-by _class()
	 * @used-by \Dfe\AllPay\Webhook::type()
	 */
	const KEY_TYPE = 'PaymentType';
}