<?php
namespace De\SWebhosting\Bootstrap\ViewHelpers\Resource;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package                          *
 * "De.SWebhosting.Bootstrap".                                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Resource\ResourceManager;

/**
 * Renders a public resource URI for a given file in a given collection.
 */
class CollectionUriViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @Flow\Inject
	 * @var ResourceManager
	 */
	protected $resourceManager;

	/**
	 * @param string $collectionName The name of the collection for which the URI should be rendered.
	 * @param string $path The path to the file within the collection for which the URI should be rendered.
	 * @return string
	 */
	public function render($collectionName, $path) {
		$collection = $this->resourceManager->getCollection($collectionName);
		return $collection->getTarget()->getPublicStaticResourceUri($path);
	}
}