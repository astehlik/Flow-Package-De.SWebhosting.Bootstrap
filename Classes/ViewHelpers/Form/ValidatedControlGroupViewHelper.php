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

/**
 * Displays a form control group with different classes depending on the validation state.
 */
class ValidatedControlGroupViewHelper extends AbstractViewHelper {

	/**
	 * We render HTML code and to not want it to be escaped.
	 *
	 * @var bool
	 */
	protected $escapeOutput = FALSE;

	/**
	 * Displays a form control group with different classes depending on the validation state.
	 *
	 * @param string $for The name of the property for which the validation results should be checked.
	 * @param string $as The variable name in which the validation results should be stored.
	 * @param string $errorClass The class that should be added when validation errors are found.
	 * @param string $warningClass The class that should be added when validation warnings are found.
	 * @param string $infoClass The class that should be added when validation notices are found.
	 * @param string $class An additional class attribute that will always be rendered.
	 * @return string
	 */
	public function render($for = '', $as = 'validationResults', $errorClass = 'has-error', $warningClass = 'has-warning', $infoClass = 'has-notice', $class = '') {

		$finalClass = 'form-group';

		/** @var $request \TYPO3\Flow\Mvc\ActionRequest */
		$request = $this->controllerContext->getRequest();
		/** @var $validationResults \TYPO3\Flow\Error\Result */
		$validationResults = $request->getInternalArgument('__submittedArgumentValidationResults');

		if ($validationResults !== NULL && $for !== '') {
			$validationResults = $validationResults->forProperty($for);
			if ($validationResults->hasErrors()) {
				$finalClass .= ' ' . $errorClass;
			} elseif ($validationResults->hasWarnings()) {
				$finalClass .= ' ' . $warningClass;
			} elseif ($validationResults->hasNotices()) {
				$finalClass .= ' ' . $infoClass;
			}
		}

		if (!empty($class)) {
			$finalClass .= empty($finalClass) ? $class : ' ' . $class;
		}

		$result = '<div class="' . $finalClass . '">';
		$this->templateVariableContainer->add($as, array('validationResults' => $validationResults, 'for' => $for));
		$result .= $this->renderChildren();
		$this->templateVariableContainer->remove($as);
		$result .= '</div>';

		return $result;
	}
}