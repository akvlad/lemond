<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo '/templates/lemond/images/fav.png' ?>" />
	<script src='media/jui/js/jquery.js'></script>
	<script src='media/jui/js/bootstrap.min.js'></script>
	<script src='media/jui/js/chosen.jquery.min.js'></script>
	<script type="text/javascript">jQuery.noConflict();</script>
	<jdoc:include type="head" />
	<link href="index.php?format=feed&amp;type=rss" rel="alternate" type="application/rss+xml" title="RSS 2.0" />
	<link href="index.php?format=feed&amp;type=atom" rel="alternate" type="application/atom+xml" title="Atom 1.0" />
	<link rel="stylesheet" href="media/system/css/modal.css" type="text/css" />
	<link rel="stylesheet" type="text/css" href="templates/lemond/css/style.css" />
	<link rel="stylesheet" type="text/css" href="templates/lemond/css/style-product-cart.css" />
</head>

<body>
	<div id="top">
		<div id="top-line">
			<div id="top-menu">
				<jdoc:include type="modules" name="top-line" style="XHTML"/>
			</div>
            <div id="top-pos2">
                <jdoc:include type="modules" name="top-pos2" style="XHTML"/>
            </div>
			<div id="top-pos1">
				<jdoc:include type="modules" name="top-pos1" style="XHTML"/>
			</div>
            <div id="cart-pos">
                <jdoc:include type="modules" name="cart-pos" style="XHTML"/>
            </div>
			<div class="cl">
			</div>
		</div>
	</div>
	<div id="head">
		<div id="header">
                        <a id="clickable_logo" href="/"></a>
			<div id="header-contact">
				<jdoc:include type="modules" name="header-contact" style="XHTML"/>
			</div>
			<div id="header-menu">
				<jdoc:include type="modules" name="header-menu" style="XHTML"/>
			</div>
			<div id="header-cart">
				<jdoc:include type="modules" name="header-cart" style="XHTML"/>
			</div>

		</div>	
	</div>


	<div id="middle">

		

		
		<div id="wrapper">
				<div id="content">
					<jdoc:include type="component" style="XHTML" />
                    <div class="cl"></div>
                    <jdoc:include type="modules" name="some-catalogue" style="XHTML" />
					<jdoc:include type="modules" name="up" style="XHTML" />

				</div>
				<div class="cl">
				</div>
                    
		</div>
	</div>
		
		<div id="some-text">
			<jdoc:include type="modules" name="some-text" style="XHTML"/>
			<div class="cl">
			</div>
		</div>
		
		
	<div id="footer-all">
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
			<div id="footer-5">
				<jdoc:include type="modules" name="footer-5" style="XHTML"/>
			</div>
			<div id="footer-6">
				<jdoc:include type="modules" name="footer-6" style="XHTML"/>
			</div>
		</div>
	</div>
	
	</div>
    <div id="formlogin">
        <jdoc:include type="modules" name="login-form" style="XHTML"/>
    </div>
</body>
</html>

