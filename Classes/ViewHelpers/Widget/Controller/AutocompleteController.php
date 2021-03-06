<?php
declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\ViewHelpers\Widget\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package                          *
 * "De.SWebhosting.Bootstrap"                                             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Persistence\QueryResultInterface;
use Neos\FluidAdaptor\Core\Widget\AbstractWidgetController;
use Neos\Utility\ObjectAccess;

/**
 * Controller for the auto-complete widget
 */
class AutocompleteController extends AbstractWidgetController
{
    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @param string $term
     * @return string
     */
    public function autocompleteAction($term)
    {
        $searchProperty = $this->widgetConfiguration['searchProperty'];
        /** @var $queryResult QueryResultInterface */
        $queryResult = $this->widgetConfiguration['objects'];
        $query = clone $queryResult->getQuery();
        $constraint = $query->getConstraint();

        if ($constraint !== null) {
            $query->matching(
                $query->logicalAnd(
                    [
                        $constraint,
                        $query->like($searchProperty, '%' . $term . '%', false),
                    ]
                )
            );
        } else {
            $query->matching(
                $query->like($searchProperty, '%' . $term . '%', false)
            );
        }
        if (isset($this->widgetConfiguration['maxItems'])) {
            $query->setLimit((integer)$this->widgetConfiguration['maxItems']);
        }

        $results = $query->execute();

        $output = [];
        $values = [];
        foreach ($results as $singleResult) {
            $val = ObjectAccess::getPropertyPath($singleResult, $searchProperty);
            if (isset($values[$val])) {
                continue;
            }
            $values[$val] = true;
            $output[] = [
                'id' => $this->persistenceManager->getIdentifierByObject($singleResult),
                'label' => $val,
                'value' => $val,
            ];
        }
        return json_encode($output);
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('id', $this->widgetConfiguration['id']);
    }
}
