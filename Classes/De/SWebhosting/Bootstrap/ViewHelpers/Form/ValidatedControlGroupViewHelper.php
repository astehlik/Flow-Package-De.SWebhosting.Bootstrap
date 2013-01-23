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
class ValidatedControlGroupViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render method.
	 *
	 *
	 * @param string $for
	 * @param string $as
	 * @param string $errorClass
	 * @param string $warningClass
	 * @param string $infoClass
	 * @return string
	 */
	public function render($for = '', $as = 'validationResults', $errorClass = 'error', $warningClass = 'warning', $infoClass = 'info') {

		$finalClass = 'control-group';

		/**
		 * @var \TYPO3\Flow\Error\Result $validationResults
		 */
		$validationResults = $this->controllerContext->getRequest()->getInternalArgument('__submittedArgumentValidationResults');
		if ($validationResults !== NULL && $for !== '') {
			$validationResults = $validationResults->forProperty($for);
			if ($validationResults->hasErrors()) {
				$finalClass .=  ' ' . $errorClass;
			} elseif ($validationResults->hasWarnings()) {
				$finalClass .=  ' ' . $warningClass;
			} elseif ($validationResults->hasNotices()) {
				$finalClass .=  ' ' . $infoClass;
			}
		}

		$result = '<div class="' . $finalClass . '">';
		$this->templateVariableContainer->add($as, $validationResults);
		$result .= $this->renderChildren();
		$this->templateVariableContainer->remove($as);
		$result .= '</div>';

		return $result;
	}
}

?>
