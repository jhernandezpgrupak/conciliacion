<form enctype="multipart/form-data" action="<?= base_url() ?>upload" method="POST" 
	name="form" 
	id="form">
	<label for="file1">Archivo UTVT:</label>
	<input type="file" name="file1" id="file1">
	<label for="file2">Archivo Banco:</label>
	<input type="file" name="file2" id="file2">
	<button>Send</button>
</form>