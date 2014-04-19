(function($) {

	var formPopover = function() {

		var settings;

		var getPopoverContent = function() {
			return $('.' + settings.classNamePrefix + settings.classNames.popoverContentContainer).html();
		};

		var toggleDeletePopover = function(tableLine, popover, settings) {

			if (tableLine.find('.' + settings.classNamePrefix + 'is-open').length) {
				closePopover(popover);
			} else {
				showDeletePopover(tableLine, popover);
			}
		};

		var closePopover = function(popover) {
			popover.popover('hide');
			popover.removeClass(settings.classNamePrefix + 'is-open');
		};

		var showDeletePopover = function(tableLine, popover) {

			popover.popover('show');
			popover.addClass(settings.classNamePrefix + 'is-open');

			var entityId = tableLine.find('.' + settings.classNamePrefix + settings.classNames.identifier).attr(settings.identifierAttribute);
			tableLine.find('.' + settings.classNamePrefix + settings.classNames.identifierInput).val(entityId);

			var closeButton = tableLine.find('.' + settings.classNamePrefix + settings.classNames.closeButton);
			closeButton.click(function(e) {
				e.preventDefault();
				closePopover(popover)
			});

			tableLine.find('.' + settings.classNamePrefix + settings.classNames.submitButton).focus();
		};

		this.initialize = function(tableLine, globalSettings) {

			settings = globalSettings;

			var popoverTrigger = tableLine.find('.' + settings.classNamePrefix + settings.classNames.popoverTrigger);

			var popoverSettings = $.extend(
				{
					content: getPopoverContent()
				},
				settings.popover
			);
			var popover = popoverTrigger.popover(popoverSettings);

			popoverTrigger.click(function(e) {
				e.preventDefault();
				toggleDeletePopover(tableLine, popover, settings);
			});
		};
	};

	$.fn.tableFormPopover = function(options) {

		// This is the easiest way to have default options.
		var settings = $.extend(
			{
				classNamePrefix: 'de-swebhosting-bootstrap-table-popover-',
				identifierAttribute: 'data-de-swebhosting-bootstrap-table-popover-identifier',
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
					submitButton: 'submit'
				}
			},
			options
		);

		return this.each(function() {
			var popover = new formPopover();
			popover.initialize($(this), settings);
		});
	}

}(jQuery));