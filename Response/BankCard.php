<?php
namespace Dfe\AllPay\Response;
use Dfe\AllPay\Source\Method as SMethod;
class BankCard extends \Dfe\AllPay\Response {
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
		df_assert_eq(SMethod::BANK_CARD, $codeFirst);
		return df_ccc(' ', parent::paymentOptionTitleByCode($codeFirst, $codeLast),
			!$this->isInstallment() ? '' : '(Installments)'
		);
	}

	/**
	 * 2016-08-09
	 * @return bool
	 */
	private function isInstallment() {return !!$this['stage'];}
}

