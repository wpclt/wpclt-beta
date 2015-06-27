<?php
global $firmasite_showcase, $post, $firmasite_settings;
	$cover = "";
	$large_image_url = "";

	$large_image_url = wp_get_attachment_image_src( $post->ID, 'large');
	if ($large_image_url[1] > (get_option( 'large_size_w' ) / 1.3 )) { 
		$cover = "background-size:cover;";
	}
	?>  
     <div class="firmasite-showcase-content jumbotron hero-background clearfix" style="background-image:url(<?php echo $large_image_url[0]; ?>); background-repeat:no-repeat; background-position:center right 15%; <?php  echo $cover;?>">
        <div class="caption">
			 <?php if ($post->post_excerpt) { ?>
       			<h2 class="hero-title"><?php echo $post->post_excerpt; ?></h2>
             <?php } ?>
             <?php if ($post->post_content) { ?>
             <div class="hero-content hidden-xs">
                <p>
                    <?php echo $post->post_content; ?>
                </p>
             </div>
             <?php } ?>
        </div>
    </div> 
<?php
