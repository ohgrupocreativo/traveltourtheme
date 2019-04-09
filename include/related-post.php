<?php 
    //basado en https://decodecms.com/posts-relacionados-sin-plugins-en-wordpress/
    if ( !is_singular('post') ) return $content;	
	
	$cad			= "";

 	$template_li = '<div class="gdlr-core-item-list  tourmaster-column-20 tourmaster-item-pdlr" style="width: 24.3333%; padding-left: 1px; padding-right: 12px;">
 					<div class="tourmaster-tour-grid  tourmaster-price-bottom-title">
               			<div class="tourmaster-tour-thumbnail tourmaster-media-image">
               				<a href="{url}">
               					<img src="{thumb}" alt="" width="180" height="150">
               				</a>
               			</div>
               			<div class="tourmaster-tour-content-wrap gdlr-core-skin-e-background">
                  			<h3 class="tourmaster-tour-title gdlr-core-skin-title articulo-relacionado">
                  				<a href="{url}">{title}</a>
                  			</h3>  
               			</div>
            		</div>
            	</div>';

	$template_rel ='<div class="tourmaster-single-related-tour tourmaster-tour-item tourmaster-style-grid" style="padding-bottom: 5px; margin-bottom:5px;">
   					<div class="tourmaster-single-related-tour-container tourmaster-container" style="padding-left:0">
      					<h3 class="tourmaster-single-related-tour-title tourmaster-item-pdlr articulo-relacionado" style="padding-left:0">'.esc_html__( 'Artículos Relacionados', 'traveltour' ).'</h3>
      					<div class="tourmaster-tour-item-holder clearfix ">
         					
         						{list}
                  			</div>
      					</div>
   					</div>';

   // $terms = get_the_terms( get_the_ID(), 'category');
   	$terms = get_the_terms( get_the_ID(), 'post_tag');
    $categ = array();
    
    if ( $terms )
    {
    	foreach ($terms as $term) 
    	{
    		$categ[] = $term->term_id;
    	}
    }
    else{
    	return $content;
    }

    /*$loop	= new WP_QUERY(array(
    				'category__in'		=> $categ,
    				'posts_per_page'	=> 4,
    				'post__not_in'		=>array(get_the_ID()),
    				'orderby'			=>'rand'
    				));*/


     $loop   = new WP_QUERY(array(
                    'tag__in'           => $categ,
                    'posts_per_page'    => 4,
                    'post__not_in'      =>array(get_the_ID()),
                    'orderby'           =>'rand'
                    ));

    if ( $loop->have_posts() )
    {

    	while ( $loop->have_posts() )
    	{
    		$loop->the_post();

			$id_post = get_the_ID(); 
			$thumb_id = get_post_thumbnail_id($id_post); 
			$thumb_url_array = wp_get_attachment_image_src($thumb_id,array(150,150));
			$thumb_url = $thumb_url_array[0];



    		$search	 = Array('{url}','{thumb}','{title}');
	  		$replace = Array(get_permalink(),$thumb_url,get_the_title());
    	
    		$cad .= str_replace($search,$replace, $template_li);
    	}

    	if ( $cad ) 
    	{
		  	echo  str_replace('{list}', $cad, $template_rel);
    	}

    }
   	wp_reset_query();

    //return $content;