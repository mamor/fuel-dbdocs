<h1>Views</h1>

<form class="form-search">
<input id="_views_search" type="text" class="span2 search-query" placeholder="Search">
</form>

<table id="_views_table" class="table table-bordered table-striped">
<thead>
<tr>
<th style="width:5%;">No</th>
<th style="width:95%;">Name</th>
</tr>
</thead>

<tbody>

<?php foreach ($view_names as $view_name): ?>

<tr>
<td><?php echo isset($i) ? ++$i : $i = 1; ?></td>
<td><a href="view_<?php echo $view_name; ?>.html"><?php echo $view_name; ?></a></td>
</tr>

<?php endforeach; ?>

</tbody>
</table><!--/.table-->

<script>
$(document).ready(function() {
	$('#_views_search').quicksearch('#_views_table tbody tr');

	$('#_views_search').focus();
});
</script>
