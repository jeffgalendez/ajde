<?php 

class AclController extends AjdeX_Acl_Controller
{	
	protected $_logonRoute = 'user/logon/html';
	
	protected function getOwnerId()
	{
		if ($owner = Ajde::app()->getRequest()->getParam('owner', false)) {
			return $owner;
		}
		return false;
	}
	
	public function view()
	{
		return $this->render();
	}
	
	public function edit()
	{
		return $this->render();
	}
	
	public function denied()
	{
		return $this->render();
	}
	
	public function owner()
	{
		return $this->render();
	}
}
