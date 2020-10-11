<?php

declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\Messages;

use Neos\Error\Messages\Message;
use Neos\Error\Messages\Result;

final class MessageCollector
{
    /**
     * @var MessageSettings
     */
    private $messageSettings;

    public function __construct(MessageSettings $messageSettings)
    {
        $this->messageSettings = $messageSettings;
    }

    public function collectResultsMerged(): Result
    {
        $propertyResults = new Result();
        foreach ($this->messageSettings->getPropertyPaths() as $propertyPath) {
            $propertyResult = $this->buildPropertiesResult($propertyPath);
            if (!$propertyResult) {
                continue;
            }
            $propertyResults->merge($propertyResult);
        }
        return $propertyResults;
    }

    /**
     * @return Result[]|array
     */
    public function collectResultsByPropertyPath(): array
    {
        $errorMessageArray = [];
        foreach ($this->messageSettings->getPropertyPaths() as $propertyPath) {
            $propertyResult = $this->buildPropertiesResult($propertyPath);
            if (!$propertyResult) {
                continue;
            }
            $errorMessageArray[$propertyPath] = $propertyResult;
        }
        return $errorMessageArray;
    }

    private function buildPropertiesResult(string $propertyPath): ?Result
    {
        $validationResults = $this->messageSettings->getValidationResult()->forProperty($propertyPath);

        if ($this->messageSettings->shouldIncludeChildProperties($propertyPath)) {
            $messages = [
                'errors' => $validationResults->getErrors(),
                'warnings' => $validationResults->getWarnings(),
                'notices' => $validationResults->getNotices(),
            ];
            return $this->buildResultFromArray($messages);
        }

        $messages = [
            'errors' => $this->getFattenedMessages($validationResults->getFlattenedErrors()),
            'warnings' => $this->getFattenedMessages($validationResults->getFlattenedWarnings()),
            'notices' => $this->getFattenedMessages($validationResults->getFlattenedNotices()),
        ];
        return $this->buildResultFromArray($messages);
    }

    private function buildResultFromArray(array $messages): ?Result
    {
        $hasResults = false;
        $result = new Result();
        foreach ($messages['errors'] as $error) {
            $result->addError($error);
            $hasResults = true;
        }
        foreach ($messages['warnings'] as $warning) {
            $result->addWarning($warning);
            $hasResults = true;
        }
        foreach ($messages['notices'] as $notice) {
            $result->addNotice($notice);
            $hasResults = true;
        }

        if (!$hasResults) {
            return null;
        }

        return $result;
    }

    /**
     * Flattens the given array of property messages.
     *
     * @param array $propertyMessages
     * @return Message[]|array
     */
    private function getFattenedMessages(array $propertyMessages): array
    {
        $messages = [];
        foreach ($propertyMessages as $messageArray) {
            $messages = array_merge($messages, $messageArray);
        }
        return $messages;
    }
}
