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

use De\SWebhosting\Bootstrap\Messages\MessageCollector;
use De\SWebhosting\Bootstrap\Messages\MessageSettings;
use InvalidArgumentException;
use Neos\Error\Messages\Result;
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

        $this->registerArgument('tagName', 'string', 'The tag name that should be used.', false, 'div');
        $this->registerArgument('formControlId', 'string', 'HTML ID of the enclosed form field', false, '');

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
            'properties',
            'array',
            'Use this if the validation results of multiple properties should be checked.',
            false,
            ''
        );
        $this->registerArgument('includeChildProperties', 'array', '', false, []);

        $this->registerArgument('translationPrefix', 'string', '', false, 'error.');
        $this->registerArgument('excludeForPartsFromTranslationKey', 'array', '', false, []);
    }

    /**
     * Displays a form control group with different classes depending on the validation state.
     *
     * @return string
     */
    public function render()
    {
        if ($this->arguments['defaultClass'] !== '') {
            $this->appendClassForTag($this->arguments['defaultClass']);
        }

        $this->tag->setTagName($this->arguments['tagName']);

        $messageSettings = MessageSettings::createForViewHelper(
            $this->controllerContext->getRequest(),
            $this->arguments
        );

        $messageCollector = new MessageCollector($messageSettings);
        $mergedResult = $messageCollector->collectResultsMerged();

        $this->addResultClasses($mergedResult);

        $this->viewHelperVariableContainer->add(static::class, 'messageSettings', $messageSettings);
        $this->templateVariableContainer->add('validationResults', $mergedResult);
        $this->templateVariableContainer->add('formGroupFieldId', $this->arguments['formControlId']);

        $this->tag->setContent($this->renderChildren());
        $result = $this->tag->render();

        $this->templateVariableContainer->remove('formGroupFieldId');
        $this->templateVariableContainer->remove('validationResults');
        $this->viewHelperVariableContainer->remove(static::class, 'messageSettings');

        return $result;
    }

    /**
     * Appends the given class to the list of classes that the tag should get.
     *
     * @param string $class
     */
    protected function appendClassForTag(string $class)
    {
        $currentClass = $this->tag->getAttribute('class');

        if (!empty($currentClass)) {
            $class = $currentClass . ' ' . $class;
        }

        $this->tag->addAttribute('class', $class);
    }

    private function addResultClasses(Result $propertyResults): void
    {
        if ($propertyResults->hasErrors()) {
            $this->appendClassForTag($this->arguments['errorClass']);
        } elseif ($propertyResults->hasWarnings()) {
            $this->appendClassForTag($this->arguments['warningClass']);
        } elseif ($propertyResults->hasNotices()) {
            $this->appendClassForTag($this->arguments['noticeClass']);
        }
    }
}
