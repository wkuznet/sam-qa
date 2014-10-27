<?php

/* Добавляем блок в основную колонку на страницах вопросов */
function otvet_add_custom_box() 
{
	add_meta_box( 'otvet_sectionid', 'Параметры ответа', 'otvet_inner_custom_box', 'vopros' );
}
add_action('add_meta_boxes', 'otvet_add_custom_box');

/* HTML код блока */
function otvet_inner_custom_box()
{
	// Используем nonce для верификации
	wp_nonce_field( plugin_basename(__FILE__), 'otvet_noncename' );
	global $post;
?>

<table>
	<tr>
		<td>
			<label for="otvet_username">Имя</label><br>
			<input type="text" id="otvet_username" name="otvet_username" value="">
		</td>
		<td>
			<label for="otvet_datetime">Дата</label><br>
			<input type="text" id="otvet_datetime" name="otvet_datetime" value="">
		</td>
	</tr>
</table>
<p>
	<label for="otvet_text_otvet">Текст ответа</label><br>
	<textarea id="otvet_text_otvet" name="otvet_text_otvet" cols="65" rows="5" style="width:100%;"><?php echo get_post_meta( $post->ID, 'sam_qa-otvet_text', true ); ?></textarea>
</p>

<?php
}

/* Сохраняем данные, когда пост сохраняется */
function otvet_save_postdata( $post_id )
{
	// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
	if ( ! wp_verify_nonce( $_POST['otvet_noncename'], plugin_basename(__FILE__) ) )
		return $post_id;

	// проверяем, если это автосохранение ничего не делаем с данными нашей формы.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;

	// проверяем разрешено ли пользователю указывать эти данные
	if ( 'page' == $_POST['otvet'] && ! current_user_can( 'edit_page', $post_id ) ) {
		  return $post_id;
	} elseif( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	// Убедимся что поле установлено.
	if ( ! isset( $_POST['otvet_text_otvet'] ) )
		return;

	// Все ОК. Теперь, нужно найти и сохранить данные
	// Очищаем значение поля input.
	$my_data = sanitize_text_field( $_POST['otvet_text_otvet'] );

	// Обновляем данные в базе данных.
	update_post_meta( $post_id, 'sam_qa-otvet_text', $my_data );
}
add_action( 'save_post', 'otvet_save_postdata' );
