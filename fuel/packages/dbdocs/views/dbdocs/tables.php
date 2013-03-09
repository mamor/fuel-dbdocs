<h1>Tables</h1>

<form class="form-search">
<input id="_tables_search" type="text" class="span2 search-query" placeholder="search">
</form>

<table id="_tables_table" class="table table-bordered table-striped">
<thead>
<tr>
<th style="width:100%;">Name</th>
</tr>
</thead>

<tbody>

<?php foreach ($table_names as $table_name): ?>

<tr>
<td><a href="table_<?php echo $table_name; ?>.html"><?php echo $table_name; ?></a></td>
</tr>

<?php endforeach; ?>

</tbody>
</table><!--/.table-->

<script>
$(document).ready(function() {
	$('input#_tables_search').quicksearch('table#_tables_table tbody tr');

	$('input#_tables_search').focus();
});
</script>
