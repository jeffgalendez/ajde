<?php

class Ajde_Document_Format_Json extends Ajde_Document
{
	protected $_cacheControl = 'no-cache';
	protected $_contentType = 'application/json';


	public function render()
	{
		Ajde::app()->getDocument()->setLayout(new Ajde_Layout('empty'));
		Ajde::app()->getResponse()->addHeader('Content-type', $this->_contentType);
		Ajde::app()->getResponse()->addHeader('Cache-control', 'no-cache');
		return parent::render();
	}
	
	public function getBody()
	{
		return json_encode($this->get('body'));
	}
}