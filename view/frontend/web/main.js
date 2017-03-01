define([
	'df'
	,'df-lodash'
	,'Df_Core/my/redirectWithPost'
 	,'Df_Payment/custom'
	,'Dfe_AllPay/plan'
  	,'jquery'
], function(df, _, redirectWithPost, parent, Plan, $) {'use strict'; return parent.extend({
	defaults: {
		df: {
			test: {showBackendTitle: false},
			// 2016-08-06
			// @used-by mage2pro/core/Payment/view/frontend/web/template/item.html
			formTemplate: 'Dfe_AllPay/form'
		}
	},
	/**
	 * 2016-08-08
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/mixin.js
	 * @used-by mage2pro/core/Payment/view/frontend/web/mixin.js::getData()
	 * https://github.com/mage2pro/core/blob/2.0.21/Payment/view/frontend/web/mixin.js?ts=4#L208-L225
	 * @returns {Object}
	 */
	dfData: function() {return df.o.merge(this._super(), df.clean({
		option: this.option, plan: this.plan
	}));},
	/**
	 * 2016-08-17
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/mixin.js
	 * @used-by mage2pro/core/Payment/view/frontend/web/template/item.html
	 * @param {HTMLElement} element
	 * @param {Object} _this
	 */
	dfFormAfterRender: function(element, _this) {
		/** @type {jQuery} HTMLDivElement */
		var c = $('#payment');
		var f = _this.dfForm();
		var toggle = function() {f.toggleClass('df-wide', 575 <= c.width());};
		toggle();
		$(window).resize(toggle);
	},
	/**
	 * 2016-08-16
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/mixin.js
	 * @used-by dfFormCssClassesS()
	 * @returns {String[]}
	 */
	dfFormCssClasses: function() {return this._super().concat([
		this.needShowOptions() ? 'with-options' : null
		,this.hasPlans ? 'has-plans' : null
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
		return $.map(this.installment().plans, function(p) {return Plan({
			fee: parseFloat(p.fee), numPayments: parseInt(p.numPayments), rate: parseFloat(p.rate)
		}, rateToCurrent);});
	}),
	/**
	 * 2016-08-15
	 * 2017-03-01
	 * Результатом этого метода является предпочтение администратором
	 * стороны для запроса у покупателя варианта оплаты allPay или Magento.
	 * @returns {Boolean}
	 */
	needShowOptions: function() {return 'magento' === this.config('optionsLocation');},
	/**
	 * 2016-08-15
	 * 2017-03-01
	 * @used-by https://github.com/mage2pro/allpay/blob/1.1.32/view/frontend/web/template/one-off/simple.html?ts=4#L4
	 * @used-by https://github.com/mage2pro/allpay/blob/1.1.32/view/frontend/web/template/one-off/withOptions.html?ts=4#L2
	 * @returns {String}
	 */
	oneOff: function() {return df.t('One-off Payment: %s',
		this.dfc.formatMoney(this.dfc.grandTotal())
	);},
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
	 * Эта функция вернёт null только при выполнении сразу обоих следующих условий:
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
	 * 2016-08-06
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/mixin.js
	 * @used-by placeOrderInternal()
	 */
	onSuccess: function(json) {
		/** @type {Object} */
		var data = $.parseJSON(json);
		// 2016-07-10
		// @see \Dfe\AllPay\Method::getConfigPaymentAction()
		redirectWithPost(data.uri, data.params);
	},
	/**
	 * 2016-08-15
	 * @returns {Object}
	 */
	options: function() {return this.config('options');},
	/**
	 * 2016-08-15
	 * @returns {Object[]}
	 */
	optionsA: df.c(function() {return $.map(this.options(), function(label, v) {return {
		domId: 'df-option-' + v, label: label, value: v
	};});}),
	/**
	 * 2016-07-01
	 * @override
	 * @see https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Checkout/view/frontend/web/js/view/payment/default.js#L127-L159
	 * @used-by https://github.com/magento/magento2/blob/2.1.0/lib/web/knockoutjs/knockout.js#L3863
	*/
	placeOrder: function() {
		if (this.validate()) {
			// http://stackoverflow.com/a/8622351
			/** @type {?String} */
			var option = this.dfRadioValue('option');
			if (null !== option) {
				if (option.match(/\d+/)) {
					this.plan = option;
				}
				else if (this.needShowOptions()) {
					this.option = option;
				}
			}
			this.placeOrderInternal();
		}
	}
});});
