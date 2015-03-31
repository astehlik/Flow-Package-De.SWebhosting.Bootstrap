<?php
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

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\ActionRequest;

/**
 * Utility class for pointcut handling.
 */
class PointcutUtility {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Aop\Pointcut\PointcutExpressionParser
	 */
	protected $pointcutExpressionParser;

	/**
	 * This is TRUE when all dependencies were already injected in the PointcutExpressionParser.
	 *
	 * @var bool
	 */
	protected $pointcutExpressionParserInitialized = FALSE;

	/**
	 * @Flow\Inject(lazy=false)
	 * @var \TYPO3\Flow\Aop\Builder\ProxyClassBuilder
	 */
	protected $proxyClassBuilder;

	/**
	 * @Flow\Inject(lazy=false)
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 */
	protected $reflectionService;

	/**
	 * Returns TRUE when the given pointcut expression matches the controller action method of the given request.
	 *
	 * @param string $pointcutExpression
	 * @param ActionRequest $request
	 * @return bool
	 */
	public function matchControllerActionMethod($pointcutExpression, ActionRequest $request) {
		$this->initializePointcutExpressionParser();
		$filter = $this->pointcutExpressionParser->parse($pointcutExpression, __CLASS__);
		return $filter->matches($request->getControllerObjectName(), $request->getControllerActionName() . 'Action', $request->getControllerObjectName(), __CLASS__);
	}

	/**
	 * Initializes all required dependencies in the PointcutExpressionParser.
	 * This is required, because no proxy classes are build for the parser.
	 * TODO: Check if it is possible to do this in a better way.
	 */
	protected function initializePointcutExpressionParser() {

		if ($this->pointcutExpressionParserInitialized) {
			return;
		}

		$this->pointcutExpressionParser->injectObjectManager($this->objectManager);
		$this->pointcutExpressionParser->injectReflectionService($this->reflectionService);
		$this->pointcutExpressionParser->injectProxyClassBuilder($this->proxyClassBuilder);
		$this->pointcutExpressionParserInitialized = TRUE;
	}
}