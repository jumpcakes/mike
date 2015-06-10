<?php

/**
* Template Name: Careers Template
* Description: Used as a page template to show career page contents
*/

// Add our custom loop
add_action( 'genesis_before_content', 'cd_goh_loop' );

function cd_goh_loop() {


	if( have_posts() ) {

		// loop through posts
		while( have_posts() ): the_post(); ?>


		<div class="careers-header" style="background-color:#333; width:100%;  margin:-25px -25px 50px -25px; padding:25px 25px 5px 25px; border-top:1px solid black; text-align:center;">
			
			<h1 style="font-size:48px; line-height:50px; text-transform:none; margin-top:10px; text-shadow:1px 1px 2px black">Get the RESPECT that you deserve.</h1>
			<h2 style="font-size:30px; color:white; margin:20px 0;">Join our highly rated team of well paid technicians today.</h2>
			<div class="view-jobs" style="width:100%;">
				<a href="<?php echo get_permalink(12715); ?>" style="display:block; margin:10px auto -60px; width:350px;">
					<img src="http://mikediamondservices.com/wp-content/themes/associate/images/jointheteam.png"></a>	
				</a>
			</div>
		</div>

		<?php endwhile;
	}

	wp_reset_postdata();

}

genesis();