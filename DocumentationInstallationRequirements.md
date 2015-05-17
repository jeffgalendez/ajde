# Installation & Requirements #



## Download ##

Right now, Ajde is a project under development. The source code changes all the time and we're not sure when we will release the first alpha version. In the meantime, there are two ways to try it out.

### Release downloads ###

Grab a prepared copy of the source code by heading over to:

  * http://code.google.com/p/ajde/downloads/list

You'll find some snapshots of past releases, but those are certainly unstable and may contain bugs.

### Subversion ###

Another way is to check out the latest code from our SVN repository:

  * http://code.google.com/p/ajde/source/checkout

This way you make sure the code you get is up to date, but you can still be certain it is unstable. However, as we release patches you can simply update the code with SVN.

## Install ##

You need the following to run Ajde:

  * Apache webserver with mod\_rewrite enabled
  * PHP, version 5.2.3 or newer (5.3 unleashes some extra debugging powers)
  * MySQL (optional)

You might want to enable eAccelerator or APC in PHP to make Ajde even more faster, and mod\_deflate and mod\_expires in Apache. Those are not required though.

### Apache configuration ###

Extract the code (if you've downloaded it) or checkout with SVN into your project directory.

  * If this is in your `wwwroot`, navigate there and you should be up and running.
  * If you want to create a virtual host, see [this tutorial from Zend Framework](http://framework.zend.com/manual/en/learning.quickstart.create-project.html) (Heading **Create a virtual host**, you can skip the line `SetEnv APPLICATION_ENV "development"`)

We won't go into detail about setting up an Apache configuration here. There are excellent tutorials out there for [Windows](http://www.apachefriends.org/en/xampp-windows.html), [Max OS X](http://www.apachefriends.org/en/xampp-macosx.html) or [Ubuntu](https://help.ubuntu.com/community/ApacheMySQLPHP).

So far, if you've run into any problems, please drop a comment below.

## Prepare your project ##

Navigating to your newly created projects URL will bring up the default Ajde homepage:

![http://ajde.googlecode.com/svn/branches/samples/public/images/screenshots/freshinstall.png](http://ajde.googlecode.com/svn/branches/samples/public/images/screenshots/freshinstall.png)

Notice that Ajde is by default in developer mode, as the purple bar of the Ajde Debugger near the bottom of the screen can be seen. (See [DocumentationConfig](DocumentationConfig.md) for more details on this)

### Configuration ###

Probably the first thing you want to do is make some changes to your projects main configuration settings in `private/application/config/Config_Application.php`.

→ More about [project configuration](DocumentationConfig.md)

### Layout ###

The default layout can be found in the `private/application/layout/default/template/default.html.phtml` file. You can either copy this layout to a new directory, or edit this default template.

→ More about [layouts](DocumentationLayout.md)

### Module ###

The layout is the projects master template in which the requested module will be loaded. These modules can be found in `private/application/modules` and in the default configuration, the `view` action of the `main` module is executed.

→ More about [modules](DocumentationModule.md)