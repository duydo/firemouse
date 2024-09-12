<?php
require_once 'Definition.class.php';
require_once 'DefinitionManager.class.php';
require_once 'View.class.php';
require_once '../../yaml/sfYaml.class.php';

$definitions = sfYaml::load('template-defs.yaml');
$dm = DefinitionManager::getInstance();
var_dump($definitions);
$dm->initialize($definitions);
$view = new View();
$view->abc = 'ABC';
//$view->title = 'Home Page';
$view->render('homepage');
?>

