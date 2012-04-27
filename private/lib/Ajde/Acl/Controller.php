<?php 

abstract class Ajde_Acl_Controller extends Ajde_User_Controller
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
			Ajde_Model::register($model);
		}
		Ajde_Acl::$access = false;
		if (!in_array($this->getAction(), $this->_allowedActions) && $this->hasAccess() === false) {
			Ajde::app()->getRequest()->set('message', __('No access'));
			Ajde::app()->getResponse()->dieOnCode(Ajde_Http_Response::RESPONSE_TYPE_UNAUTHORIZED);
		} else {			
			return true;
		}
	}
	
	protected function getOwnerId()
	{
		return false;
	}
	
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
		if ($user !== false) {
			$uid = $user->getPK();
			$usergroup = $user->getUsergroup();	
		} else {
			// We should never get here, see remark at self::getUser()
			Ajde_Dump::warn("No logged in user found when validating ACL access");
			$uid = null;
			$usergroup = null;
		}
		$module = $this->getModule();
		$action = $this->getAction();
		
		return $this->validateAclFor($uid, $usergroup, $module, $action);
	}
	
	private function validateAclFor($uid, $usergroup, $module, $action)
	{
		/**
		 * TODO: Nasty code...
		 */
		
		/**
		 * Allright, this is how things go down here:
		 * We want to check for at least one allowed or owner record in this direction:
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
		
		$moduleActionWhereGroup = new Ajde_Filter_WhereGroup(Ajde_Query::OP_AND);
		foreach($moduleAction as $moduleActionPart) {
			$group = new Ajde_Filter_WhereGroup(Ajde_Query::OP_OR);
			foreach($moduleActionPart as $key => $value) {
				$group->addFilter(new Ajde_Filter_Where($key, Ajde_Filter::FILTER_EQUALS, $value, Ajde_Query::OP_AND));
			}
			$moduleActionWhereGroup->addFilter($group);
		}
		
		$rules = $this->getAclCollection();
		$rules->reset();
		
		foreach($userGroup as $userGroupPart) {
			$group = new Ajde_Filter_WhereGroup(Ajde_Query::OP_OR);
			$comparison = is_null($userGroupPart[1]) ? Ajde_Filter::FILTER_IS : Ajde_Filter::FILTER_EQUALS;
			$group->addFilter(new Ajde_Filter_Where('type', Ajde_Filter::FILTER_EQUALS, $userGroupPart[0], Ajde_Query::OP_AND));
			$group->addFilter(new Ajde_Filter_Where($userGroupPart[0], $comparison, $userGroupPart[1], Ajde_Query::OP_AND));
			$group->addFilter($moduleActionWhereGroup, Ajde_Query::OP_AND);			
			$rules->addFilter($group, Ajde_Query::OP_OR);
		}
		
		$rules->load();
		
		/**
		 * Oempfff... now let's traverse and set the order
		 * 
		 * TODO: It seems that we can just load the entire ACL table in the collection
		 * and use this traversal to find matching rules instead of executing this
		 * overly complicated SQL query constructed above...
		 */
		
		$orderedRules = array();
		foreach($userGroup as $userGroupPart) {
			$type	= $userGroupPart[0];
			$ugId	= $userGroupPart[1];
			foreach($moduleAction as $moduleActionPart) {
				$module = $moduleActionPart['module'];
				$action = $moduleActionPart['action'];
				$rule = $rules->findRule($type, $ugId, $module, $action);
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
					Ajde_Acl::$log[] = 'ACL rule id ' . $rule->getPK() . ' denies access for '.$module.'/'.$action;
					$access = false;
					break;
				case "own":
					if ((int) $this->getOwnerId() === (int) $uid) {
						Ajde_Acl::$log[] = 'ACL rule id ' . $rule->getPK() . ' allows access for '.$module.'/'.$action.' (owner)';
						$access = true;
					} else {
						Ajde_Acl::$log[] = 'ACL rule id ' . $rule->getPK() . ' denies access for '.$module.'/'.$action.' (owner)';
						// TODO: or inherit?
						$access = false;
					}
					break;
				case "allow":
					Ajde_Acl::$log[] = 'ACL rule id ' . $rule->getPK() . ' allows access for '.$module.'/'.$action;
					$access = true;
					break;
			}
		}
		Ajde_Acl::$access = $access;
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