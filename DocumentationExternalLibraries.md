# Libraries ready for use with Ajde #



## HTMLPurifier ##

Put the `library` folder of the Lite distribution of HTMLPurifier ([download here](http://htmlpurifier.org/download)) in the `lib` folder of Ajde, and rename it to `HTMLPurifier`. The library is then used when calling the `clean` function in a template like so:

```
$this->clean('<script>alert(0);</script>Unsafe element!');
```

## Zend ##

Put the `Zend` library folder of the [Zend Framework](http://framework.zend.com/) in the `lib` folder of Ajde, and use it directly in your project.