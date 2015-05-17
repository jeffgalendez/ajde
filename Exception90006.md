# Exception 90006 #
## Call to undefined method _METHODNAME_ ##

# Details #

If a class extends a subclass of `Ajde_Object_Magic` (i.e. `Ajde_Object_Standard`, `Ajde_Object_Singleton`) the magic method `__call()` is invoked when calling an undefined method on this class. This is used for the magic getters and setters. Calling an undefined method which is not one of `get{Parameter}()`, `set{Parameter}()` or `has{Parameter}()` results in this error.

Example:
```
class Test extends Ajde_Object_Standard
{
}
$foo = new Test();
$foo->setBar(true);
$foo->undefinedMethod();
```