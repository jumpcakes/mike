<!DOCTYPE html>
<html>
<head>
	<title><?php the_title(); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
		    
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
   <!-- <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">-->
    
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'style/style.css', dirname(__FILE__) ); ?>">

	<link rel="Shortcut Icon" href="http://mikediamondservices.com/wp-content/themes/associate/images/favicon.ico" type="image/x-icon" />
</head>
<body>
	
<?php if ( have_posts() ) { while ( have_posts() ) { the_post(); ?>

<div class="top-section">
	<div class="container">
    	<div class="row"> 
    	
                <div class="header">                                
                		<span class="phone"> 
                            <a href="<?php echo plugins_url( 'coupons.html', dirname(__FILE__) ); ?>"  class="coupon-cta"><i class="fa fa-usd "></i> Click for Coupons!</a>
                            <br class="line-break">
                            <a href="tel:+8884037144">888-403-7144</a>
                        </span>
             
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">                	
                            <img class="logo" src="<?php echo plugins_url( 'images/logo2.png', dirname(__FILE__) ); ?>">
                        </a>
                        <h1><?php the_title(); ?></h1>
                </div> <!--end header-->

        
	<div class="top-content">
		<div class="spacer">	
			<div class="col-sx-12 col-md-8">
                        	
            		<div class="letter-wrap">			
                    	 <?php if (get_field('top_content')) { the_field('top_content'); };?>
                    </div>
                             
                           <?php if (get_field('tab_section')) { the_field('tab_section'); };?> 
                           
                <div class="panel-group" id="accordion">
                        
                      <div class="panel">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#accordionOne"> <i class="fa fa-plus"></i>
                                 <?php if (get_field('checklist_1_title')) { the_field('checklist_1_title'); };?></a>
                              </h4>
                            </div>  
                            <div id="accordionOne" class="panel-collapse collapse "> <!--add "in" class to refresh open-->
                                <div class="panel-body">
                                <?php if (get_field('checklist_1_content')) { the_field('checklist_1_content'); };?>
                                </div>
                            </div>
                        </div> <!--end panel 1-->
                  
                      <div class="panel ">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#accordionTwo"> <i class="fa fa-plus"></i>
                                <?php if (get_field('checklist_2_title')) { the_field('checklist_2_title'); };?></a>
                              </h4>
                            </div>
                            <div id="accordionTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                <?php if (get_field('checklist_2_content')) { the_field('checklist_2_content'); };?>
                                </div>
                            </div>
                      </div><!--end panel 2-->
                                     
                      <div class="panel ">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion"  href="#accordionThree"> <i class="fa fa-plus"></i>
                                <?php if (get_field('checklist_3_title')) { the_field('checklist_3_title'); };?>  </a>
                              </h4>
                            </div> 
                            <div id="accordionThree" class="panel-collapse collapse">
                                <div class="panel-body">
                                <?php if (get_field('checklist_3_content')) { the_field('checklist_3_content'); };?>
                                </div>
                            </div>
                      </div> <!--end panel 3-->
                       <div class="panel ">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#accordionFour"> <i class="fa fa-plus"></i>
                                <?php if (get_field('checklist_4_title')) { the_field('checklist_4_title'); };?>  </a>
                              </h4>
                            </div>  
                            <div id="accordionFour" class="panel-collapse collapse ">
                                <div class="panel-body">
                                <?php if (get_field('checklist_4_content')) { the_field('checklist_4_content'); };?>
                                </div>
                            </div>
                        </div> <!--end panel 4-->
                  
                      <div class="panel ">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#accordionFive"> <i class="fa fa-plus"></i>
                                <?php if (get_field('checklist_5_title')) { the_field('checklist_5_title'); };?></a>
                              </h4>
                            </div>
                            <div id="accordionFive" class="panel-collapse collapse">
                                <div class="panel-body">
                                <?php if (get_field('checklist_5_content')) { the_field('checklist_5_content'); };?>
                                </div>
                            </div>
                      </div><!--end panel 5-->
   
           		</div> <!--end accordion id-->
            
            	<button class="print_button" onClick="window.location.href='http://mikediamondservices.com/wp-content/plugins/gruen-landing-pages/images/ConservationChecklist-MikeDiamond.pdf'" style="max-width:200px;">download checklist</button>
                
            	<p class="checklist-disclaimer"><?php if (get_field('disclaimer_text')) { the_field('disclaimer_text'); };?> </p>
           
            </div>	<!--end left column-->
                    
                    
            <div class="col-sx-12 col-md-4" >
            		<a href="http://www.reputation.com/reviews/mike-diamond-services-culver-city" target="_blank"><img class="850reviews" style="width:100%; margin-bottom:20px;" src="<?php echo plugins_url( 'images/850reviews.jpg', dirname(__FILE__) ); ?>"></a>
                        		
                  <div class="form-wrap" >        
                       <?php if (get_field('gravity_form_id')) { the_field('gravity_form_id'); };?>
                  </div>
                                     
                
       
       
             </div><!--end right column-->
                        
         </div>  <!--end spacer-->	
     </div> <!--end top-content-->
          

<div class="mid-section">
	<div class="container spacer">
   	<div class="row "> 
    




 	</div> <!--end row-->
	</div> <!--end container-->
</div> <!--end mid-section-->
 
 
 
 
 
   <div class="coupon-section" id="coupon-section">
	  <div class="container spacer">
    	 <div class="row"> 
   
			<div class="col-sx-12 col-md-8" >
            	<div class="coupon-wrap" >  
           			 <h2 class="coupon-header">June Coupon Offers</h2> 
            		 <a onClick="ga('send', 'event', 'droughtcoupon', 'click');" target="_blank" href="<?php echo plugins_url( 'coupons.html', dirname(__FILE__) ); ?>" ><img class="coupon-img" src="<?php if (get_field('coupons')) { the_field('coupons'); };?>  "></a>
            	   
            		 <button class="print_button" onClick="window.location.href='<?php echo plugins_url( 'coupons.html', dirname(__FILE__) ); ?>'" style="max-width:150px;">print</button>
             
             	</div><!-- end coupon wrap-->
       		 </div>   <!--end column--> 		
 
    
            <div class="col-sx-12 col-md-4" >
                <div class="blog-wrap" >
                     <h3>Water Conservation Resource Center</h3>   
                                
                     <?php 
        
        				$posts = get_field('resource_center');
        
        				if( $posts ): ?>
            				<ul>
           				 <?php foreach( $posts as $post): // variable must be called $post (IMPORTANT) ?>
                		 <?php setup_postdata($post); ?>
              					<li>
                                    <a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a>
                                    <span class="blog-date"><?php the_date(); ?></span>
                                </li>
            			 <?php endforeach; ?>
            				
           				 <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
        			 <?php endif; ?>                   
                                                                
                
                		<?php

					// check if the repeater field has rows of data
					if( have_rows('resource_center_external_links') ):
					
						// loop through the rows of data
						while ( have_rows('resource_center_external_links') ) : the_row(); ?>
                                 <li>
                                    <?php the_sub_field('url_path'); ?>
                                </li>
                    	<?php endwhile;
							else :
								// no rows found
							endif; ?>
                    
                		   </ul>
                
                 </div><!--end blog wrap-->
                 
                 
                   <div class="truck-wrap">
       					<img class="truck" src="<?php echo plugins_url( 'images/mike-diamond-truck.jpg', dirname(__FILE__) ); ?>">
       			   </div>
                   
                   
            </div> <!--end col-->
         

    	  </div>     <!--end row-->       
	   </div> <!--end container-->
	</div> <!--end coupon-section-->
    
    
     </div>     <!--end row-->       
	</div> <!--end container-->
</div> <!--end top-section-->
    

<footer>
    
	<img class="footer-logo" src="<?php echo plugins_url( 'images/logo.png', dirname(__FILE__) ); ?>">
        
 </footer>


<?php }} ?>

<script type="text/javascript">
 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

 ga('create', 'UA-39252100-3', 'auto');
 ga('send', 'pageview');

</script>


<!--<script type="text/javascript">
	  (function(d) {
		var config = {
		  kitId: 'toh0xpr',
		  scriptTimeout: 3000
		},
		h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='//use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
	  })(document);
	</script>-->
    
    
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>


</body>

</html>