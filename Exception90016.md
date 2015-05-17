# Exception 90016 #
## Callback is not valid ##

# Details #

The registered callback is not a valid.

**Example:**
```
Ajde_Event::register('Ajde', 'test', 'strangeFunction'); // strangeFunction() doesn't exist
Ajde_Event::register('Ajde', 'test', array('Ajde', 'weirdMethod')); // Ajde::weirdMethod() doesn't exist

class Test
{
    public static function triggerTest()
    {
        Ajde_Event::trigger('Ajde', 'test');
    }
}
Test:triggerTest();
```