<?php
namespace De\SWebhosting\Bootstrap\ViewHelpers\Load;

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

/**
 * Renders include statements for JQuery UI CSS / JavaScript.
 */
class JavaScriptLibrariesViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Disable escaping, we print HTML.
	 *
	 * @var bool
	 */
	protected $escapeOutput = FALSE;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Resource\ResourceManager
	 */
	protected $resourceManager;

	/**
	 * @return string
	 */
	public function render() {
		$fileName = 'TableFormPopover.js';
		$uri = $this->resourceManager->getPublicPackageResourceUri('De.SWebhosting.Bootstrap', 'JavaScript/' . $fileName);
		$content = '<script type="text/javascript" src="' . $uri . '"></script>';
		return $content;
	}
}