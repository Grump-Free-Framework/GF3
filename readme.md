# Grump-Free-Framework
> Fork of Fat-Free-Framework 3.6 with included MVC project structure with basic automated routing, UIKit 3, and GrumpyPDO 1.4, Drag & Drop ready to reduce grumpiness.

GF3 is a framework based on Fat-Free-Framework, which is mostly known for allowing you to structure your projects however you want, but this fork has a MVC structure baked into it. This is basically a skeleton project that may be used to rapidly create MVC model websites.

Currently, GF3 comes with a few core features:
- Fat-Free-Framework version 3.6
- A custom completely modular MVC system structure, plug-in ready
- Stock with UIKIT 3, but can easily be changed.
- Template (Header, navbar, footer) maintained in single file, and can easily switch between multiple different templates
- GrumpyPDO 1.4 for easy and secure MySQL database management

Looking for plugins? Check out https://github.com/GrumpyCrouton/gf3-modules

## Installation

Drag & Drop Ready. Simply download the repository as a zip file and unpack it onto an apache server, and then rename the `.htaccess.apache` file to `.htaccess`

NOTE: It is absolutely essential that you change the permissions of config.ini (in the root directory) to a setting that will not allow the browser to view/download it. I personally use chmod 700 (Owner has all permissions but no one else). This is where your database credentials should be stored, and failing to change these permissions could very well allow anyone to access the file and get database login credentials which would be VERY BAD.

For nginx, lighttpd, IIS, check out the documentation at [https://fatfreeframework.com/3.6/routing-engine#SampleApacheConfiguration](fatfreeframework.com) for information on how to set up the project.

## Documentation

### Settings

Application settings can be found in `./config.ini`

List of things you can do in settings:
 - Set up your MySQL connection details
 - Change the default module

You can also fairly easily change the location of certain things like UI elements.

### Structure

I've attempted to make the GF3 structure easy to follow. Most edits will be made to the `app/` directory, which has this structure:

```
| application
	| vendor
		php_functions.php
		routes.ini
| modules
	| ex1
		| models
			model.php
		| views
			index.htm
		controller.php
	| ex2
		| models
			model.php
		| views
			index.htm
		controller.php
controller.php
```

This version has a modules system in place that allows you to easily create new modules and just throw them into your project.

### Controllers

Controllers are what tells the server what it is that it should be doing when on a specific route.

Here is a simple class skeleton:

```
<?php
namespace modules\MODULE_NAME;
class controller extends \Controller {
	function get() {
		$example = loadModel('model');
		echo render('index');
	}
}
```

Classes have a namespace "modules\MODULE_NAME", so F3 knows which class is a controller or a model.

Note: MODULE_NAME should be equal to the directory name the module resides in. So if you have a module called "login", it would be stored at app/modules/login/ and MODULE_NAME would be "login"

Classes also extend the base controller class (`\Controller`). 
This gives you access to the f3 base class inside the actual page controller with the variable `$this->f3`.
This is necessary to do things like setting variables, etc.

There is also a function that is used called `loadModel()`, this will allow you to easily load any model that is stored inside the MODULE_NAME/models folder by just passing the name of the class to the function.

#### Rendering Pages Through Controllers

You'll also notice that to render a page we call the function `render()`, which is stored in `app/application/php_functions.php`. This function is used like this:

```
render($content, $template)
```

Where `$content` is a relative path from `app/modules/MODULE_NAME/views/` to the page content. So, in the example above, we use the path `index` to load the index.htm page from `app/modules/MODULE_NAME/views/index.htm` - You should not put the .htm extension when using this function.

Also, `$template` is a relative path from `app/` to a template page. By default, the path supplied is `templates/main`. This is the page template that is loaded.
This function makes it trivial to load content into any template you wish easily. - You should not put the .htm extension when using this function.

### Models

Models are the data layer of your application, logic and database queries should be done here.

Here is a simple model skeleton based on the controller above:

```
<?php
namespace modules\MODULE_NAME\models;
class Model {
	
	public function returnOutput() {
	    return "Example Output One";
	}
	
}
```

So, if you wanted to store the return of `returnOutput()` to use in a view later, in the controller you would do:

```
$model = loadModel('model');
$this->f3->set('variable', $model->returnOutput());
```

If you want to interact with your database in the model, you would alter your model like so:

```
<?php
namespace modules\MODULE_NAME\models;
class Model {
	
	protected $db;
	
	public function __construct() {
		$this->db = \Base::instance()->db;
	}
	
	public function returnOutput() {
	    return "Example Output One";
	}
	
}
```

Which would allow you to access database object with `$this->db`. If you use GrumpyPDO, this means queries can be done anywhere inside the model as simply as `$this->db->run($query, $variables);`

### Views

Views are stored in `modules/MODULE_NAME/views/`. View files should be built in HTML, I'm fairly positive that trying to use any PHP on these pages will simply just not render the pages because GF3 uses Fat-Free-Frameworks own rendering engine by default, but this can be changed by altering the render function in `app/application/php_functions.php` to render a different way.

For detailed instructions on using F3's rendering engine, check out [their documentation](https://fatfreeframework.com/3.6/views-and-templates#AQuickLookattheF3TemplateLanguage).

### Automatic Routing

For the most part, barring some of the more complex routes, will be done automatically.

What's handled automatically:
 - The routing structure is example.com/MODULE/CONTROLLER_METHOD
 - If no CONTROLLER_METHOD is given, (e.g `example.com/example`), a method matching the verb of whatever HTTP request given will be executed.
	- For example, just navigating to the example above will invoke the `example` controller and automatically call the `get()` method inside of it.
	- If there is a form on the page loaded by the `get()` method above, sending the form data via POST to the same target page that is currently loaded will automatically invoke the `post()` method inside the controller.
	
This structure means that you can choose to call any method in a class by simply adding it to your URL.
You could use the URL `example.com/example/example_of_a_method` which will invoke the method `example_of_a_method` inside the class. This does not detect if the method is supposed to GET or POST data, so you can check that yourself using `$this->f3->get('VERB')` to detect if you are posting to a specific method, and if you are, you can tell the controller to load a different method for handling that data. See `modules/ex2/controllers/controller.php` for an example of this. Of course, if a specific method call does not require POST data processing, this step is not necessary.
	
For additional routing capabilities, take a look at [F3's routing engine documentation](https://fatfreeframework.com/3.6/routing-engine)

### Module Specific beforeroute and afterroute

Inside a module directory, you can create a file called `beforeroute.php` and `afterroute.php` to automatically be loaded when the project executes. This is a simple `include` hook to the beforeroute and afterroute method inside the base controller `app/controller.php` that checks for the files mentioned above and includes them into the page when found.

Inside these files, you have access to the framework base with `$this->f3` because the file is simply included inside the base controller class. I added this to give the ability to modules to easily create rules for themselves, for example, a good use case for this would be if you were including a login system to your website. In beforeroute.php you would check if the user is logged in, and if they are not, you can easily redirect the user to the login page.

beforeroute and afterroute are always included, whether that module is loaded or not.

Working example:

```
<?php
$uri = explode("/", $this->f3->get('URI'));
if(!in_array("login", $uri)) {
	if(!isset($_SESSION['user'])) {
		$this->f3->reroute('/login');
	}
}
```

The above will automatically reroute the user to `/login` if `$_SESSION['user']` is not set and the user is not already on a route that contains the word "login".

## Contributing

1. Fork it
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request
