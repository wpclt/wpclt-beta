<?php
/**
 * @package firmasite
 */
 ?>
<header class="entry-header">
    <h1 class="page-header page-title entry-title">
        <?php the_title(); ?>
		<?php
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		if(1 < $paged) echo "<small> - " . sprintf(__("%s. page", 'firmasite'), $paged) . "</small>";
        if (!empty($post->post_excerpt)){ ?>
            <small><?php the_excerpt(); ?></small>
        <?php } ?>
    </h1>
</header>
<div class="entry-content">      
	<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'firmasite' ) ); ?>
    <?php wp_link_pages( array( 'before' => '<div class="page-links"><ul class="pagination pagination-sm">', 'after' => '</ul></div>' ) ); ?>
</div>
