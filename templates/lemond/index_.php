
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src='media/jui/js/jquery.js'></script>
	<script src='media/jui/js/bootstrap.min.js'></script>
	<script src='media/jui/js/chosen.jquery.min.js'></script>
	<script type="text/javascript">jQuery.noConflict();</script>
	<jdoc:include type="head" />
	<link href="index.php?format=feed&amp;type=rss" rel="alternate" type="application/rss+xml" title="RSS 2.0" />
	<link href="index.php?format=feed&amp;type=atom" rel="alternate" type="application/atom+xml" title="Atom 1.0" />
	<link rel="stylesheet" href="media/system/css/modal.css" type="text/css" />
	<link rel="stylesheet" type="text/css" href="templates/lemond/css/style.css" />
</head>

<body>
	<div id="middle">
		<div id="top-line">
			<div id="top-menu">
				<jdoc:include type="modules" name="top-line" style="XHTML"/>
			</div>
			<div id="top-pos1">
				<jdoc:include type="modules" name="top-pos1" style="XHTML"/>
			</div>
			<div id="top-pos2">
				<jdoc:include type="modules" name="top-pos2" style="XHTML"/>
			</div>
			<div class="cl">
			</div>
		</div>
		
		<div id="header">
			<div id="header-contact">
				<jdoc:include type="modules" name="header-contact" style="XHTML"/>
			</div>
			<div id="header-menu">
				<jdoc:include type="modules" name="header-menu" style="XHTML"/>
			</div>
		</div>
		
		<div id="wrapper">
			<div id="breadcrambs">
				<jdoc:include type="modules" name="breadcrambs" style="XHTML"/>
			</div>
			<div id="two-colomns">
				<div id="left">
					<jdoc:include type="modules" name="left" style="XHTML"/>
				</div>
				<div id="content">
					<jdoc:include type="component" style="XHTML" />
				</div>
				<!--<div id="two-colomns-img">
					<img src="templates/lemond/images/wrapper-bottom.png">
				</div>-->
				<div class="cl">
				</div>
			</div>
		</div>
		
		<div id="some-new">
			<jdoc:include type="modules" name="some-new" style="XHTML"/>
			<div class="cl">
			</div>
		</div>
		
		<div id="some-text">
			<jdoc:include type="modules" name="some-text" style="XHTML"/>
			<div class="cl">
			</div>
		</div>
		
		<div id="footer">
			<div id="footer-1">
				<jdoc:include type="modules" name="footer-1" style="XHTML"/>
			</div>
			<div id="footer-2">
				<jdoc:include type="modules" name="footer-2" style="XHTML"/>
			</div>
			<div id="footer-3">
				<jdoc:include type="modules" name="footer-3" style="XHTML"/>
			</div>
			<div id="footer-4">
				<jdoc:include type="modules" name="footer-4" style="XHTML"/>
			</div>
			<div class="cl">
			</div>
			<div id="footer-5">
				<jdoc:include type="modules" name="footer-5" style="XHTML"/>
			</div>
			<div id="footer-6">
				<jdoc:include type="modules" name="footer-6" style="XHTML"/>
			</div>
		</div>
	
	</div>
</body>
</html>
