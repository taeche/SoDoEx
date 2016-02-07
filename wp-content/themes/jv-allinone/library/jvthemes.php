<?php
/*
 # JV Library - JV Theme
 # ------------------------------------------------------------------------
 # author    Open Source Code Solutions Co
 # copyright Copyright (C) 2011 joomlavi.com. All Rights Reserved.
 # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL or later.
 # Websites: http://www.joomlavi.com
 # Technical Support:  http://www.joomlavi.com/my-tickets.html
-------------------------------------------------------------------------*/

/**
 * JV Allinone functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link http://codex.wordpress.org/Plugin_API 
 * 
 * @package Allinone
 *
 * @subpackage JV_Allinone
 *
 * @since JV Allinone 1.0
 *
*/
 

if(!defined ('ADMINDOMAIN')) define ('ADMINDOMAIN', 'wp-admin');
if(!defined ('JVTHEME')) define ('JVTHEME', get_template());

JVLibrary::jvThemeInit();
//JVLibrary::widget();
function jvThemeSetting() {
        add_theme_page(__("Theme Settings",'jv_allinone'), __("Theme Settings",'jv_allinone'), 'manage_options', 'theme-settings-page', 'theme_settings_page_callback'  );
}

function afterInstallData(){
    JVLibrary::afterInstallData();
}
function InstallDataSanple(){
    JVLibrary::InstallDataSanple();
}

function installPlugins() {
    JVLibrary::installPlugins();
}

function loadLanguages(){
    JVLibrary::loadLanguages();
}
function onAfterInitialise(){
    JVLibrary::onAfterInitialise();
}
function onBeforePrintStyle(){
    JVLibrary::onBeforePrintStyle();
}
function onAfterPrintStyle(){
    JVLibrary::onAfterPrintStyle();
}
function jvPrintInline(){
    JVLibrary::jvPrintInline();
}
function loadCustomStyle(){
	$config = JVLibrary::getConfig();
    wp_enqueue_style('chosen', JVLibrary::urls('theme') .'/css/chosen.css');
    wp_enqueue_style('font-awesome', JVLibrary::urls('theme') .'/css/font-awesome.css');
    wp_enqueue_style('shop', JVLibrary::urls('theme') .'/css/shop.css');
    wp_enqueue_style('blog', JVLibrary::urls('theme') .'/css/blog.css');
    wp_enqueue_style('menu', JVLibrary::urls('theme') .'/css/menu.css');
	
	if(!isset($config->show_demo)) return;
	if($config->show_demo != 'yes') return;
    wp_enqueue_style('switcher', JVLibrary::urls('theme') .'/css/switcher.css');
}
