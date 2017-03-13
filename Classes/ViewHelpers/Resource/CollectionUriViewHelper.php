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

use Neos\Flow\Annotations as Flow;

/**
 * Renders a public resource URI for a given file in a given collection.
 */
class CollectionUriViewHelper extends \Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @Flow\Inject
     * @var \Neos\Flow\ResourceManagement\ResourceManager
     */
    protected $resourceManager;

    /**
     * @param string $collectionName The name of the collection for which the URI should be rendered.
     * @param string $path The path to the file within the collection for which the URI should be rendered.
     * @return string
     */
    public function render($collectionName, $path)
    {
        $collection = $this->resourceManager->getCollection($collectionName);

        if (!isset($collection)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The collection %s was not found. Please make sure you have configured this collection before using it.',
                    $collectionName
                ), 1428397307
            );
        }

        return $collection->getTarget()->getPublicStaticResourceUri($path);
    }
}
