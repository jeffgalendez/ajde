<?php

class _coreComponentController extends Ajde_Controller
{
	/************************
	 * Ajde_Component_Resource
	 ************************/
	
	function resourceLocalDefault()
	{
		return $this->_getLocalResource();
	}

	function resourceCompressedDefault()
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
	
	/************************
	 * Ajde_Component_Form
	 ************************/

	public function formDefault()
	{
		if ($this->getAction() !== 'form/ajax') {
			$this->setAction('form/form');
		}
		
		// CSRF
		$formToken = Ajde::app()->getRequest()->getFormToken();
		$this->getView()->assign('formToken', $formToken);
		
		$this->getView()->assign('formAction', $this->getFormAction());
		$this->getView()->assign('formId', $this->getFormId());
		$this->getView()->assign('extraClass', $this->getExtraClass());
		$this->getView()->assign('innerXml', $this->getInnerXml());
		return $this->render();
	}
	 
	public function formAjaxDefault()
	{
		$this->setAction('form/ajax');
		return $this->formDefault();
	}

	public function formUploadHtml()
	{
		$this->setAction('form/upload');
		$this->getView()->assign('name', $this->getName());
		$this->getView()->assign('saveDir', $this->getSaveDir());
		$this->getView()->assign('extensions', $this->getExtensions());
		$this->getView()->assign('inputId', $this->getInputId());
		$this->getView()->assign('extraClass', $this->getExtraClass());
		return $this->render();
	}
	
	public function formUploadJson()
	{
		// Load UploadHelper.php
		$helper = new Ajde_Component_Form_UploadHelper();
		
		$saveDir = Ajde::app()->getRequest()->getSaveDir();
		$allowedExtensions = explode(',', Ajde::app()->getRequest()->getExtensions());
		
		// max file size in bytes
		$sizeLimit = 5 * 1024 * 1024;
		
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		$result = $uploader->handleUpload($saveDir);
		// to pass data through iframe you will need to encode all html tags
		return $result;
	}
	
	/************************
	 * Ajde_Component_Image
	 ************************/
	
	public function imageHtml() {
		$image = $this->getImage();
		$imageId = md5(serialize($image));
		
		$session = new Ajde_Session('AC.Image');
		$session->set($imageId, $image);
				
		$this->setAction('image/show');
		$this->getView()->assign('source', $this->getSource());
		$this->getView()->assign('width', $this->getWidth());
		$this->getView()->assign('height', $this->getHeight());
		$this->getView()->assign('extraClass', $this->getExtraClass());
		return $this->render();
	}
	
	public function imageBase64Html() {		
		$image = $this->getImage();
		
		// TODO: add crop/resize option
		$image->crop($image->getHeight(), $image->getWidth());		
		
		$this->setAction('image/base64');
		$this->getView()->assign('image', $this->getImage());
		$this->getView()->assign('width', $this->getWidth());
		$this->getView()->assign('height', $this->getHeight());
		$this->getView()->assign('extraClass', $this->getExtraClass());
		return $this->render();
	}
	
	public function imageData() {
		$imageId = Ajde::app()->getRequest()->getId();
		$session = new Ajde_Session('AC.Image');
		$image = $session->getOnce($imageId);
				
		// TODO: add crop/resize option
		$image->crop($image->getHeight(), $image->getWidth());
				
		Ajde::app()->getResponse()->addHeader('Content-Type', $image->getMimeType());
		$output = $image->getImage();
		return $output;
	}
}