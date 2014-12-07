<?php
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

use TYPO3\Flow\Annotations as Flow;

/**
 * This view helper renders a configurable HTML tag and if the current
 * controller / action matches the configured active controller / action
 * the configured active class will be added to the class property of
 * the HTML element.
 *
 * @Flow\Scope("prototype")
 */
class MenuItemViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {
	/**
	 * Initialize all arguments. You need to override this method and call
	 * $this->registerArgument(...) inside this method, to register all your arguments.
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerUniversalTagAttributes();
	}

	/**
	 * Renders the HTML element and adds an additional active class if
	 * controller / action is matching.
	 *
	 * @param string $activeControllerActionFilter
	 * @param array $activeControllerActionFilters
	 * @param string $activeClass
	 * @param string $tagName
	 * @return string
	 */
	public function render($activeControllerActionFilter = NULL, array $activeControllerActionFilters = array(), $activeClass = 'active', $tagName = 'li') {

		if (isset($activeControllerActionFilter)) {
			$activeControllerActionFilters[] = $activeControllerActionFilter;
		}

		$class = '';

		if ($this->hasArgument('class') && $this->arguments['class'] !== '') {
			$class = trim($this->arguments['class']);
		}

		foreach ($activeControllerActionFilters as $activeControllerActionFilter) {

			if ($this->matchCurrentControllerAction($activeControllerActionFilter)) {
				$class = ($class !== '') ? $class . ' ' . $activeClass : $activeClass;
				break;
			}
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
	protected function matchCurrentControllerAction($activeControllerActionFilter) {

		$oneMatch = FALSE;
		$request = $this->controllerContext->getRequest();
		list($classPattern, $actionPattern) = \TYPO3\Flow\Utility\Arrays::trimExplode('->', $activeControllerActionFilter);

		$classPattern = (string)$classPattern;
		$controllerClass = $request->getControllerObjectName();
		if ($classPattern !== '') {
			if (preg_match('/' . str_replace('\\', '\\\\', $classPattern) . '/', $controllerClass) === 0) {
				return FALSE;
			} else {
				$oneMatch = TRUE;
			}
		}

		if ($request instanceof \TYPO3\Flow\Mvc\ActionRequest) {
			$actionPattern = (string)$actionPattern;
			$actionName = $request->getControllerActionName();
			if ($actionPattern !== '') {
				if (preg_match('/' . $actionPattern . '/', $actionName) === 0) {
					return FALSE;
				} else {
					$oneMatch = TRUE;
				}
			}
		}

		return $oneMatch;
	}
}
