<?php

/* Добавляем блок в основную колонку на страницах вопросов */
function vopros_add_custom_box() 
{
	add_meta_box( 'vopros_sectionid', 'Параметры вопроса', 'vopros_inner_custom_box', 'vopros' );
}
add_action('add_meta_boxes', 'vopros_add_custom_box');

/* HTML код блока */
function vopros_inner_custom_box()
{
	// Используем nonce для верификации
	wp_nonce_field( plugin_basename(__FILE__), 'vopros_noncename' );
	global $post;
	
	$vopros_meta_string = get_post_meta( $post->ID, 'sam_qa-vopros_data', true );
	
	if ( !empty( $vopros_meta_string ) ) 
	{
		
		$vopros_meta_array = json_decode( $vopros_meta_string, true );
		//print_r( $vopros_meta_array );
	}
?>

<table>
	<tr>
		<td>
			<label for="vopros_username">Имя</label><br>
			<input type="text" id="vopros_username" name="vopros_username" value="<?php echo $vopros_meta_array['username']; ?>" required>
		</td>
		<td>
			<label for="vopros_useremail">E-mail</label><br>
			<input type="text" id="vopros_useremail" name="vopros_useremail" value="<?php echo $vopros_meta_array['useremail']; ?>">
		</td>
		<td>
			<label for="vopros_datetime">Дата</label><br>
			<input type="text" id="vopros_datetime" name="vopros_datetime" value="<?php echo $vopros_meta_array['voprosdate'] ? $vopros_meta_array['voprosdate'] : date( 'd.m.Y' ); ?>" readonly>
		</td>
	</tr>
</table>
<p>
	<label for="vopros_text_vopros">Текст вопроса</label><br>
	<textarea id="vopros_text_vopros" name="vopros_text_vopros" cols="65" rows="5" style="width:100%;"><?php echo $vopros_meta_array['voprostext']; ?></textarea>
</p>

<script>
	jQuery(function($){
		$("#vopros_datetime").datepicker();
	});
</script>
<?php
}

/* Сохраняем данные, когда пост сохраняется */
function vopros_save_postdata( $post_id )
{
	// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
	if ( ! wp_verify_nonce( $_POST['vopros_noncename'], plugin_basename(__FILE__) ) )
		return $post_id;

	// проверяем, если это автосохранение ничего не делаем с данными нашей формы.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;

	// проверяем разрешено ли пользователю указывать эти данные
	if ( 'page' == $_POST['vopros'] && ! current_user_can( 'edit_page', $post_id ) ) 
	{
		return $post_id;
	} elseif( ! current_user_can( 'edit_post', $post_id ) ) 
	{
		return $post_id;
	}

	// Убедимся что поле установлено.
	//if ( ! isset( $_POST['vopros_text_vopros'] ) && !isset( $_POST['vopros_username'] ) && !isset( $_POST['vopros_useremail'] ) && !isset( $_POST['vopros_datetime'] ) )
	//	return;

	// Все ОК. Теперь, нужно найти и сохранить данные
	// Очищаем значение поля input.
	$post_array_vopros = filter_var_array($_POST, FILTER_SANITIZE_STRING);
    
    $vopros_array = array(
        'username' => $post_array_vopros['vopros_username'],
        'useremail' => $post_array_vopros['vopros_useremail'],
        'voprosdate' => $post_array_vopros['vopros_datetime'],
        'voprostext' => $post_array_vopros['vopros_text_vopros']
    );
    add_vopros_data( $post_id, $vopros_array );
    
}
add_action( 'save_post', 'vopros_save_postdata' );
