# Exception 90001 #
## Class _CLASSNAME_ has no pattern defined while it is configured for bootstrapping ##

# Details #

If a class is configured for bootstrapping, it must extend a subclass of `Ajde_Core_Object` for the bootstrapper to know how to call the bootstrap function.

Example:
```
class Ajde_Exception_Handler extends Ajde_Core_Object_Static
{
    public static function bootstrap()
    {
        /* [...] */
        return true;
    }
}
```