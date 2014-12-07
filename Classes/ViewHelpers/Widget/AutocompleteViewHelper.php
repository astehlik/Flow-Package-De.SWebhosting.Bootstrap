<?php
namespace De\SWebhosting\Bootstrap\ViewHelpers\Widget;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package                          *
 * "De.SWebhosting.Bootstrap"                                             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\Widget\AbstractWidgetViewHelper;

/**
 * Usage:
 * <f:input id="name" ... />
 * <f:widget.autocomplete for="name" objects="{posts}" searchProperty="author">
 */
class AutocompleteViewHelper extends AbstractWidgetViewHelper {

	/**
	 * @var boolean
	 */
	protected $ajaxWidget = TRUE;

	/**
	 * @Flow\Inject
	 * @var \De\SWebhosting\Bootstrap\ViewHelpers\Widget\Controller\AutocompleteController
	 */
	protected $controller;

	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('objects', 'QueryResultInterface', 'Query result which will be used to search for autocomplete items.', TRUE);
		$this->registerArgument('searchProperty', 'string', 'The value the user types in will be search in this property.', TRUE);
		$this->registerArgument('id', 'string', 'The id of the container which will contain the autocomplete data.', TRUE);
		$this->registerArgument('maxItems', 'integer', 'The maximum number of items returned for display in the autocomplete widget.', FALSE, 10);
	}

	/**
	 * Renders a container with information for the autocomplete widget.
	 *
	 * @return string
	 */
	public function render() {
		return $this->initiateSubRequest();
	}
}
