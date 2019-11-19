<?php
declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\Utility;

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
use Neos\Flow\Aop\Builder\ProxyClassBuilder;
use Neos\Flow\Aop\Pointcut\PointcutExpressionParser;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Reflection\ReflectionService;

/**
 * Utility class for pointcut handling.
 */
class PointcutUtility
{
    /**
     * @Flow\Inject
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @Flow\Inject
     * @var PointcutExpressionParser
     */
    protected $pointcutExpressionParser;

    /**
     * This is TRUE when all dependencies were already injected in the PointcutExpressionParser.
     *
     * @var bool
     */
    protected $pointcutExpressionParserInitialized = false;

    /**
     * @Flow\Inject(lazy=false)
     * @var ProxyClassBuilder
     */
    protected $proxyClassBuilder;

    /**
     * @Flow\Inject(lazy=false)
     * @var ReflectionService
     */
    protected $reflectionService;

    /**
     * Returns TRUE when the given pointcut expression matches the controller action method of the given request.
     *
     * @param string $pointcutExpression
     * @param ActionRequest $request
     * @return bool
     */
    public function matchControllerActionMethod($pointcutExpression, ActionRequest $request)
    {
        $this->initializePointcutExpressionParser();
        $filter = $this->pointcutExpressionParser->parse($pointcutExpression, __CLASS__);
        return $filter->matches(
            $request->getControllerObjectName(),
            $request->getControllerActionName() . 'Action',
            $request->getControllerObjectName(),
            __CLASS__
        );
    }

    /**
     * Initializes all required dependencies in the PointcutExpressionParser.
     * This is required, because no proxy classes are build for the parser.
     * TODO: Check if it is possible to do this in a better way.
     */
    protected function initializePointcutExpressionParser()
    {
        if ($this->pointcutExpressionParserInitialized) {
            return;
        }

        $this->pointcutExpressionParser->injectObjectManager($this->objectManager);
        $this->pointcutExpressionParser->injectReflectionService($this->reflectionService);
        $this->pointcutExpressionParser->injectProxyClassBuilder($this->proxyClassBuilder);
        $this->pointcutExpressionParserInitialized = true;
    }
}
