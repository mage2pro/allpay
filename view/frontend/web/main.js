// 2016-08-04
define([
	'df', 'df-lodash', 'Df_Payment/withOptions', 'Dfe_AllPay/plan', 'jquery'
], function(df, _, parent, Plan, $) {'use strict';
/** 2017-09-06 @uses Class::extend() https://github.com/magento/magento2/blob/2.2.0-rc2.3/app/code/Magento/Ui/view/base/web/js/lib/core/class.js#L106-L140 */
return parent.extend({
	// 2016-08-06
	// @used-by Df_Payment/main
	// https://github.com/mage2pro/core/blob/2.4.21/Payment/view/frontend/web/template/main.html#L36-L38
	defaults: {df: {formTemplate: 'Dfe_AllPay/form'}},
	/**
	 * 2016-08-17
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/mixin.js
	 * @used-by Df_Payment/main
	 * https://github.com/mage2pro/core/blob/2.0.36/Payment/view/frontend/web/template/main.html?ts=4#L33
	 * @param {HTMLElement} element
	 * @param {Object} _this
	 */
	dfFormAfterRender: function(element, _this) {
		var t = function() {_this.dfForm().toggleClass('df-wide', 575 <= $('#payment').width());};
		t();
		$(window).resize(t);
	},
	/**
	 * 2016-08-16
	 * @override
	 * @see Df_Payment/mixin::dfFormCssClasses()
	 * https://github.com/mage2pro/core/blob/2.0.25/Payment/view/frontend/web/mixin.js?ts=4#L165
	 * @used-by Df_Payment/mixin::dfFormCssClassesS()
	 * https://github.com/mage2pro/core/blob/2.0.25/Payment/view/frontend/web/mixin.js?ts=4#L171
	 * @returns {String[]}
	 */
	dfFormCssClasses: function() {return this._super().concat([
		this.needShowOptions() ? 'with-options' : null, this.hasPlans() ? 'has-plans' : null
	]);},
	/**
	 * 2016-08-15
	 * @returns {Boolean}
	 */
	hasPlans: function() {return !!this.iPlans().length;},
	/**
	 * 2016-08-04
	 * @returns {Object}
	 */
	installment: function() {return this.config('installment');},
	/**
	 * 2016-08-04
	 * @returns {Object[]}
	 */
	iPlans: df.c(function() {
		/** @type {Number} */
		var rateToCurrent = this.config('currencyRateFromBaseToCurrent');
		var option = this.option;
		return $.map(this.installment().plans, function(p) {return Plan({
			fee: parseFloat(p.fee)
			,numPayments: df.int(p.numPayments)
			,option: option
			,rate: parseFloat(p.rate)
		}, rateToCurrent);});
	}),
	/**
	 * 2016-08-15
	 * 2017-03-01
	 * Результатом этого метода является предпочтение администратором
	 * стороны для запроса у покупателя варианта оплаты allPay или Magento.
	 * @returns {Boolean}
	 */
	needShowOptions: function() {return this.config('needShowOptions');},
	/**
	 * 2016-08-15
	 * 2017-03-01
	 * @used-by https://github.com/mage2pro/allpay/blob/1.1.32/view/frontend/web/template/one-off/simple.html?ts=4#L4
	 * @used-by https://github.com/mage2pro/allpay/blob/1.1.32/view/frontend/web/template/one-off/withOptions.html?ts=4#L2
	 * @returns {String}
	 */
	oneOff: function() {return df.t('One-off Payment: %s', this.dfc.formatMoney(this.dfc.grandTotal()));},
	/**
	 * 2016-08-15
	 * 2017-03-01
	 * Этот метод используется в том случае,
	 * когда администратор предпочёл выбор покупателем варианта оплаты
	 * на странице allPay, а не на странице Magento.
	 * В этом случае мы информируем покупателя о доступных вариантах оплаты простым текстом,
	 * без элементов управления.
	 * @used-by https://github.com/mage2pro/allpay/blob/1.1.32/view/frontend/web/template/one-off/simple.html?ts=4#L7-L12
	 * @returns {String}
	 */
	oneOffOptions: function() {return df.t('The following payment options are available: %s.',
	   _.values(this.options()).join(', ')
	);},
	/**
	 * 2016-08-15
	 * 2017-03-01
	 * Этот метод вернёт null только при выполнении сразу обоих следующих условий:
	 * 1) Администратор предпочёл выбор покупателем варианта оплаты
	 * на странице allPay, а не на странице Magento.
	 * 2) Оплата в рассрочку отключена.
	 * @returns {?String}
	 */
	oneOffTemplate: df.c(function() {
		/** @type {String} */
		var s = this.needShowOptions() ? 'withOptions' : (this.hasPlans() ? 'simple' : null);
		return !s ? null : 'Dfe_AllPay/one-off/' + s;
	}),
	/**
	 * 2017-03-05
	 * @override
	 * @see Df_Payment/withOptions::postProcessOption()
	 * @used-by Df_Payment/withOptions::dfData()
	 * Значение «undefined» задано в шаблоне Dfe_AllPay/one-off/simple.
	 * @used-by dfData()
	 * @param {String} option
	 * @returns {?String}
	 */
	postProcessOption: function(option) {return 'undefined' === option ? null : option;}
});});
