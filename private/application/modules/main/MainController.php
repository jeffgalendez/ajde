<?php 

class MainController extends Ajde_Controller
{
	function view()
	{
		return $this->render();
	}
	
	function code404()
	{
		return $this->render();
	}
	
	function nojavascript()
	{
		die($this->render());
	}
	
	function nocookies()
	{
		// set a cookie so a user can change settings in browsers which only
		// gives users the choice to enable cookies when a website tries to set one
		$session = new Ajde_Session('_ajde');
		die($this->render());
	}
}
