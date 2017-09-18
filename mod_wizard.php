<?php

defined('_JEXEC') or die;


$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::root(true) ."/modules/mod_wizard/mod_wizard.css");

require_once dirname(__FILE__) . '/helper.php';
require JModuleHelper::getLayoutPath('mod_wizard', $params->get('layout', $params->get('tipo')));


?>