<?php
namespace De\SWebhosting\Bootstrap\ViewHelpers\Form;

/*                                                                        *
 * This script belongs to the FLOW3 package "Bootstrap".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


/**
 *
 */
class InlineHelpOrErrorsViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render method.
	 *
	 *
	 * @param string $validationResultsVariableName
	 * @return string
	 */
	public function render($validationResultsVariableName = 'validationResults') {

		$finalOutput = '';

		/**
		 * @var \TYPO3\Flow\Error\Result $validationResult
		 */
		if ($this->templateVariableContainer->exists($validationResultsVariableName)) {

			$validationResult = $this->templateVariableContainer->get($validationResultsVariableName);

			if (isset($validationResult)) {
				$messages = $validationResult->getFlattenedNotices();
				$messages = array_merge($messages, $validationResult->getFlattenedWarnings());
				$messages = array_merge($messages, $validationResult->getFlattenedErrors());
				$finalOutput = implode('<br />', $messages);
			}
		}

		if (empty($finalOutput)) {
			$finalOutput = $this->renderChildren();
		}

		if (!empty($finalOutput)) {
			$finalOutput = '<span class="help-inline">' . $finalOutput . '</span>';
		}

		return $finalOutput;
	}
}

?>
