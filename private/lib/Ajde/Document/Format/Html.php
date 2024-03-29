<?php

class Ajde_Document_Format_Html extends Ajde_Document
{
	const RESOURCE_POSITION_DEFAULT = 0;
	const RESOURCE_POSITION_FIRST = 1;
	const RESOURCE_POSITION_LAST = 2;
	
	protected $_resources = array();
	protected $_compressors = array();
	protected $_meta = array();

	public function  __construct()
	{
		/*
		 * We add the resources before the template is included, otherwise the
		 * layout resources never make it into the <head> section.
		 */
		Ajde_Event::register('Ajde_Template', 'beforeGetContents', array($this, 'autoAddResources'));
		parent::__construct();
	}

	public function render()
	{
		Ajde::app()->getResponse()->addHeader('Content-type', 'text/html');
		if (Config::get('compressHtml') == true)
		{
			Ajde_Event::register('Ajde_Layout', 'afterGetContents', 'Ajde_Document_Format_Html_Compressor::compress');
		}
		return parent::render();
	}

	/**
	 *
	 * @param mixed $resourceTypes
	 * @return string
	 */
	public function getHead($resourceTypes = '*')
	{
		if (!is_array($resourceTypes)) {
			$resourceTypes = (array) $resourceTypes;
		}
		return $this->renderHead($resourceTypes);
	}
	
	public function getScripts()
	{
		return $this->getHead('js');
	}

	public function renderHead(array $resourceTypes = array('*'))
	{		
		$code = '';
		$code .= $this->renderResources($resourceTypes);
		// TODO: meta tags etc
		return $code;
	}

	public function renderResources(array $types = array('*'))
	{
		return Config::get('compressResources') ?
			$this->renderCompressedResources($types) :
			$this->renderAllResources($types);
	}

	public function renderAllResources(array $types = array('*'))
	{
		$code = '';
		foreach ($this->_resources as $resource)
		{
			/* @var $resource Ajde_Resource */
			if (current($types) == '*' || in_array($resource->getType(), $types))
			{
				$code .= $resource->getLinkCode() . PHP_EOL;
			}
		}
		return $code;
	}

	public function renderCompressedResources(array $types = array('*'))
	{
		// Reset compressors
		$this->_compressors = array();
		$code = '';
		foreach ($this->_resources as $resource)
		{
			/* @var $resource Ajde_Resource */
			if (current($types) == '*' || in_array($resource->getType(), $types))
			{				
				if ($resource instanceof Ajde_Resource_Local)
				{
					if (!isset($this->_compressors[$resource->getType()]))
					{
						$this->_compressors[$resource->getType()] =
								Ajde_Resource_Local_Compressor::fromType($resource->getType());
					}
					$compressor = $this->_compressors[$resource->getType()];
					/* @var $compressor Ajde_Resource_Local_Compressor */
					$compressor->addResource($resource);
				}
				else
				{
					$code .= $resource->getLinkCode() . PHP_EOL;
				}
			}
		}
		foreach ($this->_compressors as $compressor)
		{
			$resource = $compressor->process();
			$code .= $resource->getLinkCode() . PHP_EOL;
		}
		return $code;
	}

	public function getResourceTypes()
	{
		return array(
			Ajde_Resource::TYPE_JAVASCRIPT,
			Ajde_Resource::TYPE_STYLESHEET
		);
	}

	public function addMeta($contents)
	{
		
	}

	public function addResource(Ajde_Resource $resource, $position = self::RESOURCE_POSITION_DEFAULT)
	{
		// Check for duplicates
		foreach($this->_resources as $item) {
			if ((string) $item == (string) $resource) {
				return false;
			}
		}
		switch ($position)
		{
			case self::RESOURCE_POSITION_DEFAULT:
			case self::RESOURCE_POSITION_LAST:
				$this->_resources[] = $resource;
				break;
			case self::RESOURCE_POSITION_FIRST:
				array_unshift($this->_resources, $resource);
				break;
		}
		return true;	
	}

	public function autoAddResources(Ajde_Template $template)
	{
		$position = $template->getDefaultResourcePosition();
		foreach($this->getResourceTypes() as $resourceType) {
			if ($defaultResource = Ajde_Resource_Local::lazyCreate($resourceType, $template->getBase(), 'default', $template->getFormat()))
			{
				$this->addResource($defaultResource, $position);
			}
			if ($template->getAction() != 'default' &&
				$actionResource = Ajde_Resource_Local::lazyCreate($resourceType, $template->getBase(), $template->getAction(), $template->getFormat()))
			{
				$this->addResource($actionResource, $position);
			}
		}
	}
	
}