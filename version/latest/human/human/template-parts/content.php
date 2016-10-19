<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package foodbiz
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


		    <?php
		    	the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'foodbiz' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
		    	) );
		    ?>

		    <?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'foodbiz' ),
				'after'  => '</div>',
			) );
		    ?>
		    
		
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php foodbiz_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
