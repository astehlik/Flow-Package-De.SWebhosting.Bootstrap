<?php
namespace De\SWebhosting\Bootstrap\Resource;

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

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Package\PackageInterface;
use TYPO3\Flow\Resource\Resource;
use TYPO3\Flow\Resource\Storage\PublishLocalDirectoriesStorageInterface;
use TYPO3\Flow\Resource\Storage\StorageInterface;
use TYPO3\Flow\Utility\Files;
use TYPO3\Flow\Utility\Unicode\Functions as UnicodeFunctions;

/**
 * A resource storage that provides read only access to directories.
 * It is intended to be used together with the symlink target.
 */
class ReadOnlyDirectoryStorage implements StorageInterface, PublishLocalDirectoriesStorageInterface {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Resource\Storage\ObjectFactory
	 */
	protected $objectFactory;

	/**
	 * @var array
	 */
	protected $paths;

	/**
	 * Constructor
	 *
	 * @param string $name Name of this storage instance, according to the resource settings
	 * @param array $options Options for this storage
	 * @throws \TYPO3\Flow\Resource\Storage\Exception
	 */
	public function __construct($name, array $options = array()) {
		$this->name = $name;
		foreach ($options as $key => $value) {
			switch ($key) {
				case 'paths':
					$this->$key = $value;
					break;
				default:
					if ($value !== NULL) {
						throw new \TYPO3\Flow\Resource\Storage\Exception(sprintf('An unknown option "%s" was specified in the configuration of the resource storage %s. Please check your settings.', $key, $this->getName()), 1427922188);
					}
			}
		}
	}

	/**
	 * Initializes this resource storage
	 *
	 * @return void
	 * @throws \TYPO3\Flow\Resource\Storage\Exception If the storage directory does not exist
	 */
	public function initializeObject() {

		if (!is_array($this->paths) || empty($this->paths)) {
			throw new \TYPO3\Flow\Resource\Storage\Exception(sprintf('No paths are configured for the storage %s.', $this->getName()), 1427922192);
		}

		foreach ($this->paths as $name => &$path) {

			if (empty($name)) {
				throw new \TYPO3\Flow\Resource\Storage\Exception(sprintf('The name of the path "%s" in the storage %s was empty.', $path, $this->getName()), 1427922198);
			}

			if (!is_dir($path)) {
				throw new \TYPO3\Flow\Resource\Storage\Exception(sprintf('The directory "%s" which was configured as path for the storage %s does not exist or is not a directory.', $path, $this->getName()), 1427922198);
			}

			// Remove trailing slashes from the path.
			$path = rtrim($path, '/');
		}
	}

	/**
	 * Returns the instance name of this storage
	 *
	 * @return string
	 * @api
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns all files in this storage.
	 *
	 * @return array<\TYPO3\Flow\Resource\Storage\Object>
	 */
	public function getObjects() {

		$objects = array();

		foreach ($this->paths as $directoryPath) {
			foreach (Files::readDirectoryRecursively($directoryPath) as $resourcePathAndFilename) {
				$pathInfo = UnicodeFunctions::pathinfo($resourcePathAndFilename);
				$object = $this->objectFactory->createByLocalFile($resourcePathAndFilename, $pathInfo);
				if (isset($pathInfo['dirname'])) {
					/** @var PackageInterface $package */
					list(, $path) = explode('/', str_replace($directoryPath, '', $pathInfo['dirname']), 2);
					$object->setRelativePublicationPath($path . '/');
				}
				$objects[] = $object;
			}
		}

		return $objects;
	}

	/**
	 * Returns all files in this storage.
	 *
	 * @param \TYPO3\Flow\Resource\CollectionInterface $collection
	 * @return array <\TYPO3\Flow\Resource\Storage\Object>
	 * @api
	 */
	public function getObjectsByCollection(\TYPO3\Flow\Resource\CollectionInterface $collection) {
		return $this->getObjects();
	}

	/**
	 * Returns the absolute paths of public resources directories of all active packages.
	 * This method is used directly by the FileSystemSymlinkTarget.
	 *
	 * @return array<string>
	 */
	public function getPublicResourcePaths() {
		return $this->paths;
	}

	/**
	 * Because we cannot store persistent resources in a PackageStorage, this method always returns FALSE.
	 *
	 * @param \TYPO3\Flow\Resource\Resource $resource The resource stored in this storage
	 * @return resource | boolean The resource stream or FALSE if the stream could not be obtained
	 */
	public function getStreamByResource(Resource $resource) {
		return FALSE;
	}

	/**
	 * Returns a stream handle which can be used internally to open / copy the given resource
	 * stored in this storage.
	 *
	 * @param string $relativePath A path relative to the storage root, for example "MyFirstDirectory/SecondDirectory/Foo.css"
	 * @return resource | boolean A URI (for example the full path and filename) leading to the resource file or FALSE if it does not exist
	 * @api
	 */
	public function getStreamByResourcePath($relativePath) {
		return FALSE;
	}
}

