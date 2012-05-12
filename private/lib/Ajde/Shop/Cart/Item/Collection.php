<?php

class Ajde_Shop_Cart_Item_Collection extends Ajde_Collection
{	
	protected $_autoloadParents = false;
		
	public function __construct(Ajde_Shop_Cart $cart = null) {
		parent::__construct();
		if (isset($cart)) {
			$this->setCart($cart);
			$this->addFilter(new Ajde_Filter_Where('cart', Ajde_Filter::FILTER_EQUALS, $cart->getPK()));
		}
	}
	
	protected function _format($value)
	{
		return money_format('%!i', $value);
	}
	
	public function getTotal()
	{
		$total = 0;
		foreach($this as $item) {
			$total = $total + $item->getTotal();
		}
		return $total;
	}
	
	public function getFormattedTotal()
	{
		return Config::get('currency') . ' ' . $this->_format($this->getTotal());
	}
}