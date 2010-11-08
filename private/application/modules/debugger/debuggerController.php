<?php

class DebuggerController extends Ajde_Controller
{
	function viewDefault()
	{
		$this->getView()->assign('timer', round(Ajde::app()->getTimer() * 1000, 0));
		$this->getView()->assign('request', Ajde::app()->getRequest());
		$this->getView()->assign('configstage', Config::$stage);
		
		if (Ajde_Core_Autoloader::exists('AjdeExtension_Db_PDO')) {
			$this->getView()->assign('database', AjdeExtension_Db_PDO::getLog());
		}
				
		return $this->render();
	}
}