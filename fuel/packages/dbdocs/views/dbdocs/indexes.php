<h1>Indexes</h1>

<form class="form-search">
<input id="_indexes_search" type="text" class="span2 search-query" placeholder="Search">
</form>

<table id="_indexes_table" class="table table-bordered table-striped">
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
<td><a href="table_<?php echo $table_name; ?>.html#_column_<?php echo $column_name; ?>"><?php echo $index_name; ?></a></td>
<td><a href="table_<?php echo $table_name; ?>.html"><?php echo $table_name; ?></a></td>

<td><?php echo $column_name; ?></td>

<td><span class="label label-info"><?php echo implode('</span> <span class="label label-info">', $index_infos['extras']); ?></span></td>
</tr>

		<?php endforeach; ?>
	<?php endforeach; ?>
<?php endforeach; ?>

</tbody>
</table><!--/.table-->

<script>
$(document).ready(function() {
	$('input#_indexes_search').quicksearch('table#_indexes_table tbody tr');

	$('input#_indexes_search').focus();
});
</script>
