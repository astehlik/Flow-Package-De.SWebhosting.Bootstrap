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
class JqueryViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Resource\Publishing\ResourcePublisher
	 */
	protected $resourcePublisher;

	/**
	 * @Flow\Inject(setting="useUncompressedFiles", package="De.SWebhosting.Bootstrap")
	 * @var bool
	 */
	protected $useUncompressedFiles;

	/**
	 * @param string $version The version that should be included.
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	public function render($version = '2.1.0') {
		$fileName = sprintf('jquery-%s', $version);
		$fileName .= $this->useUncompressedFiles ? '.js' : '.min.js';
		$uri = $this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/De.SWebhosting.Bootstrap/Vendor/JQuery/' . $fileName;
		$content = '<script type="text/javascript" src="' . $uri . '"></script>';
		return $content;
	}
} 