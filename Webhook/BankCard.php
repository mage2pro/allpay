<?php
namespace Dfe\AllPay\Webhook;
use Dfe\AllPay\Source\Option;
class BankCard extends \Dfe\AllPay\Webhook {
	/**
	 * 2016-08-09
	 * @used-by \Dfe\AllPay\Webhook\BankCard::typeLabelByCode()
	 * @used-by \Dfe\AllPay\Block\Info\BankCard::custom()
	 * @return bool
	 */
	public function isInstallment() {return !!$this->req('stage');}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\PaypalClone\Confirmation::needCapture()
	 * @used-by \Df\PaypalClone\Confirmation::_handle()
	 * @return bool
	 */
	protected function needCapture() {return true;}

	/**
	 * 2016-08-09
	 * @override
	 * @see \Dfe\AllPay\Webhook::typeLabelByCode()
	 * @used-by \Dfe\AllPay\Webhook::typeLabel()
	 * @param string $codeFirst
	 * @param string $codeLast
	 * @return string|null
	 */
	final protected function typeLabelByCode($codeFirst, $codeLast) {return df_cc_s(
		parent::typeLabelByCode(df_assert_eq(Option::BANK_CARD, $codeFirst), $codeLast)
		,!$this->isInstallment() ? '' : '(Installments)'
	);}
}