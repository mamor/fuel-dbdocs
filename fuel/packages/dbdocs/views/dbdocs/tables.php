<h1>Tables</h1>

<table class="table table-bordered table-striped">
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
