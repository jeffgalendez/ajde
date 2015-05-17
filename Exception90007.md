# Exception 90007 #
## Parameter _PARAMETER_ not set in class _CLASSNAME_ when calling get(_PARAMETER_) ##

# Details #

If a class extends a subclass of `Ajde_Core_Object_Magic` (i.e. `Ajde_Core_Object_Standard`, `Ajde_Core_Object_Singleton`) and a getter method is called (either `get{Parameter}` or `get('parameter')`) for an unset parameter, this exception is thrown.

Example:
```
class Test extends Ajde_Core_Object_Standard
{
}
$foo = new Test();
$foo->getBar();
```