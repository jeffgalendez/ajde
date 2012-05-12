<?php

interface Ajde_Shop_Transaction_Provider_Interface
{
    public static function getInstance();
}

abstract class Ajde_Shop_Transaction_Provider extends Ajde_Object_Standard
implements Ajde_Shop_Transaction_Provider_Interface
{
	
}

