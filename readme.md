# Grump-Free-Framework
> Fork of Fat-Free-Framework 3.6 with included MVC project structure with basic automated routing, UIKit 3, and GrumpyPDO 1.4, Drag & Drop ready to reduce grumpiness.

GF3 is a framework based on Fat-Free-Framework, which is mostly known for allowing you to structure your projects however you want, but this fork has a MVC structure baked into it. This is basically a skeleton project that may be used to rapidly create MVC model websites.

Currently, GF3 comes with a few core features:
- Fat-Free-Framework version 3.6
- A custom MVC system structure
- Stock with UIKIT 3, but can easily be changed.
- Template (Header, navbar, footer) maintained in single file, and can easily switch between multiple different templates
- GrumpyPDO 1.4 for easy and secure MySQL database management

## Installation

Drag & Drop Ready. Simply download the repository as a zip file and unpack it onto an apache server, and then rename the `.htaccess.apache` file to `.htaccess`

NOTE: It is absolutely essential that you change the permissions of config.ini (in the root directory) to a setting that will not allow the browser to view/download it. I personally use chmod 700 (Owner has all permissions but no one else). This is where your database credentials should be stored, and failing to change these permissions could very well allow anyone to access the file and get database login credentials which would be VERY BAD.

For nginx, lighttpd, IIS, check out the documentation at [https://fatfreeframework.com/3.6/routing-engine#SampleApacheConfiguration](fatfreeframework.com) for information on how to set up the project.

## Documentation

### Settings

Application settings can be found in `./config.ini`

Currently, the only thing to do in there is to set up your database credentials to be used with GrumpyPDO.

You can also fairly easily change the location of certain things like UI elements.

### Structure

I've attempted to make the GF3 structure easy to follow. Most edits will be made to the `app/` directory, which has this structure:

```
| application
	| vendor
	php_functions.php
	routes.ini
| controllers
	ex1.php
	ex2.php
| models
	ex1.php
	ex2.php
| templates
	main.htm
| views
	| ex1
		index.htm
	| ex2
		index.htm
controller.php
```

### Controllers

Controllers are what tells the server what it is that it should be doing when on a specific route.

Here is a simple class skeleton:

```
<?php
namespace Controllers;
class example_class extends \Controller {
	function get() {
		$example = new \Models\example_class();
		echo render('example_class/index.htm');
	}
}
```

Classes have a namespace "controllers", so F3 knows which class is a controller or a model.

Classes also extend the base controller class (`\Controller`). 
This gives you access to the f3 base class inside the actual page controller with the variable `$this->f3`.
This is necessary to do things like setting variables, etc.

#### Rendering Pages Through Controllers

You'll also notice that to render a page we call the function `render()`, which is stored in `app/application/php_functions.php`. This function is used like this:

```
render($content, $template)
```

Where `$content` is a relative path from `app/views/` to the page content. So, in the example above, we use the path `example_class/index.htm` to load the content from `app/views/example_class/index.htm`
Also, `$template` is a relative path from `app/templates` to a template page. By default, the path supplied is `main.htm`. This is the page template that is loaded.
This function makes it trivial to load content into any template you wish easily.

So if you wanted to load view content from `example_module/toasty.htm` in a custom page template stored in `templates/toast.htm`, you just have to call `render('example_module/toasty.htm', 'toast.htm')`

### Models

Models are the data layer of your application, logic and database queries should be done here.

Here is a simple model skeleton based on the controller above:

```
<?php
namespace Models;
class example_class {
	
	public function returnOutput() {
	    return "Example Output One";
	}
	
}
```

So, if you wanted to store the return of `returnOutput()` to use in a view later, in the controller you would do:

```
$this->f3->set('variable', $example->returnOutput());
```

after declaring `$example = new \Models\example_class();`.

If you want to interact with your database in the model, then you would declare the model class like this:

```
$example = new \Models\example_class($this->f3->db);
```

and you would alter your model like so:

```
<?php
namespace Models;
class example_class {
	
	protected $db;
	
	public function __construct($db) {
	    $this->db = $db;
	}
	
	public function returnOutput() {
	    return "Example Output One";
	}
	
}
```

Which would allow you to access database object with `$this->db`. If you use GrumpyPDO, this means queries can be done anywhere inside the model as simply as `$this->db->run($query, $variables);`

### Views

Views are stored in `app/views`, where each module gets it's own folder. The purpose of this is to keep the project cleaner and to allow multiple pages within a single module. View files should be  built in HTML, I'm fairly positive that trying to use any PHP on these pages will simply just not render the pages because GF3 uses Fat-Free-Frameworks own rendering engine by default, but this can be changed by altering the render function in `app/application/php_functions.php` to render a different way.

For detailed instructions on using F3's rendering engine, check out [their documentation](https://fatfreeframework.com/3.6/views-and-templates#AQuickLookattheF3TemplateLanguage).

### Automatic Routing

For the most part, barring some of the more complex routes, will be done automatically.

What's handled automatically:
 - The routing structure is example.com/MODULE/CONTROLLER_METHOD
 - If no CONTROLLER_METHOD is given, (e.g `example.com/example`), a method matching the verb of whatever HTTP request given will be executed.
	- For example, just navigating to the example above will invoke the `example` controller and automatically call the `get()` method inside of it.
	- If there is a form on the page loaded by the `get()` method above, sending the form data via POST to the same target page that is currently loaded will automatically invoke the `post()` method inside the controller.
	
This structure means that you can choose to call any method in a class by simply adding it to your URL.
You could use the URL `example.com/example/example_of_a_method` which will invoke the method `example_of_a_method` inside the class. This does not detect if the method is supposed to GET or POST data, so you can check that yourself using `$this->f3->get('VERB')` to detect if you are posting to a specific method, and if you are, you can tell the controller to load a different method for handling that data. See `controller/ex2.php` for an example of this. Of course, if a specific method call does not require POST data processing, this step is not necessary.
	
For additional routing capabilities, take a look at [F3's routing engine documentation](https://fatfreeframework.com/3.6/routing-engine)

## Contributing

1. Fork it
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request
