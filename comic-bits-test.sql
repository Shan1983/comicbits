-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2017 at 08:26 PM
-- Server version: 5.5.54-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `comic-bits-test`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`shannan`@`%` PROCEDURE `comment_get`(IN `vMode` VARCHAR(50), IN `vCommentId` INT, IN `vCommentGuid` CHAR(32), IN `vArticleId` INT)
BEGIN
	
	IF vMode = 'ByComment' THEN
		SELECT c.*, IFNULL(c2.comment_guid, '') AS parent_guid, u.username AS username
		FROM `comment` c
		INNER JOIN `users` u on c.user_id = u.id
		LEFT JOIN `comment` c2 ON c.parent_id = c2.comment_id
		WHERE c.comment_id = vCommentId;
	ELSEIF vMode = 'ByCommentGuid' THEN
		SELECT c.*, IFNULL(c2.comment_guid, '') AS parent_guid, u.username AS username
		FROM `comment` c
		INNER JOIN `users` u on c.user_id = u.id
		LEFT JOIN `comment` c2 ON c.parent_id = c2.comment_id
		WHERE c.comment_guid = vCommentGuid;
	ELSEIF vMode = 'ByArticle' THEN
		SELECT c.*, IFNULL(c2.comment_guid, '') AS parent_guid, u.username AS username
		FROM `comment` c
		INNER JOIN `users` u on c.user_id = u.id
		LEFT JOIN `comment` c2 ON c.parent_id = c2.comment_id
		WHERE c.article_id = vArticleId
		ORDER BY c.comment_id;
	END IF;
    
END$$

CREATE DEFINER=`shannan`@`%` PROCEDURE `comment_save`(
	vCommentId INT,
	vArticleId INT,
	vUserId INT,
	vParentId INT,
	vLevel INT,
	vCommentData BLOB,
	vCommentGuid CHAR(32),
	vApproved INT,
	vEntityId INT,
	vIpAddress VARCHAR(50)
)
BEGIN
	DECLARE vObjectId INT;
	
	IF vCommentId = 0 THEN
	
		INSERT INTO `comment`
		(
			article_id,
			user_id,
			parent_id,
			`level`,
			comment_data,
			comment_guid,
			approved,
			created_date,
			created_by,
			created_ip_address
		)
		VALUES
		(
			vArticleId,
			vUserId,
			vParentId,
			vLevel,
			vCommentData,
			vCommentGuid,
			vApproved,
			NOW(),
			vEntityId,
			vIpAddress
		);
		
		SET vObjectId = LAST_INSERT_ID();
	
	
	ELSE
		-- update
		SET vObjectId = vCommentId;
	
	END IF;
	
	SELECT vObjectId AS object_id;
	
    
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) DEFAULT NULL,
  `allow_comments` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `tag_id`, `user_id`, `title`, `body`, `created_at`, `status`, `allow_comments`) VALUES
(5, 6, 1, 'My Password', 'The user is set as as an administrator\r\nTo log in as test\r\nemail: test@test.com\r\npassword: test', '2017-07-10 02:39:34', 0, NULL),
(9, 1, 1, 'A Roadmap for PHP Learning', 'A detailed look at what is the next step to getting your skills to a professional standard\r\n\r\nOne of the really great things about PHP is it’s got a super-low barrier to entry, but a nice path towards gradual improvement as you get more experience and knowledge.\r\nUnfortunately, for a new developer it can be hard to know what that path is. If you lack the knowledge, terminology, and overall goal then it’s difficult to begin that journey.\r\nI’m not going to say for a second that you should always be building enterprise OOP systems in advanced frameworks… you can start really basic and then enhance.\r\nSo that is what I’m going to do. Start with some entry level code, and step through it a bit at a time. For the most part this is actually more or less my own career path. I hope to prevent people getting stuck in the same places I did.\r\nThis article is not intended to as a comprehensive tutorial. The scope of some sections (such as OOP) would simply be too large, they could be be articles of similar length all on their own. Rather it is intended to provide a guide, a roadmap for self-education. A significant amount of information and code will be left out. In particular, this will be code that is moved out of our main file, such as that setting up database connections or auto loading patterns.\r\nIt’s also important not to feel like all of these things should be rushed through. This learning below probably covered around 10 years of my career. Don’t feel obliged to understand all of it in one go. This is merely intended to cover the “what should I be learning next” question.\r\nIt’s also worth noting that some of the code in here may be wrong. Again, it’s not intended as a tutorial, but a guide towards self-learning.\r\nOur starting point\r\nThis would be a common starter approach. It’s the lowest common denominator of PHP code, and the format and syntax used by hundreds of extremely terrible tutorials that Google is only too happy to provide.\r\n<?php\r\ninclude_once ''database.php’;\r\ninclude_once ''header.php’;\r\n$sort = mysql_real_escape_string($_GET[''sort'']);\r\n$result = mysql_query(''SELECT * FROM cupcakes ORDER BY '' . $sort . '' ASC'');\r\necho ''<h1>My Cupcakes</h1>'';\r\necho ''<table id="cupcake-table">'';\r\necho ''<tr>'';\r\necho ''<th><a href="?sort=flavour">Flavour</a></th>'';\r\necho ''<th>Description</tr>'';\r\necho ''<th><a href="?sort=price">Price</a>'';\r\necho ''</tr>’;\r\nwhile($row = mysql_fetch_assoc($result)){\r\n  echo ''<tr>'';\r\n  echo ''<td class="col_flavour">'' + $row[''flavour''] + ''</td>'';\r\n  echo ''<td class="col_desc">'' + $row[''description''] + ''</td>'';\r\n  echo ''<td class="col_price">'' + $row[''price''] + ''</td>'';\r\n  echo ''</tr>'';\r\n}\r\necho ''</table>’;\r\ninclude_once ''footer.php'';\r\n?>\r\nCode has been written like that since the beginning of time. It will work. It definitely will work.\r\nIf you look, though, you’ll see pretty much all of its crap is all tangled together. How you get stuff from the database and how you loop over it, and how you display a table are all tied in. The PHP builds a big nasty chain of echoes, and reading all that concatenation is kind of a drag. The PHP makes it hard to read the HTML, and vice versa.\r\nIn fact, if you view source on this trying to debug the HTML that’s generated you’ll find it’s making it all on one line, and it will be a nightmare to work with. Adding new line characters will make your HTML better, but your PHP worse.\r\nBreaking things apart\r\nWhat we want to do is follow something called Separation of Concerns, which means breaking down your code into logical chunks. Technically it’s more about how objects are structured and interact, but the basic principle applies here.\r\nWhy are we doing this?\r\nThe more things are tangled up and depend on each other, the harder they are to work with. If you want to change your HTML, it’s a bit hard without your SQL stuff getting in the way. If you’re trying to fix something in the way the data is structured, you’ve got HTML in up in your grill. Bear in mind this is a simple example. An actual app would be much more complex and that gets exponentially more tangly.\r\nWhat are we doing?\r\nWhat we can do, though, just for now, is to split out our “getting data” and our “displaying data”. We don’t even need to make it a separate file, just a trivial change. I’m going to fix the mysql_ nonsense at the same time.\r\nThe mysql_ functions are deprecated, which means even if they work now they won’t do so forever. It would be tempting to replace them with identical functions called with mysqli_, which are not deprecated. But this only solves half the problem. The other half is that our code itself shouldn’t know or care what database is being used. MySQL, Postgres, SQLite, they’re all valid options. Caring about what exactly is being used shouldn’t be something the code exposes. So we’re going to use PDO, an abstraction library.\r\nAn abstraction is a layer over the top of something (in this case the database calls) that hides complexity. Computer science is built on good abstractions. Or you can set your own magnetised zeros and ones.\r\n<?php\r\n// set up document\r\ninclude_once ''database.php'';\r\ninclude_once ''header.php'';\r\n// get data\r\n$query = "SELECT * FROM cupcakes ORDER BY :sort ASC";\r\n$statement = $database->prepare($query);\r\n$parameters = [''sort'' => $_GET[''sort'']];\r\n$statement->execute($parameters);\r\n$cupcakesArray = $statement->fetchAll(PDO::FETCH_ASSOC);\r\n?>\r\n<!-- deal with html in html-land -->\r\n<h1>My Cupcakes</h1>\r\n<table class="cupcake-table">\r\n<?php foreach($cupcakesArray as $cupcake) { ?>\r\n<tr>\r\n  <td class="col-flavour"><?php echo $row[''flavour''] ?></td>\r\n  <td class="col-desc"><?php echo $row[''description''] ?></td>\r\n  <td class="col-price"><?php echo $row[''price''] ?></td>\r\n</tr>\r\n<?php } ?>\r\n</table>\r\n<?php include_once ''footer.php'' ?>\r\nIf you look at the above you can see the HTML is much cleaner and easier to work with. It breaks more cleanly into HTML, with bits of PHP as variables throughout. At this point you’ll usually find any decent editor or IDE, such as PHPStorm or Sublime Text will be able to tell this is HTML, and give you proper syntax highlighting, etc.\r\nThe code now also uses PDO, which means it’s not tied to any specific database, and we have access to prepared statements with named parameters. This prevents the risks mentioned earlier, the prepared statements correctly deal with all escaping, making much more readable (and secure) code.\r\nWhat to learn\r\nIt’s worth knowing more about the functionality of PDO, in particular the prepared statement used above.\r\nPDO\r\nPrepared Statements\r\nMaking and Using Functions\r\nYou can clean this up even more by taking the data collection and moving it into a function.\r\nWhy are we doing this?\r\nThe main reason is that the code, when it’s a function, can be used by multiple different files without duplicating any code. You can simply call it with a different argument and get back whatever data you need.\r\nWhat are we doing?\r\nThough this function would typically be in another file, we’re going to just leave it at the top of this one. Leaving you with this:\r\n<?php\r\ninclude_once ''database.php'';\r\ninclude_once ''header.php'';\r\nfunction getCupcakes($database){\r\n  $query = ''SELECT * FROM cupcakes ORDER BY :sort ASC'';\r\n  $statement = $database->prepare();\r\n  $parameters = [''sort'' => $_GET[''sort'']];\r\n  $statement->execute($parameters);\r\n  return $statement->fetchAll(PDO::FETCH_ASSOC);\r\n}\r\n?>\r\n<h1>My Cupcakes</h1>\r\n<table class="cupcake-table">\r\n<?php foreach(getCupcakes() as $cupcake) { ?>\r\n<tr>\r\n  <td class="col-flavour"><?php echo $row[''flavour''] ?></td>\r\n  <td class="col-desc"><?php echo $row[''description''] ?></td>\r\n  <td class="col-price"><?php echo $row[''price''] ?></td>\r\n</tr>\r\n<?php } ?>\r\n</table>\r\n<?php include_once ‘footer.php'' ?>\r\nSomething to note here is that we have to pass in $database to our function. This is because of scopes. A scope is sort of the context you’re working in. The base level is “global” scope. All standard PHP functions exist in the global scope, and so does your new function. But inside your new function is a whole other environment, a clean slate. Variables defined in the global scope are not accessible unless they’re passed in, or you use the global $variable syntax to pull them in. And don’t do that.\r\nYou’ll also notice that our syntax has changed quite a lot from mysql_query to $database->prepare. This change from what is called procedural to object syntax is important, but we don’t need to focus on it too much now.\r\nWhat to learn\r\nUser defined functions, scopes, why you shouldn’t use global.\r\nUsing globals in functions\r\nCreating Functions in PHP\r\nVariable Scopes\r\nUsing Templates\r\nThe next step is towards using templates in our PHP code. A lot of people will snarkily tell you that you don’t need to use templates, because PHP is already a templating language. This is partially true. While PHP is very good for displaying variables in HTML (as we were doing before) a dedicated system for doing that allows us to have a workable pattern and approach. We want to split of this display code into separate files that can be worked on independently. Additionally, using something that is not PHP allows us to have cleaner and more readable code, though we do have a learn a tiny bit of alternative syntax.\r\nWhy are we doing this?\r\nWe want to separate code out that doesn’t deal with the same thing. The template, for example is all HTML layout. It has nothing to do with databases. If you want to fix a markup bug or add a block of text you really don’t need SQL statements in the way.\r\nWe also want to clean up the global scope while making the functional code more re-usable.\r\nWhat do we do?\r\nWe’re already very close to something you might consider a template, so let’s imagine we broke that chunk of code out and used Twig as a template object.\r\nNow we have a file called cupcake_prices.php, and we can do the following.\r\n<?php\r\n// set up document\r\ninclude_once ''database.php'';\r\ninclude_once ''functions.php'';\r\ninclude_once ''classes/twig.class.php'';\r\n$cupcakes = [''cupcakes'' => getCupcakes($database)];\r\n$twig->render(''cupcake.html'', $cupcakes);\r\nThat’s literally the whole thing. You don’t even need the ?php> bit because you’re not mixing in HTML. Your cupcakes data is handled in the and the twig class handles all your header and footer stuff. Typically you’d have more logic, like sorting or user input or whatever here, but this is all pretty static.\r\nWe haven’t changed our actual cupcake function, but we’ve moved it into a separate file just to make it easier to on ourselves. There’s no need to show that, it’s identical to the above.\r\nYour cupcake.html, the template, file will also be cleaner than our previous pure PHP implementation.\r\n{% extends "base.html" %}\r\n{% block content %}\r\n<h1>My Cupcakes</h1>\r\n<table class="cupcake-table">\r\n{% for cupcake in cupcakes %}\r\n<tr>\r\n  <td class="col-flavour">{{ cupcake.flavour }}</td>\r\n  <td class="col-desc">{{ cupcake.description }}</td>\r\n  <td class="col-price">{{ cupcake.price }}</td>\r\n</tr>\r\n<?php } ?>\r\n</table>\r\n{% endblock %}\r\nWhat you should learn\r\nTwig syntax\r\nUsing Classes\r\nWe have been using a functions.php file to split out data access. However, that file could get enormous. What we want to do is make separate files for each type of thing, each “entity”. We could make something like cupcake_functions.php, but a better approach is a class. To illustrate the benefits we’ve also added a second function, that just gets a single cupcake by ID. This is obviously a pretty common requirement, and is definitely going to be needed by any non-trivial example usage.\r\n<?php\r\nClass Cupcake {\r\n  public function find($database, $id){\r\n    $query = ''SELECT * FROM cupcakes WHERE id = :id'';\r\n    $statement = $database->prepare($query);\r\n    return $statement->fetch(PDO::FETCH_ASSOC);\r\n  }\r\n  public function all($database){\r\n    $query = ''SELECT * FROM cupcakes ORDER BY :sort ASC'';\r\n    $statement = $database->prepare();\r\n    $parameters = [''sort'' => $_GET[''sort'']];\r\n     \r\n    return $statement->fetchAll(PDO::FETCH_ASSOC);\r\n  }\r\n}\r\nFor a bit of quick explanation, we made a simple class.\r\nThe code shown in the section above will be more or less correct, except the way you access the data will be slightly different. Classes can be accessed by creating an instance of them first, then accessing it.\r\n$cupcake = new Cupcake();\r\n$data = $cupcake->all($database);\r\nYou can see from this that we get a quite nicely readable approach. Putting the functions in a class means we can give generic names like all while still being completely clear what it does.\r\nWhat you should learn\r\nClasses in PHP\r\nObject Oriented Programming\r\nI wasn’t really sure how far to go down this path. It’s way out of scope of this article to get deep into Object Oriented Programming, but the above really isn’t great. For all the functionality is in a class, it’s not really a useful object.\r\nAn object is supposed to essentially be a smart little thing, that has all the knowledge that should be required to do its job. Ours requires quite a bit of hand-holding.\r\nWe’re going to make a little change and make the code quite a bit more useful. To do this, we’re going to make use of its constructor. The constructor is a bit of code that executes when you use the new keyword to create an object. The syntax used is a bit odd. PHP likes to start its “magic” functions with a double underscore.\r\n<?php\r\nclass Cupcake(){\r\n  private $database;\r\n  public __construct($database, $id = false){\r\n    $this->database = $database;      \r\n  }\r\n  \r\n  public function find($id){\r\n    $query = ''SELECT * FROM cupcakes WHERE id = :id'';\r\n    $statement = $this->database->prepare($query);\r\n    return $statement->fetch(PDO::FETCH_ASSOC);\r\n  }\r\n  public function all(){\r\n    $query = ''SELECT * FROM cupcakes ORDER BY :sort ASC'';\r\n    $statement = $database->prepare();\r\n    $parameters = [''sort'' => $_GET[''sort'']];\r\n     \r\n    return $statement->fetchAll(PDO::FETCH_ASSOC);\r\n  }\r\n}\r\nThe real benefit here is that you don’t need to pass in $database to every function. This might seem trivial but it’s actually quite fundamental. This object now knows everything it needs in order to run. We pass it in one time at the start, and the system knows everything it needs — these are called dependencies. In fact, we could pass in different databases at will, and it will still work! We might do testing with a pre-set SQLite database, for example.\r\nUsage is roughly the same as before, except for where we pass in the database as we create a new class.\r\n$cupcake = new Cupcake($database);\r\n$allCupcakes = $cupcake->all();\r\n$oneCupcake = $cupcake->find(12);\r\nWe’d put our file in classes/cupcake.class.php in this case, just to keep it consistent.\r\nSomething else has been introduced at the same time. You’ll have noticed previously we have been writing public in a lot of places, and now we have a bunch of public and a single private.\r\nThis is called visibility. The public keyword says that the function can be called from outside the class, like we’ve done above. The private keyword says we can’t. $cupcake->database isn’t available to us. It’s only available to the functions calling from within the class. You’ll notice the $this->database calls inside the functions. $this in PHP refers to the class.\r\nThis actually brings us back to the discussion of scopes made previously. Within the context of running a class as an object, there’s a whole new scope. It’s probably the most important and useful scope in PHP, so it’s worth reading more about some of the gotchas here.\r\nWhat to learn\r\nVisibility\r\nAutoloading\r\nOne of the biggest issues with the above is the bit of include ''stuff.php''; happening in the top. Again it’s worth clarifying that what we’re talking about here most affects complex systems. There might be dozens of files being included here, and those might be being included into dozens more. It can quickly become a maintenance problem.\r\nYou can have includes that include other includes, and then include those…. But this just adds to the problem, rather than solving it. Thankfully, PHP has an elegant solution – autoloading.\r\nAutoloading tells PHP where to find the class files it’s looking for.\r\nspl_autoload_register(function ($class) {\r\n  include ''classes/'' . $class . ''.class.php'';\r\n});\r\nThis would be a simple example. As long as we have classes/twig.class.php and classes/cupcakes.class.php we will always be able to use these without ever bothering to do our long list of includes.\r\nWe can make all of this easier on ourselves by using Composer.\r\nUsing Composer\r\nComposer is a PHP package manager. If you look at something like Twig, for example, Composer will let us install and use Twig with minimal effort. It will also let us manage anything Twig needs installed (called dependencies) and will make sure it’s kept up-to-date.\r\nInstalling Composer depends on your operating system, but once it’s installed, packages can be installed just using the command line.\r\ncomposer require twig/twig:~1.0\r\nCritically, Composer builds an autoload file. By including this you get access to all of the packages Composer has put into the vendors directory without any need for us to think about, manage, or update. If you add new packages and functionality, no changes are required ever. They just all get handled by that autoload file.\r\nThis seems like a lot of overhead, and it absolutely is for our two or three files. But a real system may have dozens or even hundreds of interconnected requirements.\r\nImportant things you need to look into\r\nComposer\r\nEven more OOP\r\nWe’ve made our cupcakes an object, but it’s a fairly simple object. Objects of this kind work a lot better if the structure of the object more closely matches the data.\r\nWhat are we going to do?\r\nTo illustrate the point, we’re going to add a save() function to our object. We’re also going to add a bunch of public properties to our model so that $cupcake->price works. We’re also going to namespace our class. Finally, we’re going to make it so that the constructor optionally takes an id.\r\nWhy are we doing this?\r\nHaving an object that represents the data closely makes it a lot easier to access and modify the data. In particularly, it’s a lot easier to keep a sort of mental model in your head of what things do.\r\nNamespacing is used to keep clear what part of the application the file belongs to. With something like “Cupcake” it’s pretty clear, but a class called Users could belong to anything or have any role. And in particular, you could have multiple that might conflict. Adding a namespace allows PHP’s compiler to tell the difference between Authentication\\Users and Profiles\\MessageSettings\\Users. Ours is going to be pretty generic.\r\n<?php\r\nuse App;\r\nclass Cupcake(){\r\n  private $database;\r\n  public $id;\r\n  public $flavour;\r\n  public $description;\r\n  public $price;\r\n  public __construct($database, $id = false){\r\n    $this->database = $database;\r\n    if($id) {\r\n      $data = $this->find($id);\r\n      $this->id = $id;\r\n      $this->flavour = $data[''flavour''];\r\n      $this-description = $data[''description''];\r\n      $this->price = $data[''price''];\r\n    }       \r\n  }\r\n  \r\n  public function find($id){\r\n    // unchanged\r\n  }\r\n  public function all(){\r\n    // unchanged\r\n  }\r\n  public function save(){\r\n    $query = "UPDATE cupcakes SET flavour = :flavour, \r\n      description = :description\r\n      price = :price WHERE id = :id";\r\n    $statement = $this->database->prepare($query);\r\n    $data = [\r\n      ''id'' => $this->id,\r\n      ''flavour'' => $this->flavour,\r\n      ''description'' => $this->description,\r\n      ''price'' => $this->price,\r\n    ];\r\n    return $statement->execute($data);\r\n  }\r\n}\r\nNote that there are clever ways of doing the above $data array, including get_object_vars($this) or running json_encode then json_decode on $this, but this is a bit more clear and explicit.\r\nWhat this does is allow really easy usage, especially when editing, for example in a simple admin system.\r\n$cupcake = new Cupcake($database, 12);\r\n$cupcake->price = ''4.95'';\r\n$cupcake->save();\r\nOur actual example usage is unchanged for the most part, but actually slightly simplified. Especially in the case where we’re using it to find a single item. In fact, let’s create a new page, this one is just cupcake.php and is the page for a single cupcake.\r\n$cupcake = ;\r\n// set up document\r\ninclude_once ''database.php'';\r\ninclude_once ''functions.php'';\r\ninclude_once ''classes/twig.class.php'';\r\n$cupcake = new Cupcake($database, $_GET[''cupcake_id'']);\r\n$twig->render(''cupcake.html'', $cupcake);\r\nWe’ve actually stumbled on a design pattern here. This is called “Active Record”. The active record pattern just treats each row of the database as an object. It’s a simple way to think about and understand data, and is ideal for systems that have simple input and output of data — CRUD: create, read, update, delete.\r\nThere are a lot of things we could do to make this smarter and more powerful, but we have diminishing returns here, and this is a huge topic.\r\nWhat to learn\r\nActive record pattern — Note that while this page does a good job of describing the pattern it also uses mysql_query.\r\nImplementing MVC\r\nMVC means Model View Controller. It suggests that you split your code into three separate layers. The Model layer is often referred to as the data, but more accurately represents “business logic”, the data and rules that define your application. The View is anything that is presented to the user. Most commonly this will be HTML, but returning something like JSON is increasingly common, and is still in the MVC pattern entirely happily.\r\nThe Controller layer is the intermediary. It is the only part of the system that actually needs to understand what was requested, and how it should be responded to. The URL, any query parameters, any post data.\r\nThe controller takes the request information and uses it to generate a response. Most commonly it takes the request, uses it to figure out what data is needs, asks the Model layer for the data, puts that data into the relevant View layer, and returns that rendered out HTML.\r\nIn our example, our Cupcake class represents our Model layer. The View layer is handled by Twig. The controller, then, is the actual page itself.\r\nWhat’s the benefit\r\nThis pattern is an intuitive one, and one that many people (including me) discover for themselves long before ever seeing it given a name. It’s a clear separation of concerns. We let each area of the site deal with its own stuff. This means that while building or maintaining the system we’re not going to be needing to cross from the controller to the model much, and certainly not from the model to the view.\r\nWhat we need to do\r\nThe next step is a bit less intuitive. We need to give up on our pricelist.php. We need to move to a front controller, often more commonly known as a router. We’re going to change things up a lot here. Actually making a router is nonsensical, so we’re just going to install one.\r\nUsing a Microframework\r\nInstalling a microframework will give us a better understanding of the intent and structure of a framework, but still allow us to use all the code we’ve got written already.\r\nWe can install the Silex framework with composer.\r\ncomposer require silex/silex "~2.0"\r\nWe are now going to make or update an index.php file and make it essentially the same as our previous cupcake_prices.php.\r\n<?php\r\nrequire_once ''autoload.php'';\r\n$app = new Silex\\Application();\r\n$app->get(''cupcake_prices'', function() {\r\n    $cupcake = new Cupcake($database);\r\n    $data = [''cupcakes''=>$cupcake->all()];\r\n    return $app[''twig'']->render(''cupcake_prices.html'', $data);\r\n});\r\n$app->get(''cupcake/{id}'', function($id) {\r\n    $data = new Cupcake($database, $id);\r\n    return $app[''twig'']->render(''cupcake_single.html'', $data);\r\n});\r\n$app->run();\r\nThe benefit here is that we can see so much of our code working so clearly. We’ve even set up our single cupcake listing, though we haven’t bothered to demo the template for that.\r\nAgain, if you imagine half a dozen different routes, like a homepage, contact form, contact form submission, order form… you can see that this sort of structure will stay entirely comprehensible. After a time it would be necessary to split them up into separate controllers, but regardless, this is a nice clean start.\r\nUsing an ORM\r\nOur data so far has been simple, limited to a single table. But data will almost never actually be this simple. Data will be accessed in a number of different ways, with a whole bunch of different “where” conditions. More particularly, tables will need to be joined, whether lookup tables for something like status_id or category_id, or whole other entities. A common example, orders will have both cupcakes and customers.\r\nWe actually want to avoid having a lot of SQL. It can end up being a lot of work to maintain. Adding a field to a table means modifications all through the system. Different contexts (the orders for a customer, the customers for a day, the cupcakes for an order, the most popular cupcakes) require subtly different queries, and they multiply out of control.\r\nWe could modify our Cupcake class to add a bunch of functionality to add “where” conditions to our “all” or add individual functions to get the data in different contexts, but a better approach (as always) is to let someone else do it, and use an existing ORM.\r\nAn ORM is an Object Relational Mapper. This is essentially a single place where you can define the relationships between your entities. An order has many cupcakes. An order belongs to a customer. An order has one status.\r\nAs long as these are defined in the ORM the data can be sliced and diced however it’s required.\r\nWe’re going to implement the Eloquent ORM used by the Laravel framework. It’s a nice system to use, and it follows the Active Record pattern we stumbled on earlier.\r\nWith Eloquent you define your models, much like we defined our Cupcake class earlier. In fact, our Eloquent model replaces the Cupcake class. The Eloquent base stuff handles the vast majority of what we need to do, and all we need really is any relationships.\r\n<?php\r\nnamespace App;\r\nUse Illuminate\\Database\\Eloquent\\Model\r\nClass Cupcake extends Model {\r\n    protected $fillable = [''flavour'', ''description'', ''price'']\r\n    protected $timestamps = false;\r\n}\r\nEloquent has a bunch defaults set up. The model assumes a table of the plural version of its own name. I.e., cupcakes. It assumes an id that’s an auto-incrementing integer. Both of these assumptions are already valid for our table, making it an easy setup. It also assumes two fields for datetime last updated and datetime created. This isn’t true for us, so we’ve put false for that.\r\nActually using these is super easy. It’s near identical to our previous code, and not by accident.\r\nThe only difference is\r\n$cupcake = new Cupcake($database);\r\n$data = [''cupcakes''=>$cupcake->all()];\r\nIs replaced with this\r\n$data = [''cupcakes'' => Cupcake::all()];\r\nThe benefit here is tenuous. Again we hit the issue that this application is too simple to benefit from the changes we’re making. And again, it’s vital to understand that basically any real application will be doing a ton of stuff. It will have a specials page, admin system, possibly a search, order system, your customers might have an order summary, and so on. All of the above is much easier to do with something like an ORM. (These assume data exists that isn’t actually present in our examples.)\r\n//customer’s total orders\r\nApp\\Order::where(''customer_id'', $customer_id)->sum(''total'')->get();\r\n// cupcakes in an order\r\nApp\\Order::find($order_id)->cupcakes;\r\n// cupcakes that are on special\r\nApp\\Order::where(''on_special'', true)->order(''price'')->get();\r\nNone of this requires any modifications or maintenance. The addition of new fields to the table doesn’t mean any changes to update or creation SQL, and there aren’t multi-line SQL statements scattered through your classes.\r\nDoing it in Laravel\r\nThe next and final step is an exercise for the reader. We’re at the level we need to be at now where the only real approach is to use a full framework. The benefits here are many. We get a lot of great features, like the ORM, database migrations, testing frameworks, and more. Now that you’re here, if you want a great guide to using Laravel you won’t find a better resource than Laracasts.', '2017-07-10 03:46:35', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `comment_data` blob,
  `comment_guid` char(36) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approved_ip_address` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_ip_address` varchar(50) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_ip_address` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `article_id`, `user_id`, `parent_id`, `level`, `comment_data`, `comment_guid`, `approved`, `approved_by`, `approved_date`, `approved_ip_address`, `created_date`, `created_by`, `created_ip_address`, `modified_date`, `modified_by`, `modified_ip_address`) VALUES
(1, 32, 1, 0, 0, 0x54686973206973206120746573742e, '92AD0A41-8A05-456E-BC0A-D0438CA8', 1, NULL, NULL, NULL, '2017-08-01 08:36:31', 6, '127.0.0.1', NULL, NULL, NULL),
(2, 32, 1, 0, 0, 0x5468697320697320636f6d6d656e74204f6e652e, 'B4919C02-3CD8-41A0-8B3C-9457B91C', 1, NULL, NULL, NULL, '2017-08-01 08:36:54', 6, '127.0.0.1', NULL, NULL, NULL),
(3, 32, 1, 1, 1, 0x5468697320697320612074657374207265706c792e, '5B51D6EA-EEB4-49AF-AA15-9D25B2D9', 1, NULL, NULL, NULL, '2017-08-01 08:37:09', 6, '127.0.0.1', NULL, NULL, NULL),
(4, 32, 1, 0, 0, 0x54686973206973206120746573742e, '9E397A29-D427-48D3-9614-6DB87893', 1, NULL, NULL, NULL, '2017-08-01 08:38:52', 6, '127.0.0.1', NULL, NULL, NULL),
(5, 32, 1, 0, 0, 0x41207265646576656c6f70656420414e5a205374616469756d20636f756c6420686176652061206361706163697479206173206c6f772061732036352c30303020e280932061207363656e6172696f207468617420776f756c642068757274205379646e65792773206368616e636573206f662061747472616374696e67206d616a6f72206576656e747320696e20746865206675747572652c2073756368206173206120666f6f7462616c6c20576f726c64204375702066696e616c2e0a0a4d6f7265207468616e203132206d6f6e7468732061667465722074686520737461746520676f7665726e6d656e7420616e6e6f756e63656420612024312e3662696c6c696f6e205379646e65792073746164696120706c616e2c2074686520616c6c6f636174696f6e206f662074686f73652066756e64732072656d61696e7320756e6365727461696e206265796f6e642061206e6577207374616469756d2061742050617272616d61747461206265696e67206275696c74206174206120636f7374206f662024333630206d696c6c696f6e2e, '3E51F082-1657-47D7-AAFC-A92A5EE8', 1, NULL, NULL, NULL, '2017-08-01 08:47:11', 6, '127.0.0.1', NULL, NULL, NULL),
(6, 32, 1, 2, 1, 0x497420697320756e64657273746f6f642074686174206f6e65206c6f7765722d636f7374206f7074696f6e206265696e6720636f6e7369646572656420666f722070726573656e746174696f6e20697320666f7220746865207374616469756d2073656174696e6720746f20626520726564756365642066726f6d206120706c616e6e65642037352c30303020636170616369747920746f206265747765656e2036352c30303020616e642037302c3030302e, 'E369CB0B-288E-4421-9848-5AD8921B', 1, NULL, NULL, NULL, '2017-08-01 08:50:19', 6, '127.0.0.1', NULL, NULL, NULL),
(7, 32, 1, 1, 1, 0x546869732069732070726574747920617765736f6d652120477265617420776f726b21, 'CBF9811E-9601-4301-A8E4-6C37DB11', 1, NULL, NULL, NULL, '2017-08-01 09:02:39', 6, '127.0.0.1', NULL, NULL, NULL),
(8, 32, 1, 7, 2, 0x5468616e6b73, 'E86200DB-0FB9-4B6E-B053-548DFDC5', 1, NULL, NULL, NULL, '2017-08-01 09:09:45', 6, '127.0.0.1', NULL, NULL, NULL),
(9, 32, 1, 0, 0, 0x546865206d6f73742068656c7066756c20636f6d6d656e74206576657221, 'DEFCFB9A-1E8F-4712-8E47-184607C9', 1, NULL, NULL, NULL, '2017-08-01 09:11:08', 6, '127.0.0.1', NULL, NULL, NULL),
(10, 5, 1, 0, 0, 0x74657374, '235ED079-D813-4B69-80B6-0CF1CC43', 1, NULL, NULL, NULL, '2017-08-01 09:58:40', 6, '127.0.0.1', NULL, NULL, NULL),
(11, 5, 1, 10, 1, 0x70657266656374, '8C48228A-9F1B-4EB8-AC3D-50034757', 1, NULL, NULL, NULL, '2017-08-01 09:59:41', 6, '127.0.0.1', NULL, NULL, NULL),
(12, 5, 1, 11, 2, 0x536f6d652070697a7a6173206172652067657474696e67206d6f726520657870656e7369766520616e6420736f6d6520617265206c696b656c7920746f2067657420736d616c6c657220617320446f6d696e6f277320747269657320746f207368616b65206f66662069747320696d6167652061732074686520686f6d65206f6620646972742d63686561702070697a7a61732e0a0a54686520666173742d666f6f64206769616e742773206e6577206d656e7520697320736c6174656420666f72207075626c6963206c61756e63682074686973207765656b656e64206275742061206c65616b656420636f70792c206f627461696e65642062792046616972666178204d656469612c2073686f777320736576656e206e65772070697a7a61732c206d6f7374207769746820616e20696e63726561736564207072696365207461672e, 'BB148D68-E3FC-4770-B9FE-9B4C6007', 1, NULL, NULL, NULL, '2017-08-01 10:03:00', 6, '127.0.0.1', NULL, NULL, NULL),
(13, 5, 1, 0, 0, 0x74657374, '1C4A218F-78F0-410E-B9F1-3788582C', 1, NULL, NULL, NULL, '2017-08-01 10:36:53', 1, '127.0.0.1', NULL, NULL, NULL),
(14, 5, 1, 13, 1, 0x686f776479, 'C3200A91-B985-4E5B-A1F4-96F09AA8', 1, NULL, NULL, NULL, '2017-08-01 10:42:20', 1, '127.0.0.1', NULL, NULL, NULL),
(15, 5, 1, 0, 0, 0x22546865206b657920666f63757320706f696e747320666f722074686973206e65772063616d706169676e2077696c6c2064726976652061207175616c6974792068616c6f20666f72207468652077686f6c65206272616e642c20656e737572696e6720746861742061732061206272616e6420446f6d696e6f2773207374616e647320666f72206d6f7265207468616e206a7573742063686561702024352070697a7a61732c222074686520636f6d70616e79207361696420696e2074686520646f63756d656e742e0a0a546865206d6f766520636f6d657320617420612074696d65207768656e2074686520636f6d70616e7920697320616c736f207265706f727465646c7920616c736f207365656b696e6720746f20726564756365207468652073697a65206f66206974732070697a7a61732e20497420616c736f20666f6c6c6f777320612046616972666178204d6564696120696e7665737469676174696f6e20696e204d61726368207468617420666f756e642068756e6472656473206f66207374616666206163726f737320666f75722073746174657320686164206265656e20756e646572706169642c20776974682073746f7265206f776e65727320626c616d696e6720746865206672616e636869736565206d6f64656c2e, '83791F3B-D8D5-4327-BD0D-8148196A', 1, NULL, NULL, NULL, '2017-08-01 10:52:11', 1, '127.0.0.1', NULL, NULL, NULL),
(16, 5, 1, 0, 0, 0x446f6d696e6f2773206672616e636869736565732061726520616c736f20746865207375626a656374206f66206d6f7265207468616e20323020696e7665737469676174696f6e732062792074686520776f726b706c61636520726567756c61746f722c20746865204661697220576f726b204f6d627564736d616e2e200a0a556e6465722074686520726576616d702074686520224368656627732042657374222072616e67652c207768696368207479706963616c6c7920696e636c756465732070697a7a617320746861742073656c6c20666f72202431322e39352c2077696c6c2062652072656d6f7665642e20496e73746561642c2074686572652077696c6c2062652061206e657720225072656d69756d2052616e6765222073656c656374696f6e20696e636c756465642c20776869636820696e636c7564657320736978206e65772070697a7a61732073656c6c696e672061742061207265636f6d6d656e646564207072696365206f66202431352e393020656163682e0a0a446f6d696e6f27732070697a7a61207072696365732064696666657220646570656e64696e67206f6e207468652073746f72652e20546865207072656d69756d2070697a7a61732077696c6c20686f7765766572206265636f6d6520616d6f6e67737420746865206d6f737420657870656e736976652070697a7a6173206f6e20746865206d656e752e0a0a446f6d696e6f2773207361696420696e20612073746174656d656e7420746865207072656d69756d2072616e676520776f756c64206265206c617267657220616e64206861766520226e6f7469636561626c79206d6f726520746f7070696e6773207468616e207468652043686566277320426573742072616e6765222e0a0a224f7572206f6e676f696e6720666f63757320697320746f2073617469736679206d6f726520637573746f6d6572732c20616e6420656e73757265206f7572206672616e636869736573206172652070726f66697461626c6520616e64207375737461696e61626c652c222074686520636f6d70616e7920736169642e, '0C4C8239-CCF9-4DF3-90BB-61DEBD16', 1, NULL, NULL, NULL, '2017-08-01 10:54:49', 1, '127.0.0.1', NULL, NULL, NULL),
(17, 5, 1, 0, 0, 0x4f6e652063617375616c7479206f6620746865206e6577206d656e7520696e204e53572077696c6c2062652074686520747261646974696f6e616c204d61726768657269746120e2809320612070697a7a61207374796c652074686174206461746573206261636b20746f20746865206d69642d313974682063656e747572792e0a0a536f6d65206974656d732061707065617220746f20626520696e6372656173696e6720696e20707269636520756e64657220746865206e6577206d656e752077697468207468652022436869636b656e20616e642043616d656d62657274222c207768696368207479706963616c6c792073656c6c7320666f722061726f756e64202431322e39352c20696e6372656173696e6720746f202431352e39302e0a0a4275742061732070726963657320676f2075702c2070697a7a612073697a65732061726520657870656374656420746f20676f20646f776e2e20446f6d696e6f2773206973207265706f727465646c7920736872696e6b696e67207468652073697a65206f66206974732070697a7a617320696e20612062696420746f2065617365207468652066696e616e6369616c207072657373757265206f6e206672616e636869736565732e, '53F1651F-DDB3-4ABB-B748-CAC2095A', 1, NULL, NULL, NULL, '2017-08-01 10:55:13', 1, '127.0.0.1', NULL, NULL, NULL),
(18, 5, 1, 13, 1, 0x4173205472756d70277320666972696e6720616e642072756e6e696e67206c696e65207374726574636865732065766572206675727468657220726f756e642074686520576869746520486f75736520626c6f636b202d205265696e63652c205365616e2c20546865204d6f6f636820696e20746865206c61737420646179206f722074776f20616c6f6e65202d20796f752764206861766520746f206665656c20666f7220746865206e6577206368696566206f662073746166662e0a0a47656e6572616c204a6f686e204b656c6c79206973206c617465206f6620746865204d6172696e6520436f72707320616e6420746875732c20796f75276420696d6167696e652c206163637573746f6d656420746f20676976696e67206f726465727320616e6420686176696e67207468656d206f62657965642e200a0a446f6e616c64204a2e205472756d702c2061706172742066726f6d206265696e6720286e6f2c207265616c6c792c20746869732069732074727565292074686520507265736964656e742c20697320616c736f20436f6d6d616e6465722d696e2d4368696566206f66207468652055532041726d656420466f726365732c206465737069746520646f6467696e67206d696c69746172792073657276696365206265636175736520686520686164207370757273206f6e2068697320666565742e204e6f7420746865206d616e6c7920546578617320636f77626f7920736f7274206f662073707572732c2062757420746865206f6e657320796f752063616e2774207365652c20776869636820697320616c776179732068616e6479207768656e20796f7527726520646f6467696e67206d696c697461727920736572766963652e0a0a416e797761792c206865206a757374206c6f7665732063616c6c696e672074686520627261737320226d792047656e6572616c73222e205361797320697420616c6c207468652074696d652e20497427732068697320776179206f6620696d7072657373696e67206f6e20616c6c2061726f756e642068696d2074686174204845275320746865206775792077686f20676976657320746865206f72646572732e0a0a47656e6572616c204b656c6c792c206265696e67206120666f726d6572204d6172696e652c20776f756c6420756e646f75627465646c7920726563697465206576657279206d6f726e696e6720746865204d6172696e65206d6f74746f2c207768696368206973202273656d70657220666964656c6973222e204974206d65616e732022616c7761797320666169746866756c222c20616e6420697320736f20706f77657266756c20746865726520686173206e65766572206265656e2061206d7574696e792062792061204d6172696e652073696e636520746865206d6f74746f207761732061646f7074656420696e20313838332e2054686572652069732c206f6620636f757273652c20612066697273742074696d6520666f722065766572797468696e672e0a0a4b656c6c792773206e6f742c20686f77657665722c207468652066697273742047656e6572616c20746f206265206472616674656420696e20617320576869746520486f757365204368696566206f66205374616666206279206120507265736964656e7420696e20612073706f74206f6620626f746865722e0a0a546865206c61737420756e666f7274756e61746520696e207468617420706f736974696f6e207761732047656e6572616c20416c6578616e64657220486169672c2062726f7567687420696e20746f2061737369737420507265736964656e742052696368617264204e69786f6e20647572696e6720746865205761746572676174652063617461636c79736d2e0a0a5468617420776f726b65642077656c6c2e204e69786f6e20626563616d6520746865206669727374202d20616e6420736f206661722c206f6e6c79202d20555320507265736964656e7420746f2072657369676e2e20496e2064697367726163652e, '650F0E20-55EF-48B8-A20E-B160627F', 1, NULL, NULL, NULL, '2017-08-01 10:55:50', 1, '127.0.0.1', NULL, NULL, NULL),
(19, 5, 1, 16, 1, 0x4173205472756d70277320666972696e6720616e642072756e6e696e67206c696e65207374726574636865732065766572206675727468657220726f756e642074686520576869746520486f75736520626c6f636b202d205265696e63652c205365616e2c20546865204d6f6f636820696e20746865206c61737420646179206f722074776f20616c6f6e65202d20796f752764206861766520746f206665656c20666f7220746865206e6577206368696566206f662073746166662e0a0a47656e6572616c204a6f686e204b656c6c79206973206c617465206f6620746865204d6172696e6520436f72707320616e6420746875732c20796f75276420696d6167696e652c206163637573746f6d656420746f20676976696e67206f726465727320616e6420686176696e67207468656d206f62657965642e200a0a446f6e616c64204a2e205472756d702c2061706172742066726f6d206265696e6720286e6f2c207265616c6c792c20746869732069732074727565292074686520507265736964656e742c20697320616c736f20436f6d6d616e6465722d696e2d4368696566206f66207468652055532041726d656420466f726365732c206465737069746520646f6467696e67206d696c69746172792073657276696365206265636175736520686520686164207370757273206f6e2068697320666565742e204e6f7420746865206d616e6c7920546578617320636f77626f7920736f7274206f662073707572732c2062757420746865206f6e657320796f752063616e2774207365652c20776869636820697320616c776179732068616e6479207768656e20796f7527726520646f6467696e67206d696c697461727920736572766963652e0a0a416e797761792c206865206a757374206c6f7665732063616c6c696e672074686520627261737320226d792047656e6572616c73222e205361797320697420616c6c207468652074696d652e20497427732068697320776179206f6620696d7072657373696e67206f6e20616c6c2061726f756e642068696d2074686174204845275320746865206775792077686f20676976657320746865206f72646572732e0a0a47656e6572616c204b656c6c792c206265696e67206120666f726d6572204d6172696e652c20776f756c6420756e646f75627465646c7920726563697465206576657279206d6f726e696e6720746865204d6172696e65206d6f74746f2c207768696368206973202273656d70657220666964656c6973222e204974206d65616e732022616c7761797320666169746866756c222c20616e6420697320736f20706f77657266756c20746865726520686173206e65766572206265656e2061206d7574696e792062792061204d6172696e652073696e636520746865206d6f74746f207761732061646f7074656420696e20313838332e2054686572652069732c206f6620636f757273652c20612066697273742074696d6520666f722065766572797468696e672e0a0a4b656c6c792773206e6f742c20686f77657665722c207468652066697273742047656e6572616c20746f206265206472616674656420696e20617320576869746520486f757365204368696566206f66205374616666206279206120507265736964656e7420696e20612073706f74206f6620626f746865722e0a0a546865206c61737420756e666f7274756e61746520696e207468617420706f736974696f6e207761732047656e6572616c20416c6578616e64657220486169672c2062726f7567687420696e20746f2061737369737420507265736964656e742052696368617264204e69786f6e20647572696e6720746865205761746572676174652063617461636c79736d2e0a0a5468617420776f726b65642077656c6c2e204e69786f6e20626563616d6520746865206669727374202d20616e6420736f206661722c206f6e6c79202d20555320507265736964656e7420746f2072657369676e2e20496e2064697367726163652e, '182D7ECC-C53B-4AA5-A5FF-115E6AC2', 1, NULL, NULL, NULL, '2017-08-01 10:56:06', 1, '127.0.0.1', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `user_id`, `body`, `created_at`, `status`) VALUES
(6, 5, 1, 'REMOVED FOR INAPPROPRIATE CONTENT', '2017-07-10 03:01:23', 0),
(7, 9, 1, 'Sounds fun..', '2017-07-10 03:47:13', 0),
(8, 5, 1, 'As Trump''s firing and running line stretches ever further round the White House block - Reince, Sean, The Mooch in the last day or two alone - you''d have to feel for the new chief of staff.\r\n\r\nGeneral John Kelly is late of the Marine Corps and thus, you''d imagine, accustomed to giving orders and having them obeyed. \r\n\r\nDonald J. Trump, apart from being (no, really, this is true) the President, is also Commander-in-Chief of the US Armed Forces, despite dodging military service because he had spurs on his feet. Not the manly Texas cowboy sort of spurs, but the ones you can''t see, which is always handy when you''re dodging military service.\r\n\r\nAnyway, he just loves calling the brass "my Generals". Says it all the time. It''s his way of impressing on all around him that HE''S the guy who gives the orders.\r\n\r\nGeneral Kelly, being a former Marine, would undoubtedly recite every morning the Marine motto, which is "semper fidelis". It means "always faithful", and is so powerful there has never been a mutiny by a Marine since the motto was adopted in 1883. There is, of course, a first time for everything.\r\n\r\nKelly''s not, however, the first General to be drafted in as White House Chief of Staff by a President in a spot of bother.\r\n\r\nThe last unfortunate in that position was General Alexander Haig, brought in to assist President Richard Nixon during the Watergate cataclysm.\r\n\r\nThat worked well. Nixon became the first - and so far, only - US President to resign. In disgrace.', '2017-08-01 10:56:57', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'News'),
(2, 'Humour'),
(3, 'Marvel'),
(4, 'DC'),
(5, 'Top Liked'),
(6, 'Popular');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(15) NOT NULL DEFAULT '',
  `password` varchar(128) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `membership` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `username`, `password`, `created_at`, `membership`) VALUES
(1, 'Test', 'test@test.com', 'default.png', 'test', '$2y$10$yEBmFgU2kYY8GjIGJNUH2.xzk.rBdeQyaF6s.EfZZFIrgiT98e69y', '2017-07-19 10:57:03', 1),
(2, 'barry doherty', 's3357072@student.rmit.edu.au', 'default.png', '1212', '$2y$10$39UGbjZ0CV9Ye6QIV5GT5eHV9lOClGJBrGpkoIOs7mOiQ/9BFi/4K', '2017-07-28 08:02:41', 3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
