<?php

class Ajde_Document_Format_Data extends Ajde_Document
{	
	protected $_cacheControl = 'public';
	
	public function render()
	{
		Ajde::app()->getResponse()->addHeader('Cache-Control', $this->_cacheControl);
		$this->removeSetCookieHeader();
		// Get the controller to output the right headers and body
		return parent::getBody();
	}
}