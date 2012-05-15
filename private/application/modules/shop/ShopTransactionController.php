<?php 
require_once 'ShopController.php';

class ShopTransactionController extends ShopController
{
	public function view()
	{
		Ajde_Model::register($this);			
		$transaction = new TransactionModel();
		
		// Get from ID
		if ($this->hasId()) {
			if ($transaction->loadByField('secret', $this->getId()) !== false) {
				$this->getView()->assign('source', 'id');
			}			
		} else  {
			// Get existing transaction / user details
			$session = new Ajde_Session('AC.Shop');
			if ($session->has('currentTransaction') && $transaction->loadByPK($session->get('currentTransaction'))) {
				$this->getView()->assign('source', 'session');
			}
		}
		
		$this->getView()->assign('transaction', $transaction);
		return $this->render();
	}
		
	public function setup()
	{
		Ajde_Model::register($this);
		Ajde_Model::register('user');
				
		// Get existing transaction / user details
		$transaction = new TransactionModel();
		$session = new Ajde_Session('AC.Shop');
		$user = UserModel::getLoggedIn();
				
		if ($session->has('currentTransaction') && $transaction->loadByPK($session->get('currentTransaction'))) {
			$name		= $transaction->name;
			$email		= $transaction->email;			
			$address	= $transaction->shipment_address;
			$zipcode	= $transaction->shipment_zipcode;			
			$city		= $transaction->shipment_city;
			$region		= $transaction->shipment_region;
			$country	= $transaction->shipment_country;
			$comment	= $transaction->comment;			
		} elseif ($user !== false) {
			// Insert intermediate transaction to save country and present user
			// with shipping options, ignore response
			$this->setupJson($user);
			
			$name		= $user->fullname;
			$email		= $user->email;			
			$address	= $user->address;
			$zipcode	= $user->zipcode;			
			$city		= $user->city;
			$region		= $user->region;
			$country	= $user->country;
			$comment	= '';
		} else {
			$name		= '';
			$email		= '';
			$address	= '';
			$zipcode	= '';
			$city		= '';
			$region		= '';
			$country	= '';
			$comment	= '';
		}
		
		$view = $this->getView();
		$view->assign('name', $name);
		$view->assign('email', $email);
		$view->assign('address', $address);
		$view->assign('zipcode', $zipcode);
		$view->assign('city', $city);
		$view->assign('region', $region);
		$view->assign('country', $country);
		$view->assign('comment', $comment);
		$view->assign('user', $user);
		return $this->render();
	}	
	
	public function shipment()
	{
		Ajde_Model::register($this);		
		$transaction = new TransactionModel();
		
		$session = new Ajde_Session('AC.Shop');
		if ($session->has('currentTransaction') && $transaction->loadByPK($session->get('currentTransaction'))) {
			$shipment = new ShippingModel($transaction);
			$method = $transaction->shipment_method;
			if (empty($method) || !$shipment->isAvailable($method)) {
				$method = $shipment->getFirstMethod()->getName();
			}
		} else {
			$shipment = false;
			$method = false;
		}
		
		$this->getView()->assign('method', $method);
		$this->getView()->assign('shipment', $shipment);
		return $this->render();
	}
	
	public function setupJson($fromUser = false)
	{
		$request		= Ajde::app()->getRequest();
		
		if ($fromUser === false) {
			// Read request			
			$name			= $request->getPostParam('name', false);
			$email			= $request->getPostParam('email', false);		
			$address		= $request->getPostParam('shipment_address', false);
			$zipcode		= $request->getPostParam('shipment_zipcode', false);
			$city			= $request->getPostParam('shipment_city', false);
			$region			= $request->getPostParam('shipment_region', false);
			$country		= $request->getPostParam('shipment_country', false);
			$shipmentMethod	= $request->getPostParam('shipment_method', false);
			$comment		= $request->getPostParam('comment', false);
		} else {
			// Read user
			$name			= $fromUser->fullname;
			$email			= $fromUser->email;			
			$address		= $fromUser->address;
			$zipcode		= $fromUser->zipcode;			
			$city			= $fromUser->city;
			$region			= $fromUser->region;
			$country		= $fromUser->country;
			$shipmentMethod	= false;
			$comment		= false;
		}
		
		// Return when fields are not complete
		if ($fromUser === false) {
			if (
					empty($name) ||
					empty($email) ||
					empty($address) ||
					empty($zipcode) ||
					empty($city) ||
					empty($country)
				) {
				return array(
					'success' => false,
					'message' => __("Not all your details are filled out")
				);
			}
			if (Ajde_Component_String::validEmail($email) === false) {
				return array(
					'success' => false,
					'message' => __('Please provide a valid e-mail address')
				);
			}
			if (empty($shipmentMethod)) {
				return array(
					'success' => false,
					'message' => __('Please choose a shipment method')
				);
			}
		}
			
		// Check for current transaction
		Ajde_Model::register($this);
		$transaction = new TransactionModel();
		$session = new Ajde_Session('AC.Shop');
		if ($session->has('currentTransaction') && $transaction->loadByPK($session->get('currentTransaction'))) {
			// Edit existing transaction
			$method = 'save';
		} else {
			// Insert new transaction
			$method = 'insert';
		}
		
		// Update transaction info
		$transaction->name				= $name;
		$transaction->email				= $email;				
		$transaction->shipment_address	= $address;
		$transaction->shipment_zipcode	= $zipcode;
		$transaction->shipment_city		= $city;
		$transaction->shipment_region	= $region;
		$transaction->shipment_country	= $country;
		$transaction->shipment_method	= $shipmentMethod;
		$transaction->comment			= $comment;

		// Save info to user
		if ($user = $this->getLoggedInUser()) {
			if ($request->hasPostParam('save_details', false)) {
				$user->address	= $address;
				$user->zipcode	= $zipcode;
				$user->city		= $city;
				$user->region	= $region;
				$user->country	= $country;
				$user->save();
				$user->login();
			}
			$transaction->user = $user->getPK();
		}
		
		// Update shipping total
		$shipping = new ShippingModel($transaction);
		$transaction->shipment_cost = 0;
		if (!empty($shipmentMethod) && $shipping->isAvailable($shipmentMethod)) {
			$transaction->shipment_cost = $shipping->getMethod($shipmentMethod)->getTotal();
		}
		
		// Insert new transaction
		if ($method === 'insert') {				
			$this->updateFromCart($transaction);
			if ($transaction->insert()) {					
				$session = new Ajde_Session('AC.Shop');
				$session->set('currentTransaction', $transaction->getPK());				
				if (!$transaction->shipment_itemsqty > 0) {
					return array(
						'success' => false,
						'message' => __("No items added to current transaction")
					);
				}
				return array(
					'success' => true
				);
			}
			return array(
				'success' => false,
				'message' => __("Something went wrong")
			);
		}
		
		$transaction->payment_amount = $transaction->shipment_itemstotal + $transaction->shipment_cost;
		
		// Update current transaction
		if ($transaction->save()) {
			if (!$transaction->shipment_itemsqty > 0) {
				return array(
					'success' => false,
					'message' => __("No items added to current transaction")
				);
			}
			return array(
				'success' => true
			);
		}
		
		// Everything else failed
		return array(
			'success' => false,
			'message' => __("Something went wrong")
		);
	}
	
