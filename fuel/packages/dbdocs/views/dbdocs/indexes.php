<h1>Indexes</h1>

<table class="table table-bordered table-striped">
<thead>
<tr>
<th style="width:25%;">Name</th>
<th style="width:25%;">Table</th>
<th style="width:25%;">Column</th>
<th style="width:25%;">Extra <span class="_extra"><i class="icon-question-sign"></i></span></th>
</tr>
</thead>

<tbody>

<?php foreach ($tables as $table_name => $table_infos): ?>
	<?php foreach ($table_infos['indexes'] as $index_name => $index_infos): ?>
		<?php foreach ($index_infos['columns'] as $column_name): ?>
<tr>
<td><?php echo $index_name; ?></td>
<td><a href="table_<?php echo $table_name; ?>.html"><?php echo $table_name; ?></a></td>

<?php if(empty($index_infos['foreign_key']['table_name'])): ?>

<td><?php echo $column_name; ?></td>

<?php else: ?>

<td>
<a href="table_<?php echo $index_infos['foreign_key']['table_name']; ?>.html"><?php echo $column_name; ?></a>
<span class="_foreign_key" title="<?php echo $index_infos['foreign_key']['table_name'].'.'.$index_infos['foreign_key']['column_name']; ?>" ><i class="icon-question-sign"></i>
</td>

<?php endif; ?>

<td><span class="label label-info"><?php echo implode('</span> <span class="label label-info">', $index_infos['extras']); ?></span></td>
</tr>

		<?php endforeach; ?>
	<?php endforeach; ?>
<?php endforeach; ?>

</tbody>
</table><!--/.table-->
