<?php 

class UserController extends AjdeX_Acl_Controller
{
	protected $_allowedActions = array(
		'logon',
		'register'
	);
	
	// Default to profile
	public function view()
	{
		$this->setView(Ajde_View::fromRoute('user/profile'));
		return $this->profile();
	}
	
	// Profile
	public function profile()
	{
		$user = $this->_getUser();
		$user->refresh();
		$user->login();
		$this->getView()->assign('user', $user);
		
		return $this->render();
	}
	
	// Logon
	public function logonHtml()
	{			
		if ($user = $this->_getUser()) {
			if ($redirect = Ajde::app()->getRequest()->getParam('redirect', false)) {
				return $this->redirect($redirect);
			}
			$this->setAction('view');
			return $this->view();
		} else {
			$this->getView()->assign('redirect', Ajde::app()->getRequest()->getParam('redirect', false));
			return $this->render();
		}		
	}
		
	public function logonJson()
	{
		// Use HKU LDAP server for authentication		
		$email = Ajde::app()->getRequest()->getParam('email');
		$password = Ajde::app()->getRequest()->getParam('password');
		
		AjdeX_Model::register($this);		
		$user = new UserModel();		
		
		$status = $user->getStatus($email, $password);
		
		switch($status['code']) {
			case UserModel::STATUS_USER_FOUND_UNREGISTERED:
				$user->add($email, $status['ldap']->getFullname());
			case UserModel::STATUS_USER_FOUND_REGISTERED:
				// See if we can link with Facebook
				if (!$user->facebook) {
					$facebook 	= new FacebookModel();
					$uid 		= $facebook->getUid();
					if ($uid) {
						$user->facebook = $uid;
						$user->save();
					}
				}
				$user->login();
				$return = array('success' => true);
				break;			
			case UserModel::STATUS_USER_NOTFOUND:
			default:
				$return = array(
					'success' => false,
					'message' => __("EmailPasswordCombinationNotFound")
				);			
				break;
		}
		return $return;
	}
	
	// Facebook Connect
	public function facebookConnect()
	{
		// Provide template with loggedin state so we can send callback when user
		// is logged in at facebook but not locally
		$this->getView()->assign('userLoggedOn', $this->_getUser() !== false ? "1" : "0");
		// Provide template with ignoreFacebook bit, if set then user just logged out
		// and we want the opportunity to log in as a different user
		$session = new Ajde_Session('user');
		$ignoreFacebook = ($session->hasIgnoreFacebook() && $session->getIgnoreFacebook());
		$this->getView()->assign('ignoreFacebook', $ignoreFacebook === true ? "1" : "0");
		return $this->render();
	}
	
	public function facebookChannel()
	{
		$content = $this->render();
		echo $content;
		exit;
	}
	
	public function facebookLogonJson()
	{
		AjdeX_Model::register($this);			
		$facebook 	= new FacebookModel();
		$user 		= new UserModel();
		
		$uid 		= $facebook->getUid();
		$found		= $user->loadByField('facebook', $uid);
		
		if ($found) {
			$user->login();
			return array(
				'success' => true
			);
		} else {
			return array(
				'success' => false,
				'message' => __("LogonWithHKUAccountToLinkFacebook")
			);
		}
		
		
	}

	// Logoff
	public function logoff()
	{
		if ($user = $this->_getUser()) {
			$user->logout();
		}
		$this->redirect(Ajde_Http_Response::REDIRECT_HOMEPAGE);
	}
}
