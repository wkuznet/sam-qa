<?php

/* 
 Plugin Name: Sam Q-and-A Plugin
 Plugin URI: http://kontakt-plus.com
 Description: Вопросы-Ответы
 Version: 1.0 
 Author: Kontakt-Plus 
 Author URI: http://kontakt-plus.com 
 */

add_action('init', 'vopros_post_type');
function vopros_post_type()
{
	$labels = array(
		'name' => 'Вопросы', // основное название для типа записи
		'singular_name' => 'Вопрос', // название для одной записи этого типа
		'add_new' => 'Добавить новый', // для добавления новой записи
		'add_new_item' => 'Добавить новый Вопрос', // заголовка у вновь создаваемой записи в админ-панели.
		'edit_item' => 'Редактировать Вопрос', // для редактирования типа записи
		'new_item' => 'Новый Вопрос', // текст новой записи
		'view_item' => 'View Вопрос', // для просмотра записи этого типа.
		'search_items' => 'Искать Вопрос', // для поиска по этим типам записи
		'not_found' => 'Не найдено никакого Вопроса', // если в результате поиска ничего не было найдень
		'not_found_in_trash' => 'Вопрос в Корзине не найдено', // если не было найдено в корзине
		'parent_item_colon' => 'Что-то', // для родительских типов. для древовидных типов
		'menu_name' => 'Вопросы',
		'all_items' => 'Все вопросы'// название меню
	);
	$args = array(
		'label' => 'Вопрос', 
		'labels' => $labels, 
		'description' => '' ,
		'public' => true ,
		'publicly_queryable' => null,
		'exclude_from_search' => null,
		'show_ui' => true,
		'show_in_menu' => null ,
		'menu_position' => null ,
		'menu_icon' => 'dashicons-format-chat' ,
		'capability_type' => 'post' ,
		'hierarchical' => false,
		'supports' => array('title'),
		'taxonomies' => array(''),
		'has_archive' => false,
		'rewrite' => true,
		'query_var' => true,
		'show_in_nav_menus' => null
	);
	register_post_type( 'vopros', $args );
}

include('options-page.php');
include('vopros-block.php');
include('otvet-block.php');
?>