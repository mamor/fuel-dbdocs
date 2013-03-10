<h1>Tables</h1>

<form class="form-search">
<input id="_tables_search" type="text" class="span2 search-query" placeholder="Search">
</form>

<table id="_tables_table" class="table table-bordered table-striped">
<thead>
<tr>
<th style="width:5%;">No</th>
<th style="width:95%;">Name</th>
</tr>
</thead>

<tbody>

<?php foreach ($table_names as $table_name): ?>

<tr>
<td><?php echo isset($i) ? ++$i : $i = 1; ?></td>
<td><a href="table_<?php echo $table_name; ?>.html"><?php echo $table_name; ?></a></td>
</tr>

<?php endforeach; ?>

</tbody>
</table><!--/.table-->

<script>
$(document).ready(function() {
	$('#_tables_search').quicksearch('#_tables_table tbody tr');

	$('#_tables_search').focus();
});
</script>
