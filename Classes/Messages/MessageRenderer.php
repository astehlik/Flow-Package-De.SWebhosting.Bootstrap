<?php

declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\Messages;

use Neos\Error\Messages\Result;
use Neos\FluidAdaptor\Core\Parser\TemplateParser;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

final class MessageRenderer
{
    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var MessageTranslator
     */
    private $messageTranslator;

    /**
     * @var RenderingContextInterface
     */
    private $renderingContext;

    /**
     * @var TemplateParser
     */
    private $templateParser;

    public function __construct(
        MessageCollector $messageCollector,
        MessageTranslator  $messageTranslator,
        RenderingContextInterface $renderingContext
    ) {
        $this->messageCollector = $messageCollector;
        $this->messageTranslator = $messageTranslator;

        $this->templateParser = new TemplateParser();
        $this->templateParser->setRenderingContext($renderingContext);
        $this->renderingContext = $renderingContext;
    }

    /**
     * Renders all error messages to a string seperated by line breaks.
     *
     * @return string
     */
    public function renderMessages(): string
    {
        $errorMessageArray = [];
        foreach ($this->messageCollector->collectResultsByPropertyPath() as $propertyPath => $result) {
            $this->messageTranslator->switchPropertyPath($propertyPath);
            $errorMessageArray = array_merge(
                $errorMessageArray,
                $this->buildErrorMessages($result)
            );
        }
        return implode('<br />', $errorMessageArray);
    }

    private function buildErrorMessages(Result $propertyResult): array
    {
        $messages = $this->getAllMessages($propertyResult);

        $errorMessages = [];
        foreach ($messages as $message) {
            $translatedMessage = $this->messageTranslator->translate($message);

            $translatedMessage = $this->templateParser->parse($translatedMessage);
            $this->renderingContext->getVariableProvider()->add('message', $message);
            $errorMessages[] = $translatedMessage->getRootNode()->evaluate($this->renderingContext);
            $this->renderingContext->getVariableProvider()->remove('message');
        }

        return $errorMessages;
    }

    private function getAllMessages(Result $result)
    {
        $messages = $result->getErrors();
        $messages = array_merge($messages, $result->getWarnings());
        $messages = array_merge($messages, $result->getNotices());
        return $messages;
    }
}
