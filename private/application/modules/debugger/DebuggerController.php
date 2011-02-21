<?php

class DebuggerController extends Ajde_Controller
{
	function viewDefault()
	{
		$view = $this->getView();
		if (Ajde_Dump::getAll()) {
			$view->assign('dump', Ajde_Dump::getAll());			
		}
		
		$view->assign('request', Ajde::app()->getRequest());
		$view->assign('configstage', Config::$stage);
		
		if (Ajde_Core_Autoloader::exists('AjdeExtension_Db_PDO')) {
			$view->assign('database', AjdeExtension_Db_PDO::getLog());
		}
		
		Ajde::app()->endTimer(0);
		Ajde::app()->endTimer(Ajde::app()->getLastTimerKey());
		
		$view->assign('timers', Ajde::app()->getTimers());
			
		return $this->render();
	}
}