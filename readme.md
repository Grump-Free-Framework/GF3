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

For nginx, lighttpd, IIS, check out the documentation at [https://fatfreeframework.com/3.6/routing-engine#SampleApacheConfiguration](fatfreeframework.com) for information on how to set up the project.

NOTE: The INI file that we use to store our database credentials and global paths should be secure from being viewed in the browser, but for extra security it would be best to assign permissions to this file that allow it to only be read from the server itself.

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

Here is a simple controller class skeleton:

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

### Rendering Pages Through Controllers

You'll also notice that to render a page we call the function `render()`, which is used like this:

```
render($content, $template)
```

- `$content` is a relative path from `app/modules/MODULE_NAME/views/` to the page content, excluding the file extension. (must be .htm)
- `$template` is a relative path from `app/` to a template page, excluding the file extension. (must be .htm, default is `templates/main`)

Files loaded through this function must be `.htm` files, and the path should exclude the file extension. This function makes it trivial to load content into any template you wish easily.

### Models

Models are the data layer of your application, logic and database queries should be done here.

Here is a model class skeleton that already imports the database object.

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

So, if you wanted to store the return of `returnOutput()` to use in a view later, in the controller you would do:

```
$model = loadModel('model');
$this->f3->set('variable', $model->returnOutput());
```

Which would allow you to access database object with `$this->db`. If you use GrumpyPDO, this means queries can be done anywhere inside the model as simply as `$this->db->run($query, $variables);`

### Using your Model

To load a model, we use the function `loadmodel()`, which is used like this:

```
loadModel($model);
```

- `$model` is a relative path from `app/modules/MODULE_NAME/models/` to the model class, excluding the file extension.

Files loaded through this function must be `.php` files, and the path should exclude the file extension. This function makes it trivial to load multiple models into the same controller easily.

### Views

Views are stored in `modules/MODULE_NAME/views/`. View files should be built in HTML, and have a `.htm` file extension.

For detailed instructions on using F3's rendering engine, check out [their documentation](https://fatfreeframework.com/3.6/views-and-templates#AQuickLookattheF3TemplateLanguage).

### Automatic Routing

For the most part, barring some of the more complex routes, routing will be done automatically.

What's handled automatically:
 - The routing structure is example.com/MODULE/CONTROLLER_METHOD
 - If `CONTROLLER_METHOD` is not given, a method matching the verb of your http request will be executed. (e.g `get()`, `post()`, etc)
 
This structure means that by default, dynamic page loading will need to be handled with `$_GET` variables, because the automatic routing does not support adding more information to the URI itself, such as `/MODULE/CONTROLLER_METHOD/VARIABLE`, but you could do `/MODULE/CONTROLLER_METHOD?variable=variable`.

Automatic URL routing does not detect the HTTP request actually being sent to the server, so if you are POSTING to a page loaded by a `/CONTROLLER_METHOD`, you'll have to check the verb to process the `$_POST` data, or you can just check if `$_POST` data was sent to the server.

The below example will load `register.htm` (with a custom page template) when accessing `example.com/login/register` when the request to the server is `GET`, but when posting to the same URI, it will call the `registerUser()` method inside the class instead. 

```
public function register()
{
	if($this->f3->get('VERB') == "POST") {
	    $this->registerUser();
	}
	echo render('register', 'modules/login/template'); 
}
```
	
For additional routing capabilities, take a look at [F3's routing engine documentation](https://fatfreeframework.com/3.6/routing-engine)

### Modularized Before and After Route Options

Inside a module directory, you can create a file called `beforeroute.php` or `afterroute.php` to automatically be loaded before or after the routing is done.

- Great place for module-specific checks. For example, a login module can check if a user is logged in and redirect to a login page if not.
- Always loaded (even if module is not)
- Store module-specific functions here that all modules will have access to. (I usually create a `functions.php` file inside the module directory and include it in `beforeroute.php` by `include `functions.php`)

Login Module Example:

```
<?php
$uri = explode("/", $this->f3->get('URI'));
if(!in_array("login", $uri)) {
	if(!isset($_SESSION['user'])) {
		$this->f3->reroute('/login');
	}
}
```

The above will automatically reroute the user to `/login` if `$_SESSION['user']` is not set and the user is not already on a route that contains the word "login" in the URI.

Similarly, you can check if a module IS loaded by checking the URI:

```
<?php
$uri = explode("/", $this->f3->get('URI'));
if(in_array("login", $uri)) {
	echo "I'm in the login module! (or a module with a method called login)";
}
```

## Contributing

1. Fork it
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request
