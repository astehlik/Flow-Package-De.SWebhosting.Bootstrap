<?php
declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\Utility;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\ActionRequest;
use RuntimeException;

trait ControllerActionMatchingTrait
{
    /**
     * @Flow\Inject
     * @var \De\SWebhosting\Bootstrap\Utility\PointcutUtility
     */
    protected $pointcutUtility;

    /**
     * Checks if the given controller / action name matches the current
     * controller / action.
     *
     * @param string $activeControllerActionFilter
     * @return bool
     */
    private function matchCurrentControllerAction($activeControllerActionFilter): bool
    {
        $request = $this->controllerContext->getRequest();
        if (!$request instanceof ActionRequest) {
            throw new RuntimeException(
                sprintf('This view helper only works in %s context.', ActionRequest::class), 1425850365
            );
        }

        return $this->pointcutUtility->matchControllerActionMethod($activeControllerActionFilter, $request);
    }
}
