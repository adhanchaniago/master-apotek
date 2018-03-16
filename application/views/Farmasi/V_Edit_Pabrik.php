<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if($Level="IT Support" OR $Level="Owner") : ?>
	<script>
	    window.onunload = refreshParent;
	    function refreshParent() {
	        window.opener.location.reload();
	    }
	</script>
	<?= form_open('pabrik/proses-edit-data/'.$Data->id_pabrik) ?>
		<table>
			<tbody>
				<tr>
					<td><label>Nama Pabrik</label></td>
					<td>:</td>
					<td><?= form_input('nm_pabrik',$Data->nm_pabrik,array('class' => 'form-control')) ?></td>
				</tr>
			</tbody>
		</table>
		<br />
		<button type="submit">Simpan</button> <button type="button" onclick="window.close()">Tutup</button>
	<?= form_close() ?>
<?php endif; ?>