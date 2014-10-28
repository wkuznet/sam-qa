<?php

function sam_qa_menu() 
{
	add_menu_page( 'Настройки "Вопросы-ответы"', '"Вопросы-ответы"', 'manage_options', 'sam-qa-page', 'sam_qa_callback');
}

add_action( 'admin_menu', 'sam_qa_menu' );
function sam_qa_callback() 
{
?>
<div class="wrap">
	<h2>Настройки плагина Вопросы-Ответы </h2>
	<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="sam_qa_mailto">E-mail для отправки уведомлений о новых вопросах</label>
				</th>
				<td>
					<input type="text" name="sam_qa_mailto" id="sam_qa_mailto" value="<?php echo get_option('sam_qa_mailto'); ?>" class="regular-text" />
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Сохранить изменения">
		</p>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="sam_qa_mailto" />
	</form>
</div>
<?php
}