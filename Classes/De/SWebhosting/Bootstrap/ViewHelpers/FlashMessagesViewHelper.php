<?php
namespace De\SWebhosting\Bootstrap\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Bootstrap".                  *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */


/**
 * View helper which renders the flash messages (if there are any) as an unsorted list.
 *
 * In case you need custom Flash Message HTML output, please write your own ViewHelper for the moment.
 *
 *
 * = Examples =
 *
 * <code title="Simple">
 * <f:flashMessages />
 * </code>
 * Renders an ul-list of flash messages.
 *
 * <code title="Output with css class">
 * <f:flashMessages class="specialClass" />
 * </code>
 * <output>
 * <ul class="specialClass">
 *  ...
 * </ul>
 * </output>
 *
 * @api
 */
class FlashMessagesViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render method.
	 *
	 * @return string rendered Flash Messages, if there are any.
	 * @api
	 */
	public function render() {
		$flashMessages = $this->controllerContext->getFlashMessageContainer()->getMessagesAndFlush();
		$result = '';
		/**
		 * @var \TYPO3\FLOW3\Error\Message $flashMessage
		 */
		foreach ($flashMessages as $flashMessage) {
			switch($flashMessage->getSeverity()) {
				case \TYPO3\FLOW3\Error\Message::SEVERITY_NOTICE:
					$class = 'alert-info';
					break;
				case \TYPO3\FLOW3\Error\Message::SEVERITY_WARNING:
					$class = 'alert-block';
					break;
				case \TYPO3\FLOW3\Error\Message::SEVERITY_ERROR:
					$class = 'alert-error';
					break;
				case \TYPO3\FLOW3\Error\Message::SEVERITY_OK:
					$class = 'alert-success';
					break;
				default:
					throw new \InvalidArgumentException('The flash message "' . $flashMessage . '" had an invalid severity.');
					break;
			}

			$result .= '<div class="alert ' . $class . '">';

			$title = $flashMessage->getTitle();
			if (!empty($title)) {
				$result .= '<h4 class="alert-heading">' . $title . '</h4>';
			}

			$result .= $flashMessage->render();
			$result .= '</div>';
		}
		return $result;
	}
}

?>
