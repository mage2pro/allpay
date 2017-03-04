// 2016-08-06
define([
	'df', 'df-lodash', 'Df_Checkout/js/data', 'jquery'
], function(df, _, dfc, $) {'use strict'; return(
	/**
	 * 2016-08-06
	 * @param {Object} plan
	 * @param {Number} plan.fee
	 * @param {Number} plan.numPayments
	 * @param {Number} plan.rate
	 * @param {Number} rateToCurrent
	 * @returns {Object}
	 */
	function(plan, rateToCurrent) {return {
	/** @returns {Number} */
	amount: df.c(function() {return Math.round(
		dfc.grandTotal() * (1 + plan.rate / 100) + plan.fee * rateToCurrent * plan.numPayments
	);}),
	/** @returns {String} */
	amountS: function() {return dfc.formatMoney(this.amount());},
	/** @returns {String} */
	domId: function() {return 'df-plan-' + plan.numPayments;},
	/**
	 * 2016-08-08
	 * В документации сказано, что если общий размер оплаты
	 * не делится нацело на количество платежей, то остаток переносится на первый платёж:
	 * «串接時請帶訂單的交易總金額，無須自行計算各分期金額，除不盡的金額銀行會於第一期收取。
	 * 舉例：總金額 1733元 分 6 期，除不盡的放第一期，293，288，288，288，288，288»
	 * @returns {Number}
	 */
	firstPayment: df.c(function() {
		/** @type {Number} */
		var remainder = this.amount() % plan.numPayments;
		/** @type {Number} */
		var singlePaymentAmount = Math.floor(this.amount() / plan.numPayments);
		return remainder + singlePaymentAmount;
	}),
	/** @returns {String} */
	firstPaymentS: function() {return dfc.formatMoney(this.firstPayment());},
	/**
	 * 2016-08-12
	 * @returns {Number}
	 */
	numPayments: plan.numPayments,
	/**
	 * 2016-08-09
	 * «How to hadle a click event for a Knockout template's element with the «click» binding?»
	 * https://mage2.pro/t/1937
	 * @see mage2pro/allpay/view/frontend/web/template/installment.html
	 * How to check a radio button:  http://stackoverflow.com/a/5665942
	 * @param {Object} _this
	 * @param {jQuery.Event} event
	 */
	onRowClicked: function(_this, event) {
		$(':radio', event.currentTarget).prop('checked', true);
		/**
		 * 2016-08-12
		 * Возврат true приводит к последующей обработке события обработчиком по умолчанию.
		 * http://knockoutjs.com/documentation/click-binding.html#note-3-allowing-the-default-click-action
		 * Опытным путём установил, что если этого не делать,
		 * то при клике непосредственно по радиокнопке радиокнопка не будет выбрана
		 * (выбор сначала происходит, а потом сбрасывается).
		 */
		return true;
	}
};});});