	public function updateFromCart(Ajde_Shop_Transaction $transaction)
	{			
		$cart = new CartModel();
		$cart->loadCurrent();

		if ($cart->countItems() > 0) {
			$transaction->shipment_description		= $cart->getHtmlSummaryTable();
			$transaction->shipment_itemsqty			= $cart->countQty();
			$transaction->shipment_itemsvatamount	= $cart->getItems()->getVATAmount();
			$transaction->shipment_itemstotal		= $cart->getItems()->getTotal();
			$transaction->payment_amount			= $transaction->shipment_itemstotal + $transaction->shipment_cost;
		} else {
			$transaction->shipment_description		= '';
			$transaction->shipment_itemsqty			= 0;
			$transaction->shipment_itemsvatamount	= 0;
			$transaction->shipment_itemstotal		= 0;
			$transaction->payment_amount			= 0;
		}
	}
	
	public function update()
	{
		// Check for current transaction
		Ajde_Model::register($this);
		$transaction = new TransactionModel();
		$session = new Ajde_Session('AC.Shop');
		if ($session->has('currentTransaction') && $transaction->loadByPK($session->get('currentTransaction'))) {
			$this->updateFromCart($transaction);
			$transaction->save();
		}
		$this->setAction('view');
		return $this->view();
	}
	
	public function cancel()
	{
		Ajde_Model::register($this);
	
		// Edit existing transaction?
		$transaction = new TransactionModel();
		$session = new Ajde_Session('AC.Shop');
		if ($session->has('currentTransaction') && $transaction->loadByPK($session->get('currentTransaction'))) {
			$transaction->payment_status = 'cancelled';
			$transaction->save();
			$session->destroy();
		}				
		$this->redirect('shop/checkout');
	}
	
	public function payment()
	{
		Ajde_Model::register($this);		
		$transaction = new TransactionModel();		
		
		$session = new Ajde_Session('AC.Shop');
		if ($session->has('currentTransaction') && $transaction->loadByPK($session->get('currentTransaction'))) {
			if (!$transaction->shipment_itemsqty > 0) {				
				$this->redirect('shop');
			}
		}
				
		$this->getView()->assign('transaction', $transaction);		
		return $this->render();
	}
		
	public function paymentJson()
	{
		$request = Ajde::app()->getRequest();	
		$provider = $request->getPostParam('provider', false);
		
		if (empty($provider)) {
			return array(
				'success' => false,
				'message' => __('Please choose a payment provider')
			);
		}
		
		// Check for current transaction
		Ajde_Model::register($this);
		$transaction = new TransactionModel();
		$session = new Ajde_Session('AC.Shop');
		if ($session->has('currentTransaction') && $transaction->loadByPK($session->get('currentTransaction'))) {			
		} else {			
			return array(
				'success' => false,
				'message' => __('Payment already processing')
			);
		}
		
		$transaction->payment_provider = $provider;
		$transaction->payment_status = 'requested';
		$transaction->save();
		
		$provider = $transaction->getProvider();
		
		$session = new Ajde_Session('AC.Shop');
		$session->destroy();
		
		$cart = new CartModel();
		$cart->loadCurrent();
		$cart->emptyItems();
		
		if ($provider->usePostProxy()) {
			$this->setAction('postproxy');
			$proxy = $this->getView();
			$proxy->assign('provider', $provider);
			return array(
				'success' => true,
				'postproxy' => $proxy->render()
			);			
		}
		
		return array(
			'success' => true
		);
	}
	
	public function complete()
	{
		return $this->render();
	}
	
	public function callback()
	{
		$providerName = $this->getId();
		$provider = Ajde_Shop_Transaction_Provider::getProvider($providerName);
		$provider->updatePayment();
	}
}
