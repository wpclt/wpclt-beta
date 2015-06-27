<?php 
	global $firmasite_post_gallery;
	$attachments = $firmasite_post_gallery["attachments"];
	$instance = $firmasite_post_gallery["instance"];
	$selector = $firmasite_post_gallery["selector"];
	$columns = $firmasite_post_gallery["columns"];
	$link = $firmasite_post_gallery["link"];	
	$size = $firmasite_post_gallery["size"];	
	$total_images = count( $attachments );
?>
<div id='<?php echo $selector; ?>' class='carousel slide' data-rel='carousel'>
<?php
  if ($total_images > 1) {
	  $i = 0;
	  $gallery_slide_active = " active";
	  echo '<ol class="carousel-indicators">';
		foreach ( $attachments as $id => $attachment ) {
			echo "<li data-target='#$selector' data-slide-to='$i' class='$gallery_slide_active'></li>";
			$i++;
			$gallery_slide_active = ""; // only first item
		}
	  echo '</ol>';
  }
?>
  <div class="carousel-inner">
	<?php
	$i = 0;
	$gallery_slide_active = " active";
	foreach ( $attachments as $id => $attachment ) {
		//$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
		switch($link){
			case 'file':
				$image =	wp_get_attachment_image($id, $size);
				$image_src =	wp_get_attachment_image($id, "full");
				ob_start();
				?>
                <a href="#" data-toggle="modal" data-target="#<?php echo $post->ID.'-image-'.$id; ?>">
                	<?php echo $image; ?>
                </a>
				<!-- Modal -->
				<div class="modal fade" id="<?php echo $post->ID.'-image-'.$id; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $post->ID.'-image-'.$id; ?>Label" aria-hidden="true">
				  <div class="modal-dialog modal-lg">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php _e( 'Close', 'firmasite' ) ?></span></button>
						<h4 class="modal-title" id="<?php echo $post->ID.'-image-'.$id; ?>Label"><?php echo wptexturize($attachment->post_excerpt); ?></h4>
					  </div>
					  <div class="modal-body">
						<?php echo $image_src; ?>
					  </div>
					</div>
				  </div>
				</div>
                <?php
				$image_output = ob_get_contents(); 
				ob_end_clean();
				break;				
			case 'none':
				$image_output =	wp_get_attachment_image($id, $size);
				break;				
			default:	
			case 'post':
				$image_output =	wp_get_attachment_link($id, $size, true, false);
				break;
		}
		

		echo "<div class='thumbnail item $gallery_slide_active'>";
		$gallery_slide_active = ""; // only first item
		 //echo " <img src='assets/img/bootstrap-mdo-sfmoma-01.jpg' alt=''>";
		   echo $image_output;
			  //echo "<h4>First thumbnail label</h4>";	
				if ( trim($attachment->post_excerpt) ) {
				  echo "<div class='carousel-caption'>";
					if (trim($attachment->post_excerpt))
					echo "
						<h4 class='wp-title-text gallery-title'>
						" . wptexturize($attachment->post_excerpt) . "
						</h4>";						
			     echo "</div>";
				}
		echo "</div>";

	}
	?>

  </div>
  <!-- Carousel nav -->
<?php
if ($total_images > 1){
  echo "<a class='carousel-control left' href='#$selector' data-slide='prev'><span class='icon-prev'></span></a>";
  echo "<a class='carousel-control right' href='#$selector' data-slide='next'><span class='icon-next'></span></a>";
} ?>
</div>
<?php
if ($total_images > 1)
echo firmasite_gallery_count($post->ID,"text-muted",$total_images);
