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
use De\SWebhosting\Bootstrap\Messages\MessageRenderer;
use De\SWebhosting\Bootstrap\Messages\MessageSettings;
use De\SWebhosting\Bootstrap\Messages\MessageTranslator;
use Neos\Flow\I18n\Translator;
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
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Displays validation errors as inline helptext.
     *
     * @return string
     */
    public function render()
    {
        $errorMessages = $this->renderErrorMessages();
        if ($errorMessages !== '') {
            return '<div class="invalid-feedback">' . $errorMessages . '</div>';
        }

        $helpText = $this->renderChildren();
        if ($helpText === '') {
            return '';
        }

        return '<small class="form-text text-muted">' . $helpText . '</small>';
    }

    private function renderErrorMessages(): string
    {
        $messageSettings = $this->viewHelperVariableContainer->get(
            ValidatedControlGroupViewHelper::class,
            'messageSettings'
        );

        if (!$messageSettings) {
            return '';
        }

        $messageCollector = new MessageCollector($messageSettings);

        $messageTranslator = new MessageTranslator($messageSettings, $this->translator);

        $messageBuilder = new MessageRenderer($messageCollector, $messageTranslator, $this->renderingContext);

        return $messageBuilder->renderMessages();
    }
}
