<?php
declare(strict_types=1);

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

use De\SWebhosting\Bootstrap\ViewHelpers\Widget\Controller\PaginateController;
use Neos\Flow\Annotations as Flow;

/**
 * Changes the look of the default Fluid pagination widget
 */
class PaginateViewHelper extends \Neos\FluidAdaptor\ViewHelpers\Widget\PaginateViewHelper
{
    /**
     * @Flow\Inject
     * @var PaginateController
     */
    protected $controller;
}
