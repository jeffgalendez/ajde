# Exception 90015 #
## Event context needed to fire, none detected ##

# Details #

All registered event callbacks are fired with the object from where they fired as the first and only result. Triggering an event without an object context lead to this exception.

The only exception to this is when registering a closure as callback function.
**Note:** When fired from object context, the closure callback gets the context object as first argument though!

**Example:**

In `index.php`, this works:
```
// As of PHP 5.3
Ajde_Event::register('Ajde', 'closureTest', function() { echo "Hello from closure!"; });
Ajde_Event::trigger('Ajde', 'closureTest');
```

But this throws an exception:
```
Ajde_Event::register('Ajde', 'noContextTest', 'Ajde::app');
Ajde_Event::trigger('Ajde', 'noContextTest');
```