<?php 
require_once 'ShopController.php';

class ShopTransactionController extends ShopController
{
	public function setup()
	{
		Ajde_Model::register($this);
		
		// Get current user, if any
		Ajde_Model::register('user');
		if ($user = UserModel::getLoggedIn()) {
			$this->getView()->assign('user', $user);
		} else {
			$this->getView()->assign('user', false);
		}
		
		// Get active providers
		$providers = Ajde_Shop_Transaction::getProviderList();
		$this->getView()->assign('providers', $providers);
		
		return $this->render();
	}
	
	public function setup2()
	{
		return $this->render();
	}
}
