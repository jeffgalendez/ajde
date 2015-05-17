# Exception 90002 #
## Bootstrap method in _CLASSNAME_ doesn't exist ##

# Details #

If a class is configured for bootstrapping, it must have a public function `bootstrap()`.

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