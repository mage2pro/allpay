<?php
namespace Dfe\AllPay\Response;
use Dfe\AllPay\Source\Option;
class BankCard extends \Dfe\AllPay\Response {
	/**
	 * 2016-08-09
	 * @used-by \Dfe\AllPay\Response\BankCard::paymentOptionTitleByCode()
	 * @used-by \Dfe\AllPay\Block\Info\BankCard::custom()
	 * @return bool
	 */
	public function isInstallment() {return !!$this['stage'];}

	/**
	 * 2016-07-20
	 * @override
	 * @see \Df\Payment\R\Response::needCapture()
	 * @used-by \Df\Payment\R\Response::handle()
	 * @return bool
	 */
	protected function needCapture() {return true;}

	/**
	 * 2016-08-09
	 * @override
	 * @see \Dfe\AllPay\Response::paymentOptionTitleByCode()
	 * @used-by \Dfe\AllPay\Response::paymentOptionTitle()
	 * @param string $codeFirst
	 * @param string $codeLast
	 * @return string|null
	 */
	protected function paymentOptionTitleByCode($codeFirst, $codeLast) {
		df_assert_eq(Option::BANK_CARD, $codeFirst);
		return df_cc_s(parent::paymentOptionTitleByCode($codeFirst, $codeLast),
			!$this->isInstallment() ? '' : '(Installments)'
		);
	}
}

