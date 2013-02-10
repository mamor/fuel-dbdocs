<h1>Generate</h1>
<?php echo Form::open(); ?>
<?php echo Form::select('platform', isset($data['platform']) ? $data['platform'] : null, array(
	'' => 'Select platform',
	'mysql' => 'MySQL',
	'pgsql' => 'PostgreSQL',
	'sqlite' => 'SQLite',
)); ?>
<?php echo Form::close(); ?>

<div id="_form">
<?php isset($data['form']) ? print($data['form']) : print(''); ?>
</div>

<script>
$("#form_platform").change(function() {
	$.get("<?php echo Uri::create('index/form/'); ?>" + $("#form_platform").val(), function(html) {
		$("#_form").html(html);
	});
});
</script>
