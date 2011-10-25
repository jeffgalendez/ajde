<?php 

abstract class AjdeX_Acl_Controller extends AjdeX_User_Controller
{		
	protected $_aclCollection = null;
	
	protected $_registerAclModels = array('acl');
	
	/* ACL sets this to true or false to grant/prevent access in beforeInvoke() */
	private $_hasAccess;
	
	public function beforeInvoke()
	{
		if (parent::beforeInvoke() === false) {
			return false;
		}
		foreach($this->_registerAclModels as $model) {
			AjdeX_Model::register($model);
		}
		if ($this->hasAccess() === false) {
			Ajde::app()->getRequest()->set('message', __('No access'));
			Ajde::app()->getResponse()->dieOnCode(401);
		} else {			
			return true;
		}
	}
	
	abstract protected function getOwnerId();
	
	/**
	 * @return AclCollection
	 */
	protected function getAclCollection()
	{
		if (!isset($this->_aclCollection)) {
			$this->_aclCollection = new AclCollection();
		}
		return $this->_aclCollection;
	}
	
	/**
	 *
	 * @return UserModel
	 */
	protected function getUser()
	{
		// We certainly have a valid user here, otherwise beforeInvoke() on the
		// parent class would have returned false
		$user = UserModel::getLoggedIn();
		return $user;		
	}
	
	public function validateAccess()
	{
		$user = $this->getUser();
		$uid = $user->getPK();
		$usergroup = $user->getUsergroup();
		$module = $this->getModule();
		$action = $this->getAction();
		
		return $this->validateAclFor($uid, $usergroup, $module, $action);
	}
	
	private function validateAclFor($uid, $usergroup, $module, $action)
	{
		/**
		 * TODO: Goddammit this code is ugly (sorry)...
		 */
		
		/**
		 * Allright, this is how things go down here:
		 * We want to check for at least on allowed or owner record in this direction:
		 * 
		 * 1. Wildcard usergroup AND module/action
		 * 2. Wildcard user AND module/action
		 * 3. Specific usergroup AND module/action
		 * 4. Specific user AND module/action
		 * 
		 * Module/action goes down in this order:
		 * 
		 * A. Wildcard module AND wildcard action
		 * B. Wildcard module AND specific action
		 * C. Specific module AND wildcard action
		 * D. Specific module AND specific action
		 * 
		 * This makes for 16 checks.
		 * 
		 * If a denied record is found and no allowed or owner record is present
		 * further down, deny access.
		 */
		
		$access = false;
		
		$moduleAction = array(
			"A" => array(
				'module' => '*',
				'action' => '*'
			),
			"B" => array(
				'module' => '*',
				'action' => $action
			),
			"C" => array(
				'module' => $module,
				'action' => '*'
			),
			"D" => array(
				'module' => $module,
				'action' => $action
			)
		);
		
		$userGroup = array(
			array('usergroup',	null),
			array('user',		null),
			array('usergroup',	$usergroup),
			array('user',		$uid)
		);
		
		/**
		 * Allright, let's prepare the SQL!
		 */
		
		$moduleActionWhereGroup = new AjdeX_Filter_WhereGroup(AjdeX_Query::OP_AND);
		foreach($moduleAction as $moduleActionPart) {
			$group = new AjdeX_Filter_WhereGroup(AjdeX_Query::OP_OR);
			foreach($moduleActionPart as $key => $value) {
				$group->addFilter(new AjdeX_Filter_Where($key, AjdeX_Filter::FILTER_EQUALS, $value, AjdeX_Query::OP_AND));
			}
			$moduleActionWhereGroup->addFilter($group);
		}
		
		$rules = $this->getAclCollection();
		$rules->reset();
		
		foreach($userGroup as $userGroupPart) {
			$group = new AjdeX_Filter_WhereGroup(AjdeX_Query::OP_OR);
			$comparison = is_null($userGroupPart[1]) ? AjdeX_Filter::FILTER_IS : AjdeX_Filter::FILTER_EQUALS;
			$group->addFilter(new AjdeX_Filter_Where('type', AjdeX_Filter::FILTER_EQUALS, $userGroupPart[0], AjdeX_Query::OP_AND));
			$group->addFilter(new AjdeX_Filter_Where($userGroupPart[0], $comparison, $userGroupPart[1], AjdeX_Query::OP_AND));
			$group->addFilter($moduleActionWhereGroup, AjdeX_Query::OP_AND);			
			$rules->addFilter($group, AjdeX_Query::OP_OR);
		}
		
		$rules->load();
		
		/**
		 * Oempfff... now let's travers and set the order
		 */
		
		$orderedRules = array();
		foreach($userGroup as $userGroupPart) {
			$type	= $userGroupPart[0];
			$ugId	= $userGroupPart[1];
			foreach($moduleAction as $moduleActionPart) {
				$module = $moduleActionPart['module'];
				$action = $moduleActionPart['action'];
				$rule = $rules->find($type, $ugId, $module, $action);
				if ($rule !== false) {
					$orderedRules[] = $rule;
				}
			}
		}
		
		/**
		 * Finally, determine access
		 */
				
		foreach($orderedRules as $rule) {
			switch ($rule->permission) {
				case "deny":
					AjdeX_Acl::$log[] = 'ACL rule id ' . $rule->getPK() . ' denies access';
					$access = false;
					break;
				case "own":
					if ((int) $this->getOwnerId() === (int) $uid) {
						AjdeX_Acl::$log[] = 'ACL rule id ' . $rule->getPK() . ' allows access (owner)';
						$access = true;
					} else {
						AjdeX_Acl::$log[] = 'ACL rule id ' . $rule->getPK() . ' denies access (owner)';
						// TODO: or inherit?
						$access = false;
					}
					break;
				case "allow":
					AjdeX_Acl::$log[] = 'ACL rule id ' . $rule->getPK() . ' allows access';
					$access = true;
					break;
			}
		}
		AjdeX_Acl::$access = $access;
		return $access;
		
	}
	
	protected function hasAccess()
	{
		if (!isset($this->_hasAccess)) {
			$this->_hasAccess = $this->validateAccess();
		}
		return $this->_hasAccess;
	}
}