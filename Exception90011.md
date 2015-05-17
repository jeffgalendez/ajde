# Exception 90011 #
## Action _ACTION_ for module _MODULE_ not found ##

# Details #

The requested action is not available. Make sure the appropriate function exists in the module controller.

Example:
```
class Guestbook extends Ajde_Controller
{
    function viewDefault()
    {
        /* [...] */
    }
}
```
throws this exception when accessing the URL `http://www.ajdedomain.com/guestbook/new.html`