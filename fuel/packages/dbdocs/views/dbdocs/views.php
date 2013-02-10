<h1>Views</h1>

<table class="table table-bordered table-striped">
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
