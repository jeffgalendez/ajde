<?php

class Ajde_Shop_Transaction_Provider_Wedeal extends Ajde_Shop_Transaction_Provider
{
	public function getName() {
		return 'iDeal';
	}
	
	public function getLogo() {
		return 'public/images/_core/shop/ideal.png';
	}
	
	public function usePostProxy() {
		return true;
	}
	
	public function getRedirectUrl() {
		return 'https://www.paypal.com/cgi-bin/webscr';
	}
	
	public function getRedirectParams() {
		return array();
	}
	
	public function updatePayment() {
		
	}
}