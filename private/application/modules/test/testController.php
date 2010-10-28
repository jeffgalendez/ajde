<?php 

class TestController extends Ajde_Controller
{	
	function viewDefault() {
		return $this->render();
	}
	
	function testDefault() {
		return $this->render();		
	}
	
	function output($data) {
		$view = Ajde_View::fromRoute('test/output');		
		$view->assign('output', $data);		
		return $view->render();
	}
	
	function zendDefault() {
		$date = new Zend_Date();		
		return $this->output($date);		
	}
	
	function nestedtemplateDefault() {
		$this->setAction( 'nestedtemplate/output' );
		return $this->render();
	}
	
	function includeXhtmlDefault() {
		return $this->render();		
	}
	
	function cssXhtmlDefault() {
		return $this->render();		
	}
}
