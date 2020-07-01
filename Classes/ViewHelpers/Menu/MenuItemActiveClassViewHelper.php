<?php
declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\ViewHelpers\Menu;

use De\SWebhosting\Bootstrap\Utility\ControllerActionMatchingTrait;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;

class MenuItemActiveClassViewHelper extends AbstractViewHelper
{
    use ControllerActionMatchingTrait;

    /**
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('activeControllerActionFilter', 'string', '', false, null);
        $this->registerArgument('activeClass', 'string', '', false, 'active');
        $this->registerArgument('variableName', 'string', '', false, 'menuItemActiveClass');
    }

    public function render(): string
    {
        $isActive = $this->matchCurrentControllerAction($this->arguments['activeControllerActionFilter']);

        if ($isActive) {
            $this->renderingContext->getVariableProvider()->add(
                $this->arguments['variableName'],
                ' ' . $this->arguments['activeClass']
            );
        }

        $result = $this->renderChildren();

        if ($isActive) {
            $this->renderingContext->getVariableProvider()->remove(
                $this->arguments['variableName']
            );
        }

        return $result;
    }
}
