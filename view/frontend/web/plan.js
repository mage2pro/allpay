// 2016-08-06
define (['df', 'df-lodash', 'Df_Checkout/js/data', 'jquery'], function(
	df, _, dfc, $
) {'use strict'; return (
	/**
	 * 2016-08-06
	 * @param {Object} plan
	 * @param {Number} plan.fee
	 * @param {Number} plan.months
	 * @param {Number} plan.rate
	 * @param {Number} rateToCurrent
	 * @returns {Object}
	 */
	function(plan, rateToCurrent) {return {
	/** @returns {Number} */
	amount: df.c(function() {return Math.round(
		dfc.grandTotal() * (1 + plan.rate / 100) + plan.fee * rateToCurrent * this.numPayments()
	);}),
	/** @returns {String} */
	amountS: function() {return dfc.formatMoney(this.amount());},
	/** @returns {String} */
	domId: function() {return 'df-plan-' + plan.months;},
	/** @returns {String} */
	duration: function() {return df.t(1 === this.months ? '1 month' : '%s months', this.months);},
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
		var remainder = this.amount() % this.numPayments();
		/** @type {Number} */
		var singlePaymentAmount = Math.floor(this.amount() / this.numPayments());
		return remainder + singlePaymentAmount;
	}),
	/** @returns {String} */
	firstPaymentS: function() {return dfc.formatMoney(this.firstPayment());},
	months: plan.months,
	/**
	 * 2016-08-07
	 * Добавляем 1 к months, потому что months означает количество месяцев,
	 * а платежей на 1 больше, чем месяцев.
	 * @returns {Number}
	 */
	numPayments: function() {return 1 + plan.months;},
	/**
	 * 2016-08-09
	 * «How to hadle a click event for a Knockout template's element with the «click» binding?»
	 * https://mage2.pro/t/1937
	 * @see mage2pro/allpay/view/frontend/web/template/installment.html
	 * How to check a radio button:  http://stackoverflow.com/a/5665942
	 * @param {Object} _this
	 * @param {jQuery.Event} event
	 */
	onRowClicked: function(_this, event) {$(':radio', event.currentTarget).prop('checked', true);}
};});});
