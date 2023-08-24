<?= helper('form'); ?>
<?= form_open('upload', $attributes = ['enctype'=>'multipart/form-data', 'class'=>'form-control'])?>
	<label for="file1">Archivo UTVT:</label>
	<input type="file" name="file1" id="file1" accept=".xlsx">
	<label for="file2">Archivo Banco:</label>
	<input type="file" name="file2" id="file2" accept=".xlsx">
	<button>Send</button>
	<?php 
	if(isset($validation)){
		echo "file not success";
	} 
	?>	
<?= form_close(); ?> 