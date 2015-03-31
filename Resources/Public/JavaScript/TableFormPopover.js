(function($) {

	var formPopover = function() {

		/**
		 * jQuery reference to the popover trigger container.
		 *
		 * @type {object}
		 */
		var container;

		/**
		 * Reference to the popover instance.
		 *
		 * @type {object}
		 */
		var popover;

		/**
		 * The settings of the current popover.
		 *
		 * @type {object}
		 */
		var settings;

		/**
		 * Returns the content that should be displayed in the popover.
		 *
		 * @returns {string}
		 */
		var getPopoverContent = function() {
			return $('.' + settings.classNamePrefix + settings.classNames.popoverContentContainer).html();
		};

		/**
		 * Shows or hides the popover depending on its current state.
		 */
		var togglePopover = function() {

			if (container.find('.' + settings.classNamePrefix + 'is-open').length) {
				closePopover();
			} else {
				showPopover();
			}
		};

		/**
		 * Closes the current popover.
		 */
		var closePopover = function() {

			container.removeClass(settings.classNames.containerHightlightClass);

			popover.popover('hide');
			popover.removeClass(settings.classNamePrefix + 'is-open');
		};

		/**
		 * Shows the current popover and updates the form values with the required data.
		 */
		var showPopover = function() {

			popover.popover('show');
			popover.addClass(settings.classNamePrefix + 'is-open');

			var entityId = container.find('.' + settings.classNamePrefix + settings.classNames.identifier).attr(settings.identifierAttribute);
			container.find('.' + settings.classNamePrefix + settings.classNames.identifierInput).val(entityId);

			var closeButton = container.find('.' + settings.classNamePrefix + settings.classNames.closeButton);
			closeButton.click(function(e) {
				e.preventDefault();
				closePopover(container, popover)
			});

			container.find('.' + settings.classNamePrefix + settings.classNames.submitButton).focus();

			container.addClass(settings.classNames.containerHightlightClass);
		};

		/**
		 * Initializes the popover.
		 *
		 * @param {object} containerParam
		 * @param {object} settingsParam
		 */
		this.initialize = function(containerParam, settingsParam) {

			container = containerParam;
			settings = settingsParam;

			var popoverTrigger = container.find('.' + settings.classNamePrefix + settings.classNames.popoverTrigger);

			var popoverSettings = $.extend(
				{
					content: getPopoverContent()
				},
				settings.popover
			);

			var originalTitle = popoverTrigger.attr('title');
			popover = popoverTrigger.popover(popoverSettings);

			// Somehow the popover kills the title from the title attribute.
			// This is why we restore it after the popover is initialized.
			popoverTrigger.attr('title', originalTitle);

			popoverTrigger.click(function(e) {
				e.preventDefault();
				togglePopover();
			});
		};
	};

	$.fn.formPopover = function(options) {

		var settings = {
			classNamePrefix: 'de-swebhosting-bootstrap-form-popover-',
			identifierAttribute: 'data-de-swebhosting-bootstrap-form-popover-identifier',
			popover: {
				html: true,
				placement: 'left',
				trigger: 'manual'
			},
			classNames: {
				identifier: 'identifier',
				identifierInput: 'input',
				popoverTrigger: 'trigger',
				popoverContentContainer: 'content',
				closeButton: 'close',
				submitButton: 'submit',
				containerHightlightClass: 'danger'
			}
		};

		$.extend(
			true,
			settings,
			options
		);

		return this.each(function() {
			var popover = new formPopover();
			popover.initialize($(this), settings);
		});
	}

}(jQuery));