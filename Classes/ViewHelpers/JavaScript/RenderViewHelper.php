<?php
declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\ViewHelpers\JavaScript;

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

use De\SWebhosting\Bootstrap\Utility\JavaScriptContainer;
use Neos\Flow\Annotations as Flow;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;

/**
 * Renders JavaScript code that was registered for the given section.
 */
class RenderViewHelper extends AbstractViewHelper
{
    /**
     * We render HTML code and to not want it to be escaped.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @var JavaScriptContainer
     * @Flow\Inject
     */
    protected $javascriptContainer;

    /**
     * Renders JavaScript code that was registered for the given section.
     *
     * @param string $section
     * @return string
     */
    public function render($section = 'footer')
    {
        return $this->javascriptContainer->getSectionContent($section);
    }
}
