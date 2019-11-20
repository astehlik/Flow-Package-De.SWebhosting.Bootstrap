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

use De\SWebhosting\Bootstrap\Utility\PointcutUtility;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionRequest;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractTagBasedViewHelper;
use RuntimeException;

/**
 * This view helper renders a configurable HTML tag and if the current
 * controller / action matches the configured active controller / action
 * the configured active class will be added to the class property of
 * the HTML element.
 *
 * @Flow\Scope("prototype")
 */
class MenuItemViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @Flow\Inject
     * @var PointcutUtility
     */
    protected $pointcutUtility;

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

    /**
     * Checks if the given controller / action name matches the current
     * controller / action.
     *
     * @param string $activeControllerActionFilter
     * @return bool
     */
    protected function matchCurrentControllerAction($activeControllerActionFilter)
    {
        $request = $this->controllerContext->getRequest();
        if (!$request instanceof ActionRequest) {
            throw new RuntimeException(
                'The MenuItemViewHelper only works in \\TYPO3\\Flow\\Mvc\\ActionRequest context.', 1425850365
            );
        }

        return $this->pointcutUtility->matchControllerActionMethod($activeControllerActionFilter, $request);
    }
}
