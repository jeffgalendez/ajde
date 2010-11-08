<?php

class Ajde_Document_Format_Processor_Html_Debugger extends Ajde_Object_Static implements Ajde_Document_Format_Processor
{
	public static function preProcess(Ajde_Layout $layout) {
		// only here to add resources
		Ajde_Controller::fromRoute(new Ajde_Core_Route('debugger'))->invoke();
	}
	
	public static function postProcess(Ajde_Layout $layout) {
		$debugger = Ajde_Controller::fromRoute(new Ajde_Core_Route('debugger'))->invoke();
		$layout->setContents($layout->getContents() . $debugger);		
	}	
}