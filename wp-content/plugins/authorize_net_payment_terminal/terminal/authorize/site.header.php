<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link rel="stylesheet" type="text/css" href="custom-theme/jquery-ui-1.8.11.custom.css" />
	<script src="js/jquery.tools-1.2.5.min.js"></script>
	<script src="js/jquery-ui-1.8.11.custom.min.js" language="javascript" type="text/javascript"></script>
	<script src="js/ccvalidations.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title?></title>
	<script type="text/javascript">
	<!--
		$(document).ready(function(){
			$(".ccinfo").show();
			$(":radio[name=cctype]").click(function(){
				if($(this).hasClass("isPayPal")){
					 $(".ccinfo").slideUp("fast");
				} else {
					 $(".ccinfo").slideDown("fast");
				}
				resetCCHightlight();
			});
	
			$("input[name=ccn]").bind('paste', function(e) {
					var el = $(this);
					setTimeout(function() {
						var text = $(el).val();
						resetCCHightlight();
						checkNumHighlight(text);
					}, 100);
			});
		});
	-->
	</script>
	<noscript>
		<style type="text/css">
			.noscriptCase { display:none; }
			#accordion .pane { display:block;}
		</style>
	</noscript>
	<?php
	echo $customJS[$terminal];
	?>
</head>
<body>