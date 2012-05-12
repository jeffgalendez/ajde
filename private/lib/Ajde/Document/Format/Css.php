<?php

class Ajde_Document_Format_Css extends Ajde_Document
{
	protected $_cacheControl = 'public';
	protected $_contentType = 'text/css';

	public function render()
	{
		Ajde::app()->getDocument()->setLayout(new Ajde_Layout('empty'));
		$this->setContentTypeHeader();
		$this->setCacheControlHeader();
		$this->removeSetCookieHeader();
		if (Ajde::app()->getRequest()->getRoute()->getAction() == 'resourceCompressed') {
			$this->registerDocumentProcessor('css', 'compressor');
		} else {
			$this->registerDocumentProcessor('css');
		}
		return parent::render();		
	}	
}