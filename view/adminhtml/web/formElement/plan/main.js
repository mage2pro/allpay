// 2016-07-30
define(['jquery', 'domReady!'], function($) {return (
	/**
	 * @param {Object} config
	 * @param {String} config.id
	 */
	function(config) {
		var prepare = function($item) {
		};
		/** @type {jQuery} HTMLFieldSetElement */
		var $element = $(document.getElementById(config.id));
		$element.hasClass('df-name-template')
			// https://github.com/mage2pro/core/tree/b9f6c2f1c33bfdfd033f8c20d63afa4c54167d99/Framework/view/adminhtml/web/formElement/array/main.js#L115
			? $(window).bind('df.config.array.add', function(event, $item) {prepare($item);})
			: prepare($element)
		;
	}
);});