<?php 

class SecurityController extends Ajde_Controller
{
	function view()
    {
		return $this->render();
	}
	
	function xss()
    {
    	Config::getInstance()->autoEscapeString = false;
		Config::getInstance()->autoCleanHtml = false;
    	$xsstest = Ajde::app()->getRequest()->getParam('xsstest', '');
		$safe = false;
    	if (Ajde::app()->getRequest()->getParam('escape', 'on') == 'on') {
    		$safe = true;
    	}
		$this->getView()->assign('safe', $safe);
		$this->getView()->assign('xsstest', $xsstest);
		return $this->render();
	}
	
	function csrf()
    {
    	
		return $this->render();
	}
	
	function csrfTarget() {
		return Ajde::app()->getRequest()->get('csrftest');
	}
	function csrfTargetJson() {
		return array('result' => Ajde::app()->getRequest()->get('csrftest'));
	}
	
	function csrfAttack()
    {
    	
		return $this->render();
	}
}
