import * as jQuery from 'jquery';

(function($) {
	const formPopover = function() {
		/**
		 * jQuery reference to the popover trigger container.
		 *
		 * @type {object}
		 */
		let container;

		/**
		 * Reference to the popover instance.
		 *
		 * @type {object}
		 */
		let popover;

		let popoverContainer;

		/**
		 * The settings of the current popover.
		 *
		 * @type {object}
		 */
		let settings;

		/**
		 * Shows the current popover and updates the form values with the required data.
		 */
		const showPopover = function() {
			popover.popover('show');
			popover.addClass(settings.classNamePrefix + 'is-open');

			container.addClass(settings.classNames.containerHightlightClass);
		};

		const onPopoverShown = () => {
			const entityId = container.find('.' + settings.classNamePrefix + settings.classNames.identifier).attr(settings.identifierAttribute);
			popoverContainer.find('.' + settings.classNamePrefix + settings.classNames.identifierInput).val(entityId);

			const closeButton = popoverContainer.find('.' + settings.classNamePrefix + settings.classNames.closeButton);
			closeButton.click(function(e) {
				e.preventDefault();
				closePopover(container, popover)
			});

			popoverContainer.find('.' + settings.classNamePrefix + settings.classNames.submitButton).focus();
		}

		/**
		 * Closes the current popover.
		 */
		const closePopover = function() {
			container.removeClass(settings.classNames.containerHightlightClass);

			popover.popover('hide');
			popover.removeClass(settings.classNamePrefix + 'is-open');
		};

		/**
		 * Returns the content that should be displayed in the popover.
		 *
		 * @returns {string}
		 */
		const getPopoverContent = function() {
			console.log('getting');
			return $('.' + settings.classNamePrefix + settings.classNames.popoverContentContainer).html();
		};

		/**
		 * Shows or hides the popover depending on its current state.
		 */
		const togglePopover = function() {
			if (container.find('.' + settings.classNamePrefix + 'is-open').length) {
				closePopover();
			} else {
				showPopover();
			}
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
			popoverContainer = $('#main-popover-container');

			const popoverTrigger = container.find('.' + settings.classNamePrefix + settings.classNames.popoverTrigger);

			const popoverSettings = $.extend(
				{
					content: getPopoverContent,
				},
				settings.popover
			);

			const originalTitle = popoverTrigger.attr('title');
			popover = popoverTrigger.popover(popoverSettings);
			popover.on('shown.bs.popover', onPopoverShown);

			// Somehow the popover kills the title from the title attribute.
			// This is why we restore it after the popover is initialized.
			popoverTrigger.attr('title', originalTitle);

			popoverTrigger.click(function(e) {
				console.log('clicking');
				e.preventDefault();
				togglePopover();
			});
		};
	};

	$.fn.formPopover = function(options) {
		const settings = {
			classNamePrefix: 'de-swebhosting-bootstrap-form-popover-',
			identifierAttribute: 'data-de-swebhosting-bootstrap-form-popover-identifier',
			popover: {
				html: true,
				sanitize: false,
				placement: 'left',
				trigger: 'manual',
				container: '#main-popover-container',
			},
			classNames: {
				identifier: 'identifier',
				identifierInput: 'input',
				popoverTrigger: 'trigger',
				popoverContentContainer: 'content',
				closeButton: 'close',
				submitButton: 'submit',
				containerHightlightClass: 'table-danger'
			}
		};

		$.extend(
			true,
			settings,
			options
		);

		return this.each(function() {
			const popover = new formPopover();
			popover.initialize($(this), settings);
		});
	}

}(jQuery));
