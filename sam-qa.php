<?php

/* 
 Plugin Name: Sam Q-and-A Plugin
 Plugin URI: http://kontakt-plus.com
 Description: Вопросы-Ответы
 Version: 1.0 
 Author: Kontakt-Plus 
 Author URI: http://kontakt-plus.com 
 */
require( 'options-page.php' );
require( 'vopros-block.php' );
require( 'otvet-block.php' );
 
add_action('init', 'vopros_post_type'); //регистрируем новый тип записей
function vopros_post_type()
{
	$labels = array( //лейблы для нашего типа записей
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
		'publicly_queryable' => true,
		'exclude_from_search' => null,
		'show_ui' => true,
		'show_in_menu' => null ,
		'menu_position' => null ,
		'menu_icon' => 'dashicons-format-chat' ,
		'capability_type' => 'post' ,
		'hierarchical' => false,
		'supports' => array('title'),
		'taxonomies' => array(''),
		'has_archive' => true,
		'rewrite' => true,
		'query_var' => true,
		'show_in_nav_menus' => null
	);
	register_post_type( 'vopros', $args );
}

/* ФОРМА ЗАДАТЬ ВОПРОС */
function show_qa_form() //создаем шорткод для вывода формы "Задать вопрос"
{
	$qaform_content = file_get_contents( plugin_dir_path( __FILE__ ) . 'sam-qa-form.php' );
	return $qaform_content;
}
add_shortcode('qaform', 'show_qa_form');

add_action( 'wp_enqueue_scripts', 'samqaform_scripts_load' ); //подключаем на страницу скрипты валидации и обработки ajax для формы "задать вопрос"
function samqaform_scripts_load() 
{
    wp_register_script( 'validate-script', plugins_url('/js/jquery.validate.min.js', __FILE__), array('jquery'), false, true );
	wp_register_script( 'samqaform-script', plugins_url('/js/samqaform.js', __FILE__), array('jquery'), false, true );
	wp_enqueue_script( 'validate-script' );
    wp_enqueue_script( 'samqaform-script' );
}

function add_vopros_data( $postid, $postmeta_arr ) //функция записывает данные вопроса в базу.. используется в vopros-block.php и для добавления с морды
{
    $defaults = array(
        'username' => '', 
        'useremail' => '', 
        'voprosdate' => date('d.m.Y'), 
        'voprostext' => ''
    );
    $postmeta_arr = wp_parse_args( $postmeta_arr, $defaults );
    
	$vopros_data = json_encode( $postmeta_arr, JSON_UNESCAPED_UNICODE );

	update_post_meta( $postid, 'sam_qa-vopros_data', $vopros_data ); // Обновляем данные в базе данных.
    
}

function send_qaform() //обработка формы "задать вопрос"
{
	$post_arr = filter_var_array($_POST, FILTER_SANITIZE_STRING); //очищаем значения массива $_POST от лишних символов
	extract( $post_arr );	//преобразуем ключи массива $_POST в переменные с соответствующими именами

    $new_vopros = array(
        'post_title' => $sam_qa_name . ' - ' . date( 'd-m-Y' ),
        'post_type' => 'vopros',
        'post_status' => 'draft'
    );
    if ( $new_id = wp_insert_post( $new_vopros, true ) ) {
        $new_vopros_meta_array = array(
            'username' => $post_arr['sam_qa_name'],
            'useremail' => $post_arr['sam_qa_email'],
            'voprosdate' => date( 'd.m.Y' ),
            'voprostext' => $post_arr['sam_qa_text']
        );
        add_vopros_data( $new_id, $new_vopros_meta_array );
        echo '<div class="alert-success">Спасибо! Ваш вопрос отправлен!<div>';
    }
	exit;
}
add_action('wp_ajax_send_qaform', 'send_qaform'); //работает для авторизованных пользователей
add_action('wp_ajax_nopriv_send_qaform', 'send_qaform'); //работает для неавторизованных
?>