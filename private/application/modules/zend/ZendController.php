<?php 

class ZendController extends Ajde_Controller
{
	function view()
	{
		$time = new Zend_Date();
		return (string) $time;
	}
}
