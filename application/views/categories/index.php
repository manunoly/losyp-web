<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Título</th>
        <th>Icono</th>
        <th>Prioridad</th>
        <th>Visible</th>
        <th colspan="2">Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($categories as $key=>$object) { ?>
        <tr class="element">
        <td><?= $key+1 ?></td>
        <td class="element-title"><?= $object->title ?></td>
        <td><img src="<?= $object->icon ?>" height="40px" width="45px" title="<?= $object->icon ?>"
                 alt="<?= $object->icon ?>"></td>
        <td class="element-title"><?= $object->priority ?></td>
		<td><a class="pull-center" href="<?= site_url('admin/categories/visible/') . $object->id ?>">
            <i class="fa <?= $object->visible ? 'fa-check-circle' : 'fa-circle-o' ?> pull-center" data-toggle="tooltip" data-placement="top" 
			title="<?= $object->visible ? 'Categoría Visible' : 'Categoría Oculta' ?>"></i></a></td>
        <td width="80"><?= anchor('admin/categories/edit/' . $object->id, 'Editar', 'class="btn btn-warning"'); ?></td>
        <td width="80"><?= anchor('admin/categories/destroy/' . $object->id, 'Eliminar', 'class="btn btn-danger destroy"'); ?></td>
        </tr><?php } ?>
    </tbody>
</table>

<?= anchor('admin/categories/create', 'Crear', 'class="btn btn-primary"'); ?>
