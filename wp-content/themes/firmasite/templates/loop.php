<?php
/**
 * @package firmasite
 */
 ?>
<?php
/* 
 * This is default loop for every post types. So we calling templates/post/loop manually.
 * If you want custom design for your custom post type, lets say "example" named custom post type, just create a loop-example.php file
*/
global $firmasite_settings;
if(!isset($firmasite_settings["loop-style"])) $firmasite_settings["loop-style"] = "loop-list";
switch($firmasite_settings["loop-style"]){
	case "loop-tile":
		global $firmasite_loop_tile, $wp_query, $firmasite_settings, $firmasite_loop_tile_count;
		// we setting those for 1 time only.
		if (!isset($firmasite_loop_tile)) {
			if(is_main_query()) {
				$firmasite_loop_tile["loop_id"] = "main_loop";
				$firmasite_settings["main_loop_id"] = $firmasite_loop_tile["loop_id"];
				$firmasite_settings["main_loop_style"] = "loop-tile";
				$firmasite_settings["main_loop_element"] = "#main_loop li[id^=post-]";
			} else {
				$firmasite_loop_tile["loop_id"] = "firmasite_loop_tile" . $firmasite_loop_tile_count;
			}
			$firmasite_loop_tile["i"] = 0;
			$firmasite_loop_tile["item_left"] = $wp_query->post_count;
		}
 
		$firmasite_loop_tile["i"]++;
		if (1 == $firmasite_loop_tile["i"] ){
			$firmasite_loop_tile_count++;
			if(!(defined('DOING_AJAX') && DOING_AJAX)) {
				?>
				<ul id="<?php echo $firmasite_loop_tile["loop_id"]; ?>" class="loop_tile list-unstyled row">
				<?php 
			}
		}
		
		get_template_part( 'templates/post/loop_tile/loop' , get_post_format() );
		
		$firmasite_loop_tile["item_left"]--;
		if (0 == $firmasite_loop_tile["item_left"]) {
			if(!(defined('DOING_AJAX') && DOING_AJAX)) {
				?>
				<li class="loop-grid-sizer loop_tile_<?php echo $firmasite_loop_tile_count; ?>_grid col-xs-12 col-sm-6 col-md-<?php echo round(12 / $firmasite_settings["loop_tile_row"]); ?>"></li>
				</ul>
				<?php
			}
			wp_enqueue_script( 'jquery-masonry' );
			firmasite_masonry_implement($firmasite_loop_tile["loop_id"]);
			$firmasite_loop_tile = null;
		}		
		break;
	case "loop-excerpt":
		get_template_part( 'templates/post/loop_excerpt/loop' , get_post_format() );
		break;
	case "loop-list":
	default:
		get_template_part( 'templates/post/loop_list/loop' , get_post_format() );
		break;
}