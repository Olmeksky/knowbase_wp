<?php
get_header();
while ( have_posts() ) : the_post();
	get_template_part( 'content', 'knowledgebase' );
endwhile;
get_footer();
?>