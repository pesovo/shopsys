(function ($) {

	SS6 = window.SS6 || {};

	SS6.ajax = function (options) {
		var loaderOverlayTimeout;
		var defaults = {
			loaderElement: 'body',
			loaderMessage: '',
			overlayDelay: 200,
			error: showDefaultError,
			complete: function () {}
		};
		var options = $.extend(defaults, options);
		var userCompleteCallback = options.complete;
		var $loaderOverlay = getLoaderOverlay(options.loaderMessage, options.loaderElement);
		var userErrorCallback = options.error;

		options.complete = function (jqXHR, textStatus) {
			userCompleteCallback.apply(this, [jqXHR, textStatus]);
			clearTimeout(loaderOverlayTimeout);
			$loaderOverlay.remove();
			$(options.loaderElement).removeClass('in-overlay');
		};

		options.error = function (jqXHR) {
			// on FireFox abort ajax request, but request was probably successful
			if (jqXHR.status !== 0) {
				userErrorCallback.apply(this, [jqXHR]);
			}
		};

		loaderOverlayTimeout = setTimeout(function () {
			showLoaderOverlay(options.loaderElement, $loaderOverlay);
		}, options.overlayDelay);
		$.ajax(options);
	};

	var getLoaderOverlay = function(loaderMessage, loaderElement) {
		var $loaderOverlayDiv = $('<div class="in-overlay__in"></div>');

		var overlaySpinnerClass = 'in-overlay__spinner';
		if (loaderElement !== 'body') {
			overlaySpinnerClass += ' in-overlay__spinner--absolute';
			$loaderOverlayDiv.addClass('in-overlay__in--absolute');
		}

		var $loaderOverlaySpinnerDiv = $($.parseHTML(
			'<div class="' + overlaySpinnerClass + '">' +
				'<span class="in-overlay__spinner__icon"></span>' +
				'<span class="in-overlay__spinner__message">' + loaderMessage + '</span>' +
			'</div>'
		));

		return $loaderOverlayDiv.append($loaderOverlaySpinnerDiv);
	};

	var showLoaderOverlay = function (loaderElement, $loaderOverlay) {
		$(loaderElement)
			.addClass('in-overlay')
			.append($loaderOverlay);
	};

	var showDefaultError = function () {
		SS6.window({
			content: SS6.translator.trans('Nastala chyba, zkuste to, prosím, znovu.')
		});
	};

})(jQuery);