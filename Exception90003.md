# Exception 90003 #
## Bootstrap method in _CLASSNAME_ returned FALSE ##

# Details #

If a class is configured for bootstrapping, it must have a public function `bootstrap()` which returns `TRUE` on success. A return value `FALSE` (or no return value defined) will trigger this exception.

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