<?php get_header(); ?>
<main>
	<div class="container">
		<h2><?php the_title(); ?></h2>
		<?php the_content(); ?>
		<h2>УПС! Кажется вам попалась несуществующая страница</h2>
		<p>Перейдите на <a href="/">главную</a> и продолжите</p>
	</div>

</main>
<?php get_footer(); ?>
