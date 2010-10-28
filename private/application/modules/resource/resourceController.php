<?php

class ResourceController extends Ajde_Controller
{
	function localDefault()
	{
		return $this->_getLocalResource();
	}

	function compressedDefault()
	{
		return $this->_getCompressedResource();
	}

	protected function _getLocalResource()
	{
		return $this->_getResource('Ajde_Resource_Local');
	}

	protected function _getCompressedResource()
	{
		return $this->_getResource('Ajde_Resource_Local_Compressed');
	}

	protected function _getResource($className)
	{
		// get resource from request
		$hash = Ajde::app()->getRequest()->getParam('id');
		if (!Ajde_Core_Autoloader::exists($className)) {
			throw new Ajde_Controller_Exception("Resource type could not be loaded");
		}
		$resource = call_user_func_array(array($className,"fromHash"), array($hash));
		return $resource->getContents();
	}

}