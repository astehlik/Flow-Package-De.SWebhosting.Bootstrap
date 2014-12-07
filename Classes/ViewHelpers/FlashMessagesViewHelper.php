<?php
namespace De\SWebhosting\Bootstrap\ViewHelpers;

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

use TYPO3\Flow\Error\Message;

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
	 * @param boolean $renderCloseButton If TRUE a close button will be rendered in the flash messages.
	 * @throws \InvalidArgumentException
	 * @return string rendered Flash Messages, if there are any.
	 */
	public function render($renderCloseButton = FALSE) {
		$flashMessages = $this->controllerContext->getFlashMessageContainer()->getMessagesAndFlush();
		$result = '';

		/**
		 * @var Message $flashMessage
		 */
		foreach ($flashMessages as $flashMessage) {
			switch ($flashMessage->getSeverity()) {
				case Message::SEVERITY_NOTICE:
					$class = 'alert-info';
					break;
				case Message::SEVERITY_WARNING:
					$class = 'alert-warning';
					break;
				case Message::SEVERITY_ERROR:
					$class = 'alert-danger';
					break;
				case Message::SEVERITY_OK:
					$class = 'alert-success';
					break;
				default:
					throw new \InvalidArgumentException('The flash message "' . $flashMessage . '" had an invalid severity.');
					break;
			}

			$result .= '<div class="alert ' . $class . '">';

			if ($renderCloseButton) {
				$result .= '<a class="close" data-dismiss="alert" href="#">×</a>';
			}

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