<?php

class Ajde_Core_App extends Ajde_Object_Singleton
{
	/**
	 *
	 * @staticvar Ajde_Core_App $instance
	 * @return Ajde_Core_App
	 */
	public static function getInstance()
	{
		static $instance;
		return $instance === null ? $instance = new self : $instance;
	}

	/**
	 *
	 * @return Ajde_Core_App
	 */
	public static function app()
	{
		return self::getInstance();
	}

	/**
	 *
	 * @return Ajde_Core_App
	 */
	public static function create()
	{
		return self::app();
	}

	public function run()
	{
		$app = self::app();

		// Bootstrap init
		$bootstrap = new Ajde_Core_Bootstrap();
		$bootstrap->run();

		// Get request
		$request = Ajde_Http_Request::fromGlobal();
		$this->setRequest($request);

		// Load document
		$document = Ajde_Document::fromRequest($request);
		$this->setDocument($document);

		// Load controller
		$controller = Ajde_Controller::fromRequest($request);
		$this->setController($controller);

		// Create fresh response
		$response = new Ajde_Http_Response();
		$this->setResponse($response);

		// Invoke controller action
		$actionResult = $controller->invoke();
		$document->setBody($actionResult);

		if (!$document->hasLayout())
		{
			// Load default layout into document
			$layout = new Ajde_Layout(Config::get("layout"));
			$document->setLayout($layout);
		}

		// Get document contents
		$contents = $document->render();

		// Let the cache handle the contents and have it saved to the response
		$cache = Ajde_Cache::getInstance();
		$cache->setContents($contents);
		$cache->saveResponse();
		
		// Output the buffer
		$response->send();
	}

	public static function routingError(Ajde_Exception $exception)
	{
		if (Config::get("debug") === true)
		{
			throw $exception;
		}
		else
		{
			Ajde_Exception_Log::logException($exception);
			Ajde_Http_Response::redirectNotFound();
		}
	}

	/**
	 *
	 * @return Ajde_Http_Request
	 */
	public function getRequest() {
		return $this->get("request");
	}

	/**
	 *
	 * @return Ajde_Http_Response
	 */
	public function getResponse() {
		return $this->get("response");
	}
	
	/**
	 *
	 * @return Ajde_Document
	 */
	public function getDocument() {
		return $this->get("document");
	}

	/**
	 *
	 * @return Ajde_Controller
	 */
	public function getController() {
		return $this->get("controller");
	}

	public static function includeFile($filename)
	{
		Ajde_Cache::getInstance()->addFile($filename);
		include $filename;
	}

	public static function includeFileOnce($filename)
	{
		Ajde_Cache::getInstance()->addFile($filename);
		include_once $filename;
	}

}