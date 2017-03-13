<?php
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

use Neos\Flow\Annotations as Flow;

/**
 * Appends JavaScript code to a section.
 */
class AppendViewHelper extends \Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @var \De\SWebhosting\Bootstrap\Utility\JavaScriptContainer
     * @Flow\Inject
     */
    protected $javascriptContainer;

    /**
     * Appends JavaScript code to a section.
     *
     * @param string $script
     * @param string $src
     * @param string $section
     * @throws \Neos\Flow\Mvc\Exception\RequiredArgumentMissingException
     */
    public function render($script = null, $src = null, $section = 'footer')
    {
        if (isset($script)) {
            $this->javascriptContainer->appendScriptToSection($script, null, $section);
        } else {
            if (isset($src)) {
                $this->javascriptContainer->appendSrcToSection($src, null, $section);
            } else {
                throw new \Neos\Flow\Mvc\Exception\RequiredArgumentMissingException(
                    'Either "src" or "script" is required as an argument for this view helper.'
                );
            }
        }
    }
}
