<?php
namespace De\SWebhosting\Bootstrap\ViewHelpers\JavaScript;

/*                                                                        *
 * This script belongs to the FLOW3 package "Bootstrap".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class JavaScriptContainer {

	/**
	 * Stores the JavaScript code for the different sections
	 *
	 * @var array
	 */
	protected $sections = array();

	public function appendSrcToSection($src, $section = 'footer') {
		$this->appendToSection('<script type="text/javascript" src="' . $src . '"></script>', $section);
	}

	public function appendScriptToSection($script, $section = 'footer') {
		$this->appendToSection($script, $section);
	}

	public function getSectionContent($section = 'footer', $optional = TRUE) {

		if (array_key_exists($section, $this->sections)) {
			return $this->sections[$section];
		} else {

			if (!$optional) {
				throw new \Exception('A required JavaScript section was empty: ' .$section);
			}

			return '';
		}
	}

	protected function appendToSection($script, $section) {

		if (!array_key_exists($section, $this->sections)) {
			$this->sections[$section] = '';
		}

		$this->sections[$section] .= $script. PHP_EOL;
	}
}

?>
