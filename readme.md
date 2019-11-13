# Grump-Free-Framework
> Fork of Fat-Free-Framework 3.6 with included MVC project structure with basic automated routing, UIKit 3, and GrumpyPDO 1.4, Drag & Drop ready to reduce grumpiness.

GF3 is a framework based on Fat-Free-Framework, which is mostly known for allowing you to structure your projects however you want, but this fork has a MVC structure baked into it. This is basically a skeleton project that may be used to rapidly create MVC model websites.

Currently, GF3 comes with a few core features:
- Fat-Free-Framework version 3.6
- Modular MVC system structure, plug-in ready
- Stock with Formantic-UI 2.7, but can easily be changed.
- Template (Header, navbar, footer) maintained in single file, and can easily switch between multiple different templates
- GrumpyPDO 1.4 for easy and secure MySQL database management

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
 - Change the default template used when rendering views

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

### Anatomy of a Module

Grump-Free-Framework is a modularized MVCPHP framework, this means every "module" has it's own separate MVC structure (Model, View, Controller). In fact, each module can have a single Controller, and any number of models/views.

I personally tend to use 2 models, one for uploading information to my database, and one for downloading information. However, it's completely open for you to set it up how you want. Do you want a separate model for each view? Go for it. Models for specific types of actions? You bet.

#### Getting Started

To create a module, you must create a new folder in `app/modules`. The name of the folder is how the module will be accessed via URL. As an example, let's use the name "new_module".

#### Controllers

The controller is like the backbone of the module, and unlike models and views, is _always_ required. This file contains methods which tell the framework exactly what to do on any given route. Inside our newly created module folder, let's create a file called `controller.php`.

controller.php:

	<?php
	namespace modules\new_module;
	class controller extends \Controller {
		
	}
	
Note: notice the namespace contains the name of the module.

##### Routing

Now, this controller won't do anything quite yet. Before making it do anything, you need to understand how the automatic routing works. Routing is basically how the framework reads the URL to understand what module to load and which method to execute inside the module. Grump-Free-Framework uses a certain structure in it's URL's. 

The structure looks like this: `example.com/MODULE/CONTROLLER_METHOD`, but there are some rules for this which are not very explicit.

* If no module is specified, the framework will load the module specified as `defaultModule` in `config.ini.php`
* If a module is specified, but a controller method is not, the framework will load a method specified by the verb of the HTTP request (get, post, etc)

If you want to load a module without specifying a controller method (such as `example.com/new_module`, you'll need a `get()` method inside your module controller.

If your url is something like `example.com/new_module/page`, then you will need a `page()` method inside your module controller.

###### Handling POST requests

If you are sending a POST request, you have presumably just submitted a form.

* If the form was submitted from the `get()` method, you will need a `post()` method to handle the request.
* If the form was submitted from any other method, [you will need to add a check to that method to handle the POST request](#handling-post-requests-from-specified-methods).

##### Our First Method

Inside the controller, we will specify a method called `get()`.

	class controller extends \Controller {
		
		public function get() {
			
		}
		
	}
	
#### Views

You'll presumably want to render some HTML on your page when you call a certain module and method. This is called using a View. In our module folder, create a new folder called "views", and inside this new folder create a file called `index.htm`.

index.htm:

	<h1 class="ui header">New Module</h1>
	<p>This is a new module.</p>

Note: The HTML file MUST be stored as a `.htm` file.

##### Rendering Views

The framework has a built in function for rendering views inside your module controller, the function is simply called `render()`, and it takes 1 to 2 parameters.

The first parameter you should pass this function is the path to the `.htm` file you want to render. The second parameter is which master template you want to use. Thankfully the framework does most of the path work for you.

Let's render the index.htm page for our module:

	public function get() {
		echo $this->render('index');
	}

The first parameter assumes a path to the index file based on which module is loaded, so you just have to specify which file, and you don't specify the file extension. This will also load whatever template is stored at `app/templates/main.htm`.

I mentioned previously that the `render()` function can accept a second parameter, this is to change the master template that is loaded. Say we have a 2nd template called `bootstrap.htm`, and it saved in `app/template` directory, we can render it this way:

	echo $this->render('index', 'templates/bootstrap');

Note: When specifying the 2nd paramter to the render function, the path is from the `app/` directory.

##### Using Variables in a View

If you want to use a variable inside your view, you will need to set the variable inside the contoller.

	public function get() {
		$this->f3->set('variable_name', 'variable');
		echo $this->render('index');
	}
	
On the view, you can display this variable by using double curly bracket syntax.

	<p>This is a new module, and my variable is {{ @variable_name }}</p>

Note: This framework utilizes the [F3 Template Language](https://fatfreeframework.com/3.6/views-and-templates#AQuickLookattheF3TemplateLanguage), which is incredibly useful. You can essentially write your front-end layer with HTML standards extremely easily. This includes looping through arrays and such.

#### Models

Models are the layer of the module which is meant to do all of the database communications and work with data. You load them in your controller, and use the controller to call specific methods inside the model.

##### Creating a Model

Inside your module folder, create a new folder called "models". Inside this folder create a new file called "new_model.php". The name of the model does not matter, I'm using this as an example.

new_model.php:

	<?php
	namespace modules\new_module\models;
	class New_Model extends \Model {
		
		public function example() {
			return "This is an example";
		}
		
	}
	
##### Using a Model

In your controller, you'll want to load the model into a variable, then use the variable to interact with the model.

	function get() {
	
		//load model
		$model = $this->model('new_model');
		
		//use model
		$return = $model->example();
		
		echo $this->render('index');
	}
	
##### Utilizing GrumpyPDO

In your model, it's easy to do database queries using [GrumpyPDO](https://github.com/GrumpyCrouton/GrumpyPDO), simply call it by using `$this->db`.

For example, if you want to fetch all records from a certain table, just do this:

	public function fetch_records() {
		return $this->db->all("SELECT * FROM records");
	}

### Handling POST Requests from Specified Methods

Let's say you have a module called `new_module`, and a method called `page()` which contains an HTML form. When the form is submitted, you will need to check if the HTTP request is a POST request inside the method.

	<?php
		namespace modules\new_module;
		class controller extends \Controller {
			public function page() {
				
				//if HTTP request is POST
				if($this->f3->VERB == "POST") {
					//handle POST request
					die();
				} //else
				
				//handle GET request
				
			}
		}
	
For additional routing capabilities, take a look at [F3's routing engine documentation](https://fatfreeframework.com/3.6/routing-engine)

### Modularized Before and After Route Options

Inside a module directory, you can create a file called `beforeroute.php` or `afterroute.php` to automatically be loaded before or after the routing is done.

- Great place for module-specific checks. For example, a login module can check if a user is logged in and redirect to a login page if not.
- Always loaded (even if module is not)
- Store module-specific functions here that all modules will have access to. (I usually create a `functions.php` file inside the module directory and include it in `beforeroute.php` by `include `functions.php`)

## Contributing

1. Fork it
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request
