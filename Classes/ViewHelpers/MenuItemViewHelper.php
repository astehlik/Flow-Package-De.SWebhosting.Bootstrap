<?php
declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\ViewHelpers;

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

use De\SWebhosting\Bootstrap\Utility\ControllerActionMatchingTrait;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * This view helper renders a configurable HTML tag and if the current
 * controller / action matches the configured active controller / action
 * the configured active class will be added to the class property of
 * the HTML element.
 */
class MenuItemViewHelper extends AbstractTagBasedViewHelper
{
    use ControllerActionMatchingTrait;

    /**
     * Initialize all arguments. You need to override this method and call
     * $this->registerArgument(...) inside this method, to register all your arguments.
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('activeControllerActionFilter', 'string', '', false, null);
        $this->registerArgument('activeClass', 'string', '', false, 'active');
        $this->registerArgument('tagName', 'string', '', false, 'li');
        $this->registerUniversalTagAttributes();
    }

    /**
     * Renders the HTML element and adds an additional active class if
     * controller / action is matching.
     *
     * @return string
     */
    public function render()
    {
        $activeControllerActionFilter = $this->arguments['activeControllerActionFilter'];
        $activeClass = $this->arguments['activeClass'];
        $tagName = $this->arguments['tagName'];

        $class = '';

        if ($this->hasArgument('class') && $this->arguments['class'] !== '') {
            $class = trim($this->arguments['class']);
        }

        if ($this->matchCurrentControllerAction($activeControllerActionFilter)) {
            $class = ($class !== '') ? $class . ' ' . $activeClass : $activeClass;
        }

        $this->tag->setTagName($tagName);
        $this->tag->setContent($this->renderChildren());
        $this->tag->addAttribute('class', $class);
        return $this->tag->render();
    }
}
