# Exception 90012 #
## No classname or object instance given, or classname is incorrect ##

# Details #

When invoking an `Ajde_Event`, the object parameter is incorrect. This parameter must be either a object instance or an existing classname.

Example:
```
class Guestbook extends Ajde_Controller
{
    function viewDefault()
    {
        // correct
        Ajde_Event::register($this, 'someEvent', 'someMethod');
        Ajde_Event::trigger('Guestbook', 'someEvent');
        // triggers exception
        Ajde_Event::unregister('Guestbooking', 'someEvent');
    }

    function someMethod()
    {
        /* [...] */
    }
}
```