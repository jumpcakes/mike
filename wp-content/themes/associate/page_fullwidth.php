<?php
/*
Template Name: Full Width
*/

 //* Force full width content layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

genesis();
