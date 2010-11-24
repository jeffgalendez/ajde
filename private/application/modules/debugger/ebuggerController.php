<?php

class DebuggerController extends Ajde_Controller
{
	function viewDefault()
	{		
		$this->getView()->assign('request', Ajde::app()->getRequest());
		$this->getView()->assign('configstage', Config::$stage);
		
		if (Ajde_Core_Autoloader::exists('AjdeExtension_Db_PDO')) {
			$this->getView()->assign('database', AjdeExtension_Db_PDO::getLog());
		}
		
		Ajde::app()->endTimer(0);
		Ajde::app()->endTimer(Ajde::app()->getLastTimerKey());
		
		$this->getView()->assign('timers', Ajde::app()->getTimers());
			
		return $this->render();
	}
}