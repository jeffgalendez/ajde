<?php 

abstract class Ajde_User_Controller extends Ajde_Controller
{		
	protected $_user = null;
	protected $_registerUserModels = array('user');
	
	protected $_allowedActions = array(
		'logon',
	);
	protected $_logonRoute = 'user/logon/html';
	
	public function beforeInvoke()
	{
		foreach($this->_registerUserModels as $model) {
			Ajde_Model::register($model);
		}
		// Add timeout warning to layout
		if ($this->_getUser() !== false) {
			Ajde_Event::register('Ajde_Layout', 'beforeGetContents', 'requireTimeoutWarning');
		}
		// TODO: possible undesired behaviour when called by Ajde_Acl_Controller,
		// when that controller is invoked with a allowed action like 'login'
		if (in_array($this->getAction(), $this->_allowedActions) || $this->_getUser() !== false) {
			return true;
		} else {
			Ajde::app()->getRequest()->set('message', __('Please log on to view this page'));
			Ajde::app()->getResponse()->dieOnCode(Ajde_Http_Response::RESPONSE_TYPE_UNAUTHORIZED);
		}
	}
	
	/**
	 * @return UserModel
	 */
	private function _getUser()
	{
		if (!isset($this->_user)) {
			$user = new UserModel();
			$this->_user = $user->getLoggedIn();
		}
		return $this->_user;
	}
	
	protected function getLoggedInUser()
	{
		return $this->_getUser();
	}	
}