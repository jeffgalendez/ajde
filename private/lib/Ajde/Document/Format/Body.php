<?php

class Ajde_Document_Format_Body extends Ajde_Document
{
	protected $_cacheControl = 'private';
	
	public function render()
	{		
		Ajde::app()->getDocument()->setLayout(new Ajde_Layout('empty'));
		Ajde::app()->getResponse()->addHeader('Content-type', 'text/html');
		Ajde::app()->getResponse()->addHeader('Cache-control', 'no-cache');
		return parent::render();
	}
	
	public function getBody()
	{
		return $this->get('body');
	}
}