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
class JqueryUiViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

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
	 * @param string $type Must be set to "css" or "js".
	 * @param bool $custom If true ".custom" will be appended to the filename (defaults to TRUE).
	 * @param string $theme The theme that should be used (default is black-tie).
	 * @param string $version The version that should be included.
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	public function render($type, $custom = TRUE, $theme = 'black-tie', $version = '1.10.4') {

		$fileName = sprintf('jquery-ui-%s%s', $version, $custom ? '.custom' : '');
		$fileName .= $this->useUncompressedFiles ? '.' . $type : '.min.' . $type;
		$theme = $type === 'css' ? '/' . $theme : '';
		$uri = $this->resourcePublisher->getStaticResourcesWebBaseUri() . 'Packages/De.SWebhosting.Bootstrap/Vendor/JQuery/Ui/' . $type . $theme . '/' . $fileName;

		if ($type === 'css') {
			$content = '<link href="' . $uri . '" rel="stylesheet" media="all" type="text/css" />';
		} elseif ($type === 'js') {
			$content = '<script type="text/javascript" src="' . $uri . '"></script>';
		} else {
			throw new \InvalidArgumentException('The type must either be set to "css" or to "javascript".');
		}

		return $content;
	}
} 