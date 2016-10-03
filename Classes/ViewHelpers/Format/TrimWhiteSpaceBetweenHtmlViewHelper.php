<?php
namespace De\SWebhosting\Bootstrap\ViewHelpers\Format;

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

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * This view helper removes the whitespace between all HTML tags
 * that are rendered in its children.
 */
class TrimWhiteSpaceBetweenHtmlViewHelper extends AbstractViewHelper
{
    /**
     * We render HTML code and to not want it to be escaped.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Removes whitespace in the rendered child HTML using a regular expression.
     *
     * @return string
     */
    public function render()
    {
        $result = $this->renderChildren();
        return preg_replace('~>\s+<~', '><', $result);
    }
}
