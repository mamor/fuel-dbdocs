<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>DBDocs</title>

<link type="text/css" rel="stylesheet" href="assets/css/bootstrap.css" />

<style type="text/css">
	body {
		padding-top: 60px;
		padding-bottom: 40px;
	}
	.hashlink {
		background: #ffffbb !important;
	}
</style>

<link type="text/css" rel="stylesheet" href="assets/css/bootstrap-responsive.min.css" />

<?php if (($webfont = Config::get('dbdocs.webfont', false)) !== false): ?>

<link href="http://fonts.googleapis.com/css?family=<?php echo urlencode($webfont); ?>" rel="stylesheet" type="text/css">
<style>
body { font-family: "<?php echo $webfont; ?>", sans-serif; }
</style>

<?php endif; ?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	$("._extra").tooltip({
		"html" : true,
		"title" : "PK : Primary key<br />UI : Unique<br />I : Index<br />AI : Auto increment<br />FK : Foreign key<br />UN : Unsigned",
		"placement" : "right"
	});
	$("._foreign_key").tooltip({
		"placement" : "right"
	});
});
</script>

</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top">
<div class="navbar-inner">
<div class="container-fluid">

<a class="brand" href="index.html">DBDocs</a>
<ul class="nav">
<li<?php $active === 'tables' and print(' class="active"'); ?>><a href="tables.html">Tables</a></li>
<li<?php $active === 'indexes' and print(' class="active"'); ?>><a href="indexes.html">Indexes</a></li>
<li<?php $active === 'views' and print(' class="active"'); ?>><a href="views.html">Views</a></li>
</ul>

<ul class="nav pull-right">
<li><a href="https://github.com/mp-php/fuel-dbdocs/issues" target="_blank">Issues</a></li>
</ul>

</div><!--/container-fluid-->
</div><!--/navbar-inner-->
</div><!--/navbar-->

<div class="container">

<?php echo $content; ?>

<hr />

<footer>
<p class="pull-left">
<?php echo Html::anchor('https://github.com/mp-php/fuel-dbdocs', 'DBDocs', array('target' => '_blank')); ?>
 developed by <?php echo Html::anchor('http://madroom-project.blogspot.jp/', 'madroom project', array('target' => '_blank')); ?>
</p>

<p class="pull-right">
Generated by <?php echo Html::anchor('http://fuelphp.com/', 'FuelPHP', array('target' => '_blank')); ?> <?php echo e(Fuel::VERSION); ?>
 and using <?php echo Html::anchor('http://twitter.github.com/bootstrap/', 'Twitter Bootstrap', array('target' => '_blank')); ?>
</p>
</footer>

</div><!--/.container-->

</body>
</html>
