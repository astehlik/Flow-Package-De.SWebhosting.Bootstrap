<?php

declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\Aspect;

use Neos\Error\Messages\Result;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\AOP\JoinPointInterface;
use Neos\FluidAdaptor\Core\ViewHelper\TemplateVariableContainer;
use Neos\FluidAdaptor\ViewHelpers\Form\AbstractFormFieldViewHelper;
use ReflectionProperty;

/**
 * @Flow\Aspect
 */
class FormFieldViewHelperAspect
{
    /**
     * @var ReflectionProperty
     */
    private $hasArgumentReflection;

    /**
     * @var ReflectionProperty
     */
    private $templateVariableContainerReflection;

    /**
     * @var AbstractFormFieldViewHelper
     */
    private $viewHelper;

    /**
     * @Flow\Around("within(Neos\FluidAdaptor\ViewHelpers\Form\AbstractFormFieldViewHelper) && method(.*->getMappingResultsForProperty())")
     *
     * @param JoinPointInterface $joinPoint
     * @return Result
     */
    public function getMappingResultsForProperty(JoinPointInterface $joinPoint): Result
    {
        $this->viewHelper = $joinPoint->getProxy();

        if (!$this->getTemplateVariableContainer()->exists('validationResults')) {
            return $joinPoint->getAdviceChain()->proceed($joinPoint);
        }

        return $this->getTemplateVariableContainer()->get('validationResults');
    }

    /**
     * @Flow\Around("within(Neos\FluidAdaptor\ViewHelpers\Form\AbstractFormFieldViewHelper) && method(.*->setArguments())")
     *
     * @param JoinPointInterface $joinPoint
     */
    public function setArguments(JoinPointInterface $joinPoint)
    {
        $this->viewHelper = $joinPoint->getProxy();

        $arguments = $joinPoint->getMethodArgument('arguments');

        if ($this->hasArgumentDefinition('errorClass')
            && $arguments['errorClass'] === 'f3-form-error') {
            $arguments['errorClass'] = 'is-invalid';
        }

        if ($this->hasArgumentDefinition('class')
            && empty($arguments['class'])) {
            $arguments['class'] = 'form-control';
        }

        if ($this->hasArgumentDefinition('id')
            && $this->getTemplateVariableContainer()->exists('formGroupFieldId')) {
            $arguments['id'] = $this->getTemplateVariableContainer()->get('formGroupFieldId');
        }

        $joinPoint->setMethodArgument('arguments', $arguments);
        $joinPoint->getAdviceChain()->proceed($joinPoint);
    }

    private function createAccessiblePropertyReflection(string $propertyName): ReflectionProperty
    {
        $reflection = new ReflectionProperty(AbstractFormFieldViewHelper::class, $propertyName);
        $reflection->setAccessible(true);
        return $reflection;
    }

    private function getHasArgumentReflection(): ReflectionProperty
    {
        if (!$this->hasArgumentReflection) {
            $this->hasArgumentReflection = $this->createAccessiblePropertyReflection('argumentDefinitions');
        }
        return $this->hasArgumentReflection;
    }

    private function getTemplateVariableContainer(): TemplateVariableContainer
    {
        return $this->getTemplateVariableContainerReflection()->getValue($this->viewHelper);
    }

    private function getTemplateVariableContainerReflection(): ReflectionProperty
    {
        if (!$this->templateVariableContainerReflection) {
            $this->templateVariableContainerReflection = $this->createAccessiblePropertyReflection(
                'templateVariableContainer'
            );
        }
        return $this->templateVariableContainerReflection;
    }

    private function hasArgumentDefinition(string $argumentName): bool
    {
        $hasArgument = $this->getHasArgumentReflection();
        $arguments = $hasArgument->getValue($this->viewHelper);
        return array_key_exists($argumentName, $arguments);
    }
}
