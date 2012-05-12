<?php 

class ShopController extends Ajde_Acl_Controller
{
	protected $_allowedActions = array(
		'view'
	);
	
	protected $_allowGuestTransaction = true;
	
	public function beforeInvoke()
	{
		if ($this->_allowGuestTransaction === true) {
			$this->_allowedActions[] = $this->getAction();
		}
		return parent::beforeInvoke();
	}
	
	public function view()
	{
		return $this->render();
	}
	
	public function cart()
	{
		$this->redirect('shop/cart:edit');
	}
	
	public function checkout()
	{
		Ajde_Model::register($this);
		$cart = new CartModel();
		$cart->loadCurrent();
		
		$this->getView()->assign('cart', $cart);
		$this->getView()->assign('user', $this->getLoggedInUser());
		
		return $this->render();
	}
}
