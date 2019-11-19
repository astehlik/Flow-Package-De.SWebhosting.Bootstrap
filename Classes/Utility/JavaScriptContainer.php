<?php
declare(strict_types=1);

namespace De\SWebhosting\Bootstrap\Utility;

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

use Exception;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class JavaScriptContainer
{
    /**
     * Contains all loaded ids
     *
     * @var array
     */
    protected $loadedIds = [];

    /**
     * Stores the JavaScript code for the different sections
     *
     * @var array
     */
    protected $sections = [];

    /**
     * Appends the given javascript code to the given section and marks the
     * given id as loaded if it is not null
     *
     * @param string $script The JavaScript that will be appended
     * @param string $id An optional ID that will be marked as loaded
     * @param string $section The section to which the src is appended to (footer by default)
     */
    public function appendScriptToSection($script, $id = null, $section = 'footer')
    {
        $this->appendToSection($script, $id, $section);
    }

    /**
     * Appends the given javascript src to the given section and marks the
     * given id as loaded if it is not null
     *
     * @param string $src The JavaScript src that should be loaded
     * @param string $id An optional ID that will be marked as loaded
     * @param string $section The section to which the src is appended to (footer by default)
     */
    public function appendSrcToSection($src, $id = null, $section = 'footer')
    {
        $this->appendToSection('<script type="text/javascript" src="' . $src . '"></script>', $id, $section);
    }

    /**
     * Returns the content of the given section
     *
     * @param string $section the section for wich the content should be returned
     * @param bool $optional If true the section does not need to contain any content
     * @return string The content of the given section
     * @throws Exception If no content was present in the section and the section is not optional
     */
    public function getSectionContent($section = 'footer', $optional = true)
    {
        if (array_key_exists($section, $this->sections)) {
            return $this->sections[$section];
        } else {

            if (!$optional) {
                throw new Exception('A required JavaScript section was empty: ' . $section);
            }

            return '';
        }
    }

    /**
     * Returns TRUE if the given id is already loaded into a section
     *
     * @param string $id
     * @return bool
     */
    public function idIsLoaded($id)
    {
        if (!isset($id)) {
            return false;
        }

        if (array_key_exists($id, $this->loadedIds)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Appends the given string to the given section and marks the given id as
     * loaded if it is not NULL
     *
     * @param string $script The script that should be appended to the section
     * @param string $id The id that will be marked as loaded (can be NULL)
     * @param string $section The section to which the script is appended
     * @throws Exception If there was already script loaded for the given id
     */
    protected function appendToSection($script, $id, $section)
    {
        if ($this->idIsLoaded($id)) {
            throw new Exception(
                "You are trying to set the content for an id that was already loaded. Please use idIsLoaded() to check if the id was loaded before."
            );
        }

        if (!array_key_exists($section, $this->sections)) {
            $this->sections[$section] = '';
        }

        $this->sections[$section] .= $script . PHP_EOL;

        $this->markIdAsLoaded($id);
    }

    /**
     * Marks the given id as loaded if it is not NULL
     *
     * @param string $id
     */
    protected function markIdAsLoaded($id)
    {
        if (!isset($id)) {
            return;
        }

        $this->loadedIds[$id] = true;
    }
}
