<?php
declare(strict_types=1);

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

use Neos\Error\Messages\Result;
use Neos\Flow\Mvc\ActionRequest;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * Displays a tag with different classes depending on the validation state.
 */
class ValidatedControlGroupViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * Initialize all arguments.
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerUniversalTagAttributes();

        $this->registerArgument(
            'for',
            'string',
            'The name of the property for which the validation results should be checked.',
            false,
            ''
        );
        $this->registerArgument(
            'property',
            'string',
            'Alias for "for" to prevent IDE errors because the IDs do not exists.',
            false,
            ''
        );
        $this->registerArgument(
            'formControlId',
            'string',
            'The HTML ID of the form field.',
            false,
            ''
        );
        $this->registerArgument(
            'as',
            'string',
            'The variable name in which the validation results should be stored.',
            false,
            'validationResults'
        );
        $this->registerArgument(
            'defaultClass',
            'string',
            'This class will always be added to the list of classes for the tag unless it is empty.',
            false,
            'form-group'
        );
        $this->registerArgument(
            'errorClass',
            'string',
            'The class that should be added when validation errors are found.',
            false,
            'has-error'
        );
        $this->registerArgument(
            'warningClass',
            'string',
            'The class that should be added when validation warnings are found.',
            false,
            'has-warning'
        );
        $this->registerArgument(
            'noticeClass',
            'string',
            'The class that should be added when validation notices are found.',
            false,
            'has-notice'
        );
        $this->registerArgument('tagName', 'string', 'The tag name that should be used. Defaults to "div".', false);
    }

    /**
     * Displays a form control group with different classes depending on the validation state.
     *
     * @return string
     */
    public function render()
    {
        $for = $this->arguments['for'] ?: $this->arguments['property'];
        $as = $this->arguments['as'];

        if ($this->arguments['defaultClass'] !== '') {
            $this->appendClassForTag($this->arguments['defaultClass']);
        }

        if (!empty($this->arguments['tagName'])) {
            $this->tag->setTagName($this->arguments['tagName']);
        }

        $request = $this->controllerContext->getRequest();
        /** @var $validationResults Result */
        $validationResults = $request->getInternalArgument('__submittedArgumentValidationResults');
        if ($validationResults !== null && $for !== '') {
            $validationResults = $validationResults->forProperty($for);
            if ($validationResults->hasErrors()) {
                $this->appendClassForTag($this->arguments['errorClass']);
            } elseif ($validationResults->hasWarnings()) {
                $this->appendClassForTag($this->arguments['warningClass']);
            } elseif ($validationResults->hasNotices()) {
                $this->appendClassForTag($this->arguments['noticeClass']);
            }
        }

        $this->templateVariableContainer->add($as, ['validationResults' => $validationResults, 'for' => $for]);
        $this->templateVariableContainer->add('formGroupFieldId', $this->arguments['formControlId']);

        $this->tag->setContent($this->renderChildren());
        $result = $this->tag->render();

        $this->templateVariableContainer->remove('formGroupFieldId');
        $this->templateVariableContainer->remove($as);

        return $result;
    }

    /**
     * Appends the given class to the list of classes that the tag should get.
     *
     * @param string $class
     */
    protected function appendClassForTag($class)
    {
        $currentClass = $this->tag->getAttribute('class');

        if (!empty($currentClass)) {
            $class = $currentClass . ' ' . $class;
        }

        $this->tag->addAttribute('class', $class);
    }
}
