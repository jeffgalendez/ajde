# Getting started with Ajde #



## Download ##

Grab a copy of the framework from our [download page](http://code.google.com/p/ajde/downloads/list) or check-out the latest version from [our svn repository](http://code.google.com/p/ajde/source/checkout).

## Install ##

You need the following to run Ajde:

  * Apache webserver
  * PHP, version 5.2.3 or newer
  * MySQL (optional)

→ More about [installation & requirements](DocumentationInstallationRequirements.md)

## 'Hello world' ##

A fresh install should look something like this:

![http://ajde.googlecode.com/svn/branches/samples/public/images/screenshots/freshinstall.png](http://ajde.googlecode.com/svn/branches/samples/public/images/screenshots/freshinstall.png)

The code structure is straight-forward. All application logic and module specific assets (css/js) are kept within the `private/application/` directory, while publicly accessible assets such as images are stored in the `public/` directory.

![http://ajde.googlecode.com/svn/misc/dir-schema.png](http://ajde.googlecode.com/svn/misc/dir-schema.png)

### Change some basic Config parameters ###

In the `private/application/config/` folder head over to the `Config_Application.php` file. All your application configuration should go here.

Let's change the name of our application for now:

```
public $sitename = "Ajde Samples";
```

→ Working with the [application configuration](DocumentationConfig.md)

### Create a new module ###

We want our new application to guide us through the various aspects of the framework, and for this purpose we will be creating a new module which holds different pages (actions) which can be called through their own unique URL's ([more on this](DocumentationRouting.md)).

Create a new folder `helloworld/` in the `private/application/modules/` directory. Each module must have a default controller, which in this case should be `HelloworldController.php`.

_Note: Make sure to always follow this naming convention. This controller must at least contain the controller class and one or more actions._

Each action can be handled differently for different requested formats (i.e. **JSON** for Ajax-requests, **XML** for REST services and of course the 'ordinary' **HTML** format). For now, we'll limit ourselves to one function, which handles all those formats:

```
<?php 

class HelloworldController extends Ajde_Controller
{	
	function view()
	{
		return '<h2>Hello world!</h2>';
	}
}
```

Now point your browser to: http://yourserver/helloworld.html (If that was too easy, try to grab this page as JSON). Click the purple bar (or press ESC) to unveil the debugger console.

![http://ajde.googlecode.com/svn/branches/samples/public/images/screenshots/helloworld1.png](http://ajde.googlecode.com/svn/branches/samples/public/images/screenshots/helloworld1.png)

→ Working with [modules](DocumentationController.md)

## Add a template and some unicorns ##

### Template ###

It's a good habit to seperate application logic and presentation so let's create a template. Create a subfolder `template/` in the samples module directory and a template named `view.phtml`.

The template contains only this for now:

```
<h2>Hello world!</h2>
```

In order to display this template, a minor change has to be made to the controller:

```
function view()
{
	return $this->render();
}
```

This will look for a template with the same name as the action (helloworld) so it's important to stick to the naming convention here as well.

→ Working with [templates](DocumentationTemplate.md)

### Resources ###

Now, if you look at the source of the page, you'll see some stylesheets and javascripts already included in the page. They belong to the default layout and are not directly linked, but pre-processed through internal Ajde components, in order to make it possible to combine, compress and cache those assets in a live environment.

Let's add some css to our newly created page. Create a subfolder `res/css/` and `res/js/` in the samples module directory and a stylesheet named `view.css` in the css directory and a javascript file named `view.js` in the js directory.

Add some styles to the stylesheet:

```
body { background-color: pink; }
```

It's still a little boring, let's add some rainbows and unicorns! Change the template to:

```
<?php
	$this->requireJsLibrary('jquery', '1');
	$this->requireJsRemote('http://www.cornify.com/js/cornify.js');	
?>
<h2>Hello world!</h2>
```

And add this to the javascript file:

```
$(document).ready(function() {
	setInterval(function() {
		cornify_add();
	}, 1000);
});
```

Your _Hello world_ experience will never be the same!

![http://ajde.googlecode.com/svn/branches/samples/public/images/screenshots/helloworld2.png](http://ajde.googlecode.com/svn/branches/samples/public/images/screenshots/helloworld2.png)

→ Working with [resources](DocumentationResources.md)

_**NEXT**_: [Create a simple blog](TutorialBlog.md)