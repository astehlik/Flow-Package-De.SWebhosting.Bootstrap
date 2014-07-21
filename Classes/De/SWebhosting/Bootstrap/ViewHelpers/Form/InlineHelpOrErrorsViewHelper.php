<?php
namespace De\SWebhosting\Bootstrap\ViewHelpers\Form;

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
use TYPO3\Flow\Annotations as Flow;

/**
 * Displays validation errors as inline helptext.
 */
class InlineHelpOrErrorsViewHelper extends AbstractViewHelper {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Translator
	 */
	protected $translator;

	/**
	 * Displays validation errors as inline helptext.
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

			$validationResultData = $this->templateVariableContainer->get($validationResultsVariableName);
			$validationResult = $validationResultData['validationResults'];
			$for = $validationResultData['for'] ? $validationResultData['for'] . '.' : '';

			/** @var \TYPO3\Flow\Mvc\ActionRequest $request */
			$request = $this->controllerContext->getRequest();

			$idPrefix = 'error.' . lcfirst($request->getControllerName()) . '.' . $request->getControllerActionName() . '.' . $for;

			if (isset($validationResult)) {
				$messages = $this->getFattenedMessages($validationResult->getFlattenedErrors());
				$messages = array_merge($messages, $this->getFattenedMessages($validationResult->getFlattenedWarnings()));
				$messages = array_merge($messages, $this->getFattenedMessages($validationResult->getFlattenedNotices()));

				$translatedMessages = array();
				/** @var \TYPO3\Flow\Error\Message $message */
				foreach ($messages as $message) {
					$id = $idPrefix . $message->getCode();
					$translatedMessage = $this->translator->translateById($id, array(), NULL, NULL, 'Main', $request->getControllerPackageKey());
					if ($id === $translatedMessage) {
						$translatedMessage = $message . ' [' . $id . ']';
					}
					$translatedMessages[] = $translatedMessage;
				}
				$finalOutput = implode('<br />', $translatedMessages);
			}
		}

		if (empty($finalOutput)) {
			$finalOutput = $this->renderChildren();
		}

		if (!empty($finalOutput)) {
			$finalOutput = '<span class="help-block">' . $finalOutput . '</span>';
		}

		return $finalOutput;
	}

	/**
	 * Flattens the given array of property messages.
	 *
	 * @param array $propertyMessages
	 * @return \TYPO3\Flow\Error\Message[]
	 */
	protected function getFattenedMessages($propertyMessages) {
		$messages = array();
		foreach ($propertyMessages as $messageArray) {
			$messages = array_merge($messages, $messageArray);
		}
		return $messages;
	}
}