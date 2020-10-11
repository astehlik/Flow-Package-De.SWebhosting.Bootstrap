<?php

declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\Messages;

use Generator;
use Neos\Error\Messages\Message;
use Neos\Flow\I18n\Translator;

final class MessageTranslator
{
    /**
     * @var MessageSettings
     */
    private $messageSettings;

    /**
     * @var string
     */
    private $propertyTranslationKey;

    /**
     * @var Translator
     */
    private $translator;

    public function __construct(MessageSettings $messageSettings, Translator $translator)
    {
        $this->messageSettings = $messageSettings;
        $this->translator = $translator;
    }

    public function switchPropertyPath($propertyPath): void
    {
        $this->propertyTranslationKey = $this->messageSettings->getPropertyTranslationKey($propertyPath);
    }

    public function translate(Message $message): string
    {
        foreach ($this->getKeys($message) as $key) {
            $translatedMessage = $this->translateById($key);
            if (!$this->isTranslationMissing($translatedMessage, $key)) {
                return $translatedMessage;
            }
        }

        return $this->buildFallbackMessage($message);
    }

    protected function translateById(string $id): ?string
    {
        return $this->translator->translateById(
            $id,
            [],
            null,
            null,
            'Main',
            $this->messageSettings->getControllerPackageKey()
        );
    }

    private function buildControllerKey(Message $message): string
    {
        $controllerKey = 'controller.' . lcfirst($this->messageSettings->getControllerName())
            . '.' . $this->messageSettings->getControllerActionName() . '.' . $this->propertyTranslationKey;

        return $this->buildMessageKey($controllerKey, $message);
    }

    private function buildFallbackMessage(Message $message): string
    {
        return $message . ' [' . $this->buildControllerKey($message)
            . ' or ' . $this->buildPropertyKey($message)
            . ' or ' . $this->buildGenericKey($message) . ']';
    }

    private function buildGenericKey(Message $message): string
    {
        return $this->buildMessageKey('generic', $message);
    }

    private function buildMessageKey(string $controllerKey, Message $message): string
    {
        return $this->messageSettings->getTranslationKeyPrefix() . $controllerKey . '.' . $message->getCode();
    }

    private function buildPropertyKey(Message $message): string
    {
        return $this->buildMessageKey('property' . '.' . $this->propertyTranslationKey, $message);
    }

    private function getKeys(Message $message): Generator
    {
        yield $this->buildControllerKey($message);
        yield $this->buildPropertyKey($message);
        yield $this->buildGenericKey($message);
    }

    private function isTranslationMissing(?string $translatedMessage, string $translationKey): bool
    {
        return $translatedMessage === $translationKey || empty($translatedMessage);
    }
}
