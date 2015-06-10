<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/sidr/jquery.sidr.min.js" type="text/javascript"></script>



<div class="mobile-header-cta">
1-800-446-6453
</div>
<div id="mobile-header">

	<a class="mobile-logo" href="<?php echo home_url(); ?>">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/mobilelogo.png" alt="Mike Diamond Services">
	</a>
	
	<div class="mobile-nav">
		<a id="simple-menu" href="#sidr" class="nav-toggle"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/nav-icon.png" alt="Mobile Navigation"></a>
		<div id="sidr">
			<?php
				$defaults = array(
					'container_class' => 'sidr-nav',
				);		 
			?>
			<?php wp_nav_menu( $defaults ); ?> 
		</div>
	</div><!--END MOBILE NAV-->


</div>










 
<script>
$(document).ready(function() {
	$('#simple-menu').sidr({
		 side: 'right'
	});
});

</script>