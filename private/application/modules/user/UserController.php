<?php 

class UserController extends Ajde_User_Controller
{
	protected $_allowedActions = array(
		'logon',
		'register',
		'keepalive'
	);
	protected $_logonRoute = 'user/logon/html';
	
	// Default to profile
	public function view()
	{
		return $this->profile();
	}
	
	public function keepaliveJson()
	{
		return array('success' => true);
	}
	
	// Profile
	public function profile()
	{
		$user = $this->getLoggedInUser();
		$this->setAction('profile');
		$user->refresh();
		$user->login();
		$this->getView()->assign('user', $user);
		
		return $this->render();
	}
	
	// Logon
	public function logonHtml()
	{
		if (($user = $this->getLoggedInUser())) {
			if (($returnto = Ajde::app()->getRequest()->getParam('returnto', false))) {
				return $this->redirect($returnto);
			}
			$this->setAction('relogon');
			$message = Ajde::app()->getRequest()->getParam('message', '');
			$this->getView()->assign('message', $message);			
			$this->getView()->assign('user', $user);
			return $this->render();
		} else {
			$user = new UserModel();
			$this->setAction('logon');
			$message = Ajde::app()->getRequest()->getParam('message', '');
			$this->getView()->assign('message', $message);
			$this->getView()->assign('user', $user);
			$this->getView()->assign('returnto', Ajde::app()->getRequest()->getParam('returnto', $_SERVER['REDIRECT_STATUS'] == 200 ? 'user' : false));
			return $this->render();
		}		
	}
		
	public function logonJson()
	{
		$user = new UserModel();
		
		$username	= Ajde::app()->getRequest()->getPostParam($user->usernameField);
		$password	= Ajde::app()->getRequest()->getPostParam('password');
		$rememberme = Ajde::app()->getRequest()->hasPostParam('rememberme');
				
		$return = array(false);
		
		if (false !== $user->loadByCredentials($username, $password)) {
			$user->login();
			if ($rememberme === true) {
				$user->storeCookie();
			}
			$return = array('success' => true);
		} else {
			$return = array(
				'success' => false,
				'message' => __("Logon not successful")
			);			
		}		
		return $return;
	}
	
	// Logoff
	public function logoff()
	{
		if (($user = $this->getLoggedInUser())) {
			$user->logout();
		}
		$this->redirect(Ajde_Http_Response::REDIRECT_REFFERER);
	}
	
	public function registerHtml()
	{
		$user = new UserModel();
		$this->getView()->assign('user', $user);
		return $this->render();
	}
	
	public function registerJson()
	{
		$user = new UserModel();
		
		$username		= Ajde::app()->getRequest()->getPostParam($user->usernameField);
		$password		= Ajde::app()->getRequest()->getPostParam('password');
		$passwordCheck	= Ajde::app()->getRequest()->getPostParam('passwordCheck');
		$email			= Ajde::app()->getRequest()->getPostParam('email', false);
		$fullname		= Ajde::app()->getRequest()->getPostParam('fullname', false);
		
		$return = array(false);
		
		$shadowUser = new UserModel();

		if (empty($username) || empty($password)) {
			$return = array(
				'success' => false,
				'message' => __("Please provide ".$user->usernameField." and password")
			);	
		} else if ($shadowUser->loadByField($shadowUser->usernameField, $username)) {
			$return = array(
				'success' => false,
				'message' => __(ucfirst($user->usernameField) . " already exist")
			);	
		} else if ($password !== $passwordCheck) {
			$return = array(
				'success' => false,
				'message' => __("Passwords do not match")
			);	
		} else {
			$user->set('email', $email);
			$user->set('fullname', $fullname);
			if ($user->add($username, $password)) {
				$return = array('success' => true);
			} else {
				$return = array(
					'success' => false,
					'message' => __("Something went wrong")
				);	
			}			
		}
		return $return;
	}
}
