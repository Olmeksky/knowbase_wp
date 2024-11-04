<?php
get_header();
get_sidebar( 'sections' );
while ( have_posts() ) : the_post();
	$terms = wp_get_post_terms( $post->ID, 'section' );
	$term = $terms[0];
	echo kb_article_permalink( $post->ID, $term->term_id );
endwhile;
get_footer();
?>