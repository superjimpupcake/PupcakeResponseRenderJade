Pupcake ResponseRender Of The Jade Template
============================================

This is a component to allow rendering jade template in the response object in Pupcake Framework

##Installation:

####install package "Pupcake/ResponseRenderJade" using composer (http://getcomposer.org/)

##Usage:

Before we get started, we would like to set up the following file structures for our web application:

* public/index.php --- the main index file
* public/views --- the folder contains all the view files
* vendor --- the vendor folder, storing all composer packages
* vendor/autoload.php --- the comoser's auto-generated file for class autoloading
* packages --- the folder to store 3rd party libraries
* packages/php-jade --- the folder to store the everzet/jade.php library (https://github.com/everzet/jade.php)

We then do git clone https://github.com/everzet/jade.php.git packages/php-jade so we have the everzet/jade.php library in place.

Now we are going to edit public/index.php
```php
<?php
//Assuming this is public/index.php and the composer vendor directory is ../vendor

require_once __DIR__.'/../vendor/autoload.php';

$loader = new Composer\Autoload\ClassLoader();
$loader->add("Everzet", "../packages/php-jade/src"); //we need to tell where to look for the php-jade library
$loader->register();

$app = new Pupcake\Pupcake();

$app->usePlugin("Pupcake.ResponseRender"); //this is required
$app->usePlugin("Pupcake.ResponseRenderJade");

$app->setViewDirectory("../views");
$app->setViewEngine("jade");

$app->get("render_test", function($req, $res){
    $data = array();
    $data['word'] = "world";
    $res->render("index.jade", $data);
});

$app->run();
```

Now we are going to add views/index.jade
```jade
!!!5
p hello {{$word}}
```
In the jade template above, we are looking for the php varialbe $word which is passed along in the $res->render method

Now navigate to /render_test and you should see:
```html
<!DOCTYPE html>
<p>hello world</p>
```
