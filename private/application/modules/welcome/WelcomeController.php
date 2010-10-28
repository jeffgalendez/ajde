<?php 

class WelcomeController extends Ajde_Controller
{	
	function viewDefault()
	{
		return $this->render();
	}
	
	function dbDefault()
	{
		$db = AjdeExtension::load('db')->getConnection();
		$result = $db->query("SELECT * FROM test");
		$this->getView()->assign('test', $result);
		return $this->render();	
	}
	
	function zendDefault()
	{
		$date = new Zend_Date();
//		$app = new Zend_Application('live');
//		$app->run();
		
		$view = $this->getView();		
		$view->assign('date', $date);		
		return $view->render();
	}
}
