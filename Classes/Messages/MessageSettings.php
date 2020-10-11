<?php

declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\Messages;

use InvalidArgumentException;
use Neos\Error\Messages\Result;
use Neos\Flow\Mvc\ActionRequest;

final class MessageSettings
{
    /**
     * @var array
     */
    private $excludeForPartsFromTranslationKey;

    /**
     * @var array
     */
    private $includeChildProperties;

    /**
     * @var array
     */
    private $propertyPaths;

    /**
     * @var ActionRequest
     */
    private $request;

    /**
     * @var string
     */
    private $translationKeyPrefix;

    /**
     * @var Result
     */
    private $validationResult;

    protected function __construct(
        Result $validationResult,
        array $propertyPaths,
        ActionRequest $request,
        array $excludeForPartsFromTranslationKey,
        array $includeChildProperties,
        string $translationKeyPrefix
    ) {
        $this->validationResult = $validationResult;
        $this->setPropertyPaths(...$propertyPaths);
        $this->request = $request;
        $this->excludeForPartsFromTranslationKey = $excludeForPartsFromTranslationKey;
        $this->includeChildProperties = $includeChildProperties;
        $this->translationKeyPrefix = $translationKeyPrefix;
    }

    public static function createForViewHelper(
        ActionRequest $request,
        array $arguments
    ): self {
        return new static(
            self::getValidationResultFromRequest($request),
            self::getPropertyPathsFromArguments($arguments),
            $request,
            $arguments['excludeForPartsFromTranslationKey'],
            $arguments['includeChildProperties'],
            $arguments['translationPrefix']
        );
    }

    private static function getPropertyPathsFromArguments(array $arguments): array
    {
        $propertyArguments = [
            'for',
            'property',
            'properties',
        ];

        $providedPropertyArgumentNames = [];
        foreach ($propertyArguments as $propertyArgument) {
            if (!empty($arguments[$propertyArgument])) {
                $providedPropertyArgumentNames[] = $propertyArgument;
            }
        }

        $propertyArgumentCount = count($providedPropertyArgumentNames);

        if ($propertyArgumentCount === 0) {
            return [];
        }

        if ($propertyArgumentCount > 1) {
            throw new InvalidArgumentException(
                'You must provide only one property argument (for, property or properties), you provided '
                . implode(', ', $providedPropertyArgumentNames)
            );
        }

        $propertyArgumentName = array_pop($providedPropertyArgumentNames);
        return (array)$arguments[$propertyArgumentName];
    }

    private static function getValidationResultFromRequest(ActionRequest $request): Result
    {
        $result = $request->getInternalArgument('__submittedArgumentValidationResults');

        if ($result) {
            /** @noinspection PhpIncompatibleReturnTypeInspection */
            return $result;
        }

        return new Result();
    }

    public function getControllerActionName(): string
    {
        return $this->request->getControllerActionName();
    }

    public function getControllerName(): string
    {
        return $this->request->getControllerName();
    }

    public function getControllerPackageKey(): string
    {
        return $this->request->getControllerPackageKey();
    }

    public function getPropertyPaths(): array
    {
        return $this->propertyPaths;
    }

    public function getPropertyTranslationKey(string $property)
    {
        $translationKey = $property;
        $excludedKeyIndexes = $this->getExcludedTranslationKeyIndexes($property);

        if ($excludedKeyIndexes === []) {
            return $translationKey;
        }

        $translationKeyParts = explode('.', $property);

        foreach ($excludedKeyIndexes as $excludeKeyIndex) {
            unset($translationKeyParts[$excludeKeyIndex]);
        }

        return implode('.', $translationKeyParts);
    }

    public function getTranslationKeyPrefix(): string
    {
        return $this->translationKeyPrefix;
    }

    public function getValidationResult(): Result
    {
        return $this->validationResult;
    }

    public function shouldIncludeChildProperties(string $propertyPath): bool
    {
        return $this->includeChildProperties[$propertyPath] ?? true;
    }

    private function getExcludedTranslationKeyIndexes(string $property): array
    {
        return $this->excludeForPartsFromTranslationKey;
    }

    private function setPropertyPaths(string ...$propertyPaths): void
    {
        $this->propertyPaths = $propertyPaths;
    }
}
