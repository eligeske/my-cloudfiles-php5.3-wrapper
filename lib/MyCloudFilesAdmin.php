<?php
/**
* Wrapper around PHP 5.3 stable version of rackspace php-opencloud
* - new packages have min version of 5.4
* - read more http://docs.php-opencloud.com/en/latest/using-php-5.3.html
*/

namespace MyCloudFiles;

use OpenCloud\Rackspace;
use OpenCloud\ObjectStore\Resource\DataObject;

class MyCloudFilesAdmin {

	private $service;

	/**
	* $settings = array(
	* 	'username' => 'someUserName',
	* 	'apiKey' => 'asdfasfdafdaf',
	* 	'region' => 'IAD'
	* );
	*
	*/
	public function __construct($settings) {
		$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
			'username' => $settings['username'],
			'apiKey' => $settings['apiKey']
		));
		$this->service = $client->objectStoreService(null, $settings['region']);
	}

	public function getContainerList() {
		return $this->service->listContainers();
	}

	public function getContainerObjectList($containerName) {
		// http://docs.php-opencloud.com/en/latest/services/object-store/objects.html#list-objects-in-a-container
		$container = $this->service->getContainer($containerName);
		return $container->objectList();
	}

	/**
	* Uploads a file with an assigned name to container
	* - http://docs.php-opencloud.com/en/latest/services/object-store/objects.html#upload-a-single-file-under-5gb-with-metadata
	*/
	public function createFileObject($containerName, $fileName, $filePathAndName, $contentType) {
		$container = $this->service->getContainer($containerName);
		$container->uploadObject($fileName, fopen($filePathAndName, 'r+'), array('Content-Type' => $contentType));
	}
	public function createFileObjectFromString($containerName, $fileName, $fileString, $contentType) {
		$container = $this->service->getContainer($containerName);
		$container->uploadObject($fileName, $fileString, array('Content-Type' => $contentType));
	}

}
