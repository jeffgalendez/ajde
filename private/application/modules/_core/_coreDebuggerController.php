<?php

class _coreDebuggerController extends Ajde_Controller
{
	function view()
	{
		$view = $this->getView();
		if (Ajde_Dump::getAll()) {
			$view->assign('dump', Ajde_Dump::getAll());			
		}
		
		$view->assign('request', Ajde::app()->getRequest());
		$view->assign('configstage', Config::$stage);
		
		if (Ajde_Core_Autoloader::exists('AjdeX_Db_PDO')) {
			$view->assign('database', AjdeX_Db_PDO::getLog());
		}
		
		Ajde::app()->endTimer(0);
		Ajde::app()->endTimer(Ajde::app()->getLastTimerKey());
		
		$view->assign('timers', Ajde::app()->getTimers());
			
		return $this->render();
	}
}