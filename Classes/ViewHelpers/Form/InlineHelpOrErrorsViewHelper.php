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

use Neos\Flow\Annotations as Flow;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;

/**
 * Displays validation errors as inline helptext.
 */
class InlineHelpOrErrorsViewHelper extends AbstractViewHelper
{
    /**
     * We render HTML code and to not want it to be escaped.
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * @Flow\Inject
     * @var \Neos\FluidAdaptor\Core\Parser\TemplateParser
     */
    protected $templateParser;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\I18n\Translator
     */
    protected $translator;

    /**
     * Initialize all arguments.
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('validationResultsVariableName', 'string', '', false, 'validationResults');
        $this->registerArgument('translationPrefix', 'string', '', false, 'error.');
        $this->registerArgument('additionalPropertyPrefix', 'string', '', false, '');
        $this->registerArgument('flattenMessages', 'boolean', '', false, true);
        $this->registerArgument('forProperties', 'array', '');
        $this->registerArgument('includeChildProperties', 'array', '');
        $this->registerArgument('excludeForPartsFromTranslationKey', 'array', '');
    }


    /**
     * Displays validation errors as inline helptext.
     *
     * @return string
     */
    public function render()
    {
        $finalOutput = $this->getErrorMessages();

        if (empty($finalOutput)) {
            $finalOutput = $this->renderChildren();
        }

        if (!empty($finalOutput)) {
            $finalOutput = '<span class="help-block">' . $finalOutput . '</span>';
        }

        return $finalOutput;
    }

    /**
     * Builds the translated error messages for the given parameters.
     *
     * @param \Neos\Error\Messages\Result $validationResult
     * @param string $forProperty
     * @param string $originalProperty
     * @param boolean $includeChildProperties
     * @return string
     */
    protected function buildErrorMessages($validationResult, $forProperty, $originalProperty, $includeChildProperties)
    {
        $errorMessages = [];

        /** @var \Neos\Flow\Mvc\ActionRequest $request */
        $request = $this->controllerContext->getRequest();

        $for = $originalProperty;
        if (!empty($this->arguments['excludeForPartsFromTranslationKey']) && $for) {
            $forParts = explode('.', $for);
            foreach ($this->arguments['excludeForPartsFromTranslationKey'] as $excludeKey) {
                unset($forParts[$excludeKey]);
            }
            $for = implode('.', $forParts);
        }

        $for = $this->arguments['additionalPropertyPrefix'] . ($for ? $for . '.' : '');
        $translationPrefix = $this->arguments['translationPrefix'];
        $controllerPrefix = $translationPrefix . 'controller.' . lcfirst(
                $request->getControllerName()
            ) . '.' . $request->getControllerActionName() . '.' . $for;
        $propertyPrefix = $translationPrefix . 'property.' . $for;
        $genericPrefix = $translationPrefix . 'generic.';

        $forSubProperty = substr($forProperty, strlen($originalProperty) + 1);
        if ($forSubProperty) {
            $controllerPrefix .= $forSubProperty . '.';
            $propertyPrefix .= $forSubProperty . '.';
            $validationResult = $validationResult->forProperty($forSubProperty);
        }

        if ($includeChildProperties) {
            $messages = $this->getFattenedMessages($validationResult->getFlattenedErrors());
            $messages = array_merge($messages, $this->getFattenedMessages($validationResult->getFlattenedWarnings()));
            $messages = array_merge($messages, $this->getFattenedMessages($validationResult->getFlattenedNotices()));
        } else {
            $messages = $validationResult->getErrors();
            $messages = array_merge($messages, $validationResult->getWarnings());
            $messages = array_merge($messages, $validationResult->getNotices());
        }

        if (empty($messages)) {
            return $errorMessages;
        }

        /** @var \Neos\Error\Messages\Message $message */
        foreach ($messages as $message) {
            $controllerId = $controllerPrefix . $message->getCode();
            $translatedMessage = $this->translateById($controllerId);
            if ($translatedMessage === $controllerId) {
                $propertyId = $propertyPrefix . $message->getCode();
                $translatedMessage = $this->translateById($propertyId);
                if ($translatedMessage === $propertyId) {
                    $genericId = $genericPrefix . $message->getCode();
                    $translatedMessage = $this->translateById($genericId);
                    if ($genericId === $translatedMessage) {
                        $translatedMessage = $message . ' [' . $controllerId . ' or ' . $propertyId . ' or ' . $genericId . ']';
                    }
                }
            }
            $translatedMessage = $this->templateParser->parse($translatedMessage);
            $this->templateVariableContainer->add('message', $message);
            $errorMessages[] = $translatedMessage->getRootNode()->evaluate($this->renderingContext);
            $this->templateVariableContainer->remove('message');
        }

        return $errorMessages;
    }

    /**
     * Renders all error messages to a string seperated by line breaks.
     *
     * @return string
     * @throws \Neos\FluidAdaptor\Core\ViewHelper\Exception\InvalidVariableException
     */
    protected function getErrorMessages()
    {
        $errorMessages = '';

        if (!$this->templateVariableContainer->exists($this->arguments['validationResultsVariableName'])) {
            return $errorMessages;
        }

        $validationResultData = $this->templateVariableContainer->get(
            $this->arguments['validationResultsVariableName']
        );

        /** @var \Neos\Error\Messages\Result $validationResult */
        $validationResult = $validationResultData['validationResults'];
        if (!isset($validationResult)) {
            return $errorMessages;
        }

        $for = $validationResultData['for'];
        $errorMessageArray = [];
        if (!isset($this->arguments['forProperties'])) {
            $errorMessageArray = $this->buildErrorMessages($validationResult, $for, $for, true);
        } else {
            foreach ($this->arguments['forProperties'] as $index => $propertyPath) {
                $includeChildProperties = isset($this->arguments['includeChildProperties'][$index]) ? (bool)$this->arguments['includeChildProperties'][$index] : true;
                $errorMessageArray = array_merge(
                    $errorMessageArray,
                    $this->buildErrorMessages($validationResult, $propertyPath, $for, $includeChildProperties)
                );
            }
        }
        $errorMessages = implode('<br />', $errorMessageArray);

        return $errorMessages;
    }

    /**
     * Flattens the given array of property messages.
     *
     * @param array $propertyMessages
     * @return \Neos\Error\Messages\Message[]
     */
    protected function getFattenedMessages($propertyMessages)
    {
        $messages = [];
        foreach ($propertyMessages as $messageArray) {
            $messages = array_merge($messages, $messageArray);
        }
        return $messages;
    }

    /**
     * Returns the translation for the given ID.
     *
     * @param string $id
     * @return string
     */
    protected function translateById($id)
    {
        /** @var \Neos\Flow\Mvc\ActionRequest $request */
        $request = $this->controllerContext->getRequest();
        return $this->translator->translateById($id, [], null, null, 'Main', $request->getControllerPackageKey());
    }
}
