<?php

class Ajde_Document_Format_Data extends Ajde_Document
{	
	public function render()
	{
		Ajde::app()->getResponse()->addHeader('Cache-control', 'public');
		//Ajde::app()->getResponse()->addHeader('Content-type', 'application/json');
		// Get the controller to output the right headers and image
		return parent::getBody();
	}
}