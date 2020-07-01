<?php
declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\ViewHelpers\Form;

use RuntimeException;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class LabelViewHelper extends AbstractTagBasedViewHelper
{
    protected $tagName = 'label';

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerTagAttribute('for', 'string', 'The ID of the form field', false);
        $this->registerUniversalTagAttributes();
    }

    public function render()
    {
        if (!$this->hasArgument('for')) {
            $this->setForAttributeFromFromGroup();
        }

        $this->tag->setContent($this->renderChildren());

        return parent::render();
    }

    private function setForAttributeFromFromGroup(): void
    {
        if (!$this->templateVariableContainer->exists('formGroupFieldId')) {
            throw new RuntimeException(
                'The for property is not set and the formControlId'
                . ' property is not set at the form group view  helper'
            );
        }

        $this->tag->addAttribute('for', $this->templateVariableContainer->get('formGroupFieldId'));
    }
}
