<h1>Views</h1>

<form class="form-search">
<input id="_views_search" type="text" class="span2 search-query" placeholder="Search">
</form>

<table id="_views_table" class="table table-bordered table-striped">
<thead>
<tr>
<th style="width:100%;">Name</th>
</tr>
</thead>

<tbody>

<?php foreach ($view_names as $view_name): ?>

<tr>
<td><a href="view_<?php echo $view_name; ?>.html"><?php echo $view_name; ?></a></td>
</tr>

<?php endforeach; ?>

</tbody>
</table><!--/.table-->

<script>
$(document).ready(function() {
	$('input#_views_search').quicksearch('table#_views_table tbody tr');

	$('input#_views_search').focus();
});
</script>
