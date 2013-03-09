<h1>Global Information</h1>

<table class="table table-bordered table-striped">
<thead>
<tr>
<th style="width:33%;">Platform</th>
<th style="width:33%;">Database</th>
<th style="width:34%;">Generated at</th>
</tr>
</thead>

<tbody>
<tr>
<td><?php echo $information['platform']; ?></td>
<td><?php echo $information['database']; ?></td>
<td><?php echo date('r'); ?></td>
</tr>
</tbody>

</table><!--/.table-->

<?php echo $tables_count; ?> tables,
<?php echo $indexes_count; ?> indexes,
<?php echo $views_count; ?> views.
