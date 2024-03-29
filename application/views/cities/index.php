	<table class="table">
		<thead>
        <tr>
            <th>#</th>
            <th>Título</th>
			<th>Prioridad</th>
			<th>Visible</th>
            <th colspan="2">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($cities as $key=>$object):?>
            <tr class="element">
            <td><?=$key+1 ?></td>
            <td class="element-title"><?= $object->title ?></td>
			<td width="100" class="element-title"><?= $object->priority ?></td>
			<td width="100"><a class="pull-center" href="<?= site_url('admin/cities/visible/') . $object->id ?>">
				<i class="fa <?= $object->visible ? 'fa-check-circle' : 'fa-circle-o' ?> pull-center" data-toggle="tooltip" 
				data-placement="top" title="<?= $object->visible ? 'Ciudad Visible' : 'Ciudad Oculta' ?>"></i></a></td>
            <td width="80"><?= anchor('admin/cities/edit/'.$object->id, 'Editar','class="btn btn-warning"'); ?></td>
            <td width="80"><?= anchor('admin/cities/destroy/'.$object->id, 'Eliminar','class="btn btn-danger destroy"'); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
	</table>
	
	<?= anchor('admin/cities/create','Crear','class="btn btn-primary"'); ?>