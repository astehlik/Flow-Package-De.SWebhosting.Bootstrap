<?php
namespace De\SWebhosting\Bootstrap\ViewHelpers\JavaScript;

/*                                                                        *
 * This script belongs to the FLOW3 package "Bootstrap".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class RenderViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \De\SWebhosting\Bootstrap\ViewHelpers\JavaScript\JavaScriptContainer
	 * @Flow\Inject
	 */
	protected $javascriptContainer;

	/**
	 * Render method.
	 */
	public function render($section = 'footer') {
		return $this->javascriptContainer->getSectionContent($section);
	}
}

?>
