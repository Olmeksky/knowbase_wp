<?php
global $wp_query;
$args = array_merge( $wp_query->query, array( 'posts_per_page' => 100 ) );
query_posts( $args );
$term = get_term_by( 'slug', get_query_var( 'term' ), 'section' );

get_header();
get_sidebar( 'sections' );
while ( have_posts() ) : the_post();
	echo kb_article_permalink( $post->ID, $term->term_id );
endwhile;
get_footer();
?>