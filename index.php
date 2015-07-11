<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.06.15
 * Time: 16:31
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('BASE_DIR','/media/raspi/flexget');
//define('BASE_DIR','/opt/etc/flexget');
define('SERIES_GERMAN', BASE_DIR.'/series_german.yml');
define('SERIES_GERMAN_SUBBED', BASE_DIR.'/series_german_subbed.yml');
define('SERIES_ENGLISH', BASE_DIR.'/series_english.yml');

define('MOVIE_QUEUE', BASE_DIR.'/movie_queue.yml');

define('MOVIES_GERMAN', BASE_DIR.'/movies_german.yml');
define('MOVIES_GERMAN_SUBBED', BASE_DIR.'/movies_german_subbed.yml');
define('MOVIES_ENGLISH', BASE_DIR.'/movies_english.yml');

require_once 'Category_Store.php';
require_once 'Category_Series.php';
require_once 'Series_Category_Store.php';
require_once 'Series.php';
require_once 'Series_Store.php';
require_once 'Series_Category.php';

/**
 * @var Series_Category[] $categories
 */
$categories = array();

$series_store = new Series_Category_Store();
$series_store->set_name('series');
$series_store->set_base_dir(BASE_DIR);

$movie_store = new Series_Category_Store();
$movie_store->set_name('movies');
$movie_store->set_base_dir(BASE_DIR);

$series_german = new Series_Category();
$series_german->set_file(SERIES_GERMAN);
$series_german->set_name('Deutsch');
$series_german->set_title_unique('series_german');
$series_store->add($series_german);
$categories[] = $series_german;

$series_german_subbed = new Series_Category();
$series_german_subbed->set_file(SERIES_GERMAN_SUBBED);
$series_german_subbed->set_name('Deutsche Untertitel');
$series_german_subbed->set_title_unique('series_german_subbed');
$series_store->add($series_german_subbed);
$categories[] = $series_german_subbed;

$series_english = new Series_Category();
$series_english->set_file(SERIES_ENGLISH);
$series_english->set_name('Englisch');
$series_english->set_title_unique('series_english');
$series_store->add($series_english);
$categories[] = $series_english;

/*
$movies_german = new Series_Category();
$movies_german->set_file(MOVIES_GERMAN);
$movies_german->set_name('Deutsch');
$movies_german->set_title_unique('movies_german');

$movie_store->add($movies_german);
$categories[] = $movies_german;

$movies_german_subbed = new Series_Category();
$movies_german_subbed->set_file(MOVIES_GERMAN_SUBBED);
$movies_german_subbed->set_name('Deutsche Untertitel');
$movies_german_subbed->set_title_unique('movies_german_subbed');

$movie_store->add($movies_german_subbed);
$categories[] = $movies_german_subbed;

$movies_english = new Series_Category();
$movies_english->set_file(MOVIES_ENGLISH);
$movies_english->set_name('Englisch');
$movies_english->set_title_unique('movies_english');
$movie_store->add($movies_english);
$categories[] = $movies_english;
*/

$movie_queue = new Series_Category();
$movie_queue->set_file(MOVIE_QUEUE);
$movie_queue->set_name('Warteschlange');
$movie_queue->set_title_unique('movie_queue');
$movie_store->add($movie_queue);
$categories[] = $movie_queue;
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Flexget Config</title>
		<link rel="stylesheet" type="text/css" href="/css/style.css">
		<script type="text/javascript" language="JavaScript" src="/js/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" language="JavaScript" src="/js/html.js"></script>
		<meta charset="utf-8">
	</head>
	<body>
		<div class="tabbed">
			<div class="headlines">
				<div class="headline" data-category="movies">Filme</div>
				<div class="headline" data-category="series">Serien</div>
			</div>
			<div class="content">
				<div class="category" data-category="movies"><?php echo $movie_store->get_html(); ?></div>
				<div class="category" data-category="series"><?php echo $series_store->get_html(); ?></div>
			</div>
		</div>
	</body>
</html>
