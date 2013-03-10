<h1>Docs</h1>

<table class="table table-bordered table-striped">
<thead>
<tr>
<th style="width:5%;">No</th>
<th style="width:45%;">Directory</th>
<th style="width:20%;">Generated</th>
<th style="width:15%;">Action</th>

</tr>
</thead>

<tbody>

<?php foreach ($data['dirs'] as $dir): ?>
<tr>
<td><?php echo isset($i) ? ++$i : $i = 1; ?></td>
<td><a href="<?php echo Uri::create('dbdocs/'.$dir['name'].'dbdoc/index.html'); ?>" target="_blank"><?php echo $dir['name'].'dbdoc/'; ?></a></td>
<td><?php echo $dir['time_ago']; ?></td>
<td><a href="<?php echo Uri::create('index/delete/'.$dir['name']); ?>"><i class="icon-trash"></i></a></td>
</tr>
<?php endforeach; ?>

</tbody>
</table><!--/.table-->
