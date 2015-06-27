<div class="real_item panel panel-default <?php echo get_post_format(); ?>">
	<div class="thumbnail">
	<?php 
		echo wp_get_attachment_image( $post->ID, 'large');
	 ?>
     </div>
     <?php if ($post->post_excerpt || $post->post_content) { ?>
    <div class="panel-body">
		 <?php if ($post->post_excerpt) { ?>
           <h4><?php echo $post->post_excerpt; ?></h2>
         <?php } ?>
         <?php if ($post->post_content) { ?>
                <?php echo $post->post_content; ?>
         <?php } ?>
     </div>
     <?php } ?>
</div>

