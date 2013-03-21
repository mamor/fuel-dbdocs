<h1><a href="table_<?php echo $table_name; ?>.html"><?php echo $table_name; ?></a></h1>

<form class="form-search">
<input id="_columns_search" type="text" class="span2 search-query" placeholder="Search">
</form>

<table id="_columns_table" class="table table-bordered table-striped">
<thead>
<tr>
<th style="width:5%;">No</th>
<th style="width:10%;">Name</th>
<th style="width:10%;">Type</th>
<th style="width:5%;">Len</th>
<th style="width:5%;">Null</th>
<th style="width:5%;">Def</th>
<th style="width:15%;">Extra <span class="_extra"><i class="icon-question-sign"></i></span></th>
<th style="width:45%;">Comment</th>
</tr>
</thead>
<tbody>
<?php foreach ($columns as $column_name => $column_infos): ?>
<tr class="_column_<?php echo $column_name; ?>">
<td><a href="#_column_<?php echo $column_name; ?>"><?php echo isset($i) ? ++$i : $i = 1; ?></a></td>
<?php if(empty($column_infos['foreign_key']['table_name'])): ?>
<td><?php echo $column_name; ?></td>
<?php else: ?>
<td>
<a href="table_<?php echo $column_infos['foreign_key']['table_name']; ?>.html#_column_<?php echo $column_infos['foreign_key']['column_name']; ?>"><?php echo $column_name; ?></a>
<span class="_foreign_key" title="<?php echo $column_infos['foreign_key']['table_name'].'.'.$column_infos['foreign_key']['column_name']; ?>"><i class="icon-question-sign"></i></span>
</td>
<?php endif; ?>
<td><?php echo $column_infos['type']; ?></td>
<td><?php echo $column_infos['length']; ?></td>
<td><?php echo $column_infos['null'] ? 'Yes' : 'No' ; ?></td>
<td><?php echo $column_infos['default']; ?></td>
<td><span class="label label-info"><?php echo implode('</span> <span class="label label-info">', $column_infos['extras']); ?></span></td>
<td><?php echo $column_infos['comment']; ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table><!--/.table-->

<hr />

<h2>Indexes</h2>

<form class="form-search">
<input id="_indexes_search" type="text" class="span2 search-query" placeholder="Search">
</form>

<table id="_indexes_table" class="table table-bordered table-striped">
<thead>
<tr>
<th style="width:5%;">No</th>
<th style="width:35%;">Name</th>
<th style="width:30%;">Column</th>
<th style="width:30%;">Extra <span class="_extra"><i class="icon-question-sign"></i></span></th>
</tr>
</thead>
<tbody>
<?php foreach ($indexes as $index_name => $index_infos): ?>
<?php foreach ($index_infos['columns'] as $column_name): ?>
<tr>
<td><?php echo isset($j) ? ++$j : $j = 1; ?></td>
<td><a class="_column_<?php echo $column_name; ?>" href="#_column_<?php echo $column_name; ?>"><?php echo $index_name; ?></a></td>
<td><?php echo $column_name; ?></td>
<td><span class="label label-info"><?php echo implode('</span> <span class="label label-info">', $index_infos['extras']); ?></span></td>
</tr>
<?php endforeach; ?>
<?php endforeach; ?>
</tbody>
</table><!--/.table-->

<script>
(function($){
	$.hashlink = function(targets) {
		targets.each(function(index) {
			if (index == 0)
			{
				$("html, body").animate({ scrollTop: ($(this).offset().top + -75) + "px"});
			}

			$(this).addClass("hashlink");
			$(this).css("opacity", "0.5");
			$(this).fadeTo("slow", 1.0);
		});
	}
})(jQuery);

$(document).ready(function() {
	$("a[href^='#']").click(function(){
		window.location.hash = $(this).attr("href");

		$(".hashlink").each(function() {
			$(this).removeClass("hashlink");
		});

		$.hashlink($($(this).attr("href").replace(/#/, ".")).closest("tr").children("td"));
	});

	if (hash = window.location.hash)
	{
		$.hashlink($(hash.replace(/#/, ".")).closest("tr").children("td"));
	}

	$('#_columns_search').quicksearch('#_columns_table tbody tr');
	$('#_indexes_search').quicksearch('#_indexes_table tbody tr');

	$('#_columns_search').focus();
});
</script>
