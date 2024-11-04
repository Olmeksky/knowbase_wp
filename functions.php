<?php
/*
 * This is the child theme for Understrap theme.
 */
add_action( 'wp_enqueue_scripts', 'ustrb5_enqueue_styles' );
function ustrb5_enqueue_styles() {
	wp_enqueue_style( 'main', get_stylesheet_directory_uri() . '/style.css' );
	wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css' );
}

add_action( 'wp_enqueue_scripts', 'my_scripts_method');
function my_scripts_method() {
	// отменяем зарегистрированный jQuery
	// вместо "jquery-core", можно вписать "jquery", тогда будет отменен еще и jquery-migrate
	wp_deregister_script( 'jquery-core' );
	wp_register_script( 'jquery-core', get_stylesheet_directory_uri() . '/assets/js/jquery-3.7.1.min.js');
	wp_enqueue_script( 'jquery' );
	
	// подключим библиотеку для карусели бутстрап
	wp_register_script( 'bbundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js');
	wp_enqueue_script('bbundle');
	
	// подключаем свои скрипты
	wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/assets/js/main.js');
	
}

/* Дополнительные сущности (кастомные поля) */

// Регистрируеме новую тип для слайдера.
function register_events() {
	// Для Типа записи Слайдер
	register_post_type( 'sliders', [
		'labels'	=> [
			'name'					=> 'Hero Slider',
			'singular_name'			=> 'Hero Sliders',
			'name_admin_bar'		=> 'Hero Sliders',
			'menu_name'				=> 'Hero Sliders',
			'add_new'				=> 'Add Hero Slider',
			'add_new_item'			=> 'Add Hero Slider',
			'edit_item'				=> 'Edit Hero Slider',
			'new_item'				=> 'New Hero Slider',
			'view_item'				=> 'View Hero Slider',
			'search_items'			=> 'Search Hero Slider',
			'not_found'				=> 'Hero Slider Not Found',
			'not_found_in_trash'	=> 'Hero Slider Not Found In Trash',
			'featured_image'		=> 'Hero Slider Photo',
			'set_featured_image'	=> 'Set Hero Slider Photo',
			'title_placeholder'		=> 'Hero Slider Title',
			'first_menu_name'		=> 'Hero Slider List',
		],
		'public'				=> true,
		'publicly_queryable'	=> true,
		'rewrite'				=> array( 'slug' => 'sliders', 'hierarchical' => false, 'with_front' => false ),
		'capability_type'		=> 'post',
		'has_archive'			=> true,
		'hierarchical'			=> false,
		'supports'				=> array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes', 'post-formats' ),
		'menu_position'			=> 7,
		'menu_icon'				=> 'dashicons-slides',
		'posts_per_page'		=> 17,
		'show_in_rest'			=> true,
		'show_in_nav_menus'		=> true,
		'show_ui'				=> true,
		'show_in_menu'			=> true,
	] );
}
add_action( 'init', 'register_events' );



/* Дополнительные сущности (кастомные поля) */





/* Ваш дополнительный код */
if (! function_exists('sidra_setup')) {
	function sidra_setup () {
		// Добавим возможность загрузки логотипа, через настройки сайта
		add_theme_support( 'custom-logo', [
			'height'		=> 70,
			'width'			=> 190,
			'flex-width'	=> true,
			'flex-height'	=> false,
			'header-text'	=> 'Сидраген',
			'unlink-homepage-logo' => false,
		] );
		// Добавим динамический Тайтл
		add_theme_support('title-tag');
	}
	add_action('after_setup_theme','sidra_setup');
}

// функция вывода короккого содержимого
function the_truncated_post($symbol_amount) {
	$filtered = strip_tags( preg_replace('@<style[^>]*?>.*?</style>@si', '', preg_replace('@<script[^>]*?>.*?</script>@si', '', apply_filters('the_content', get_the_content()))) );
	echo substr($filtered, 0, strrpos(substr($filtered, 0, $symbol_amount), ' ')) . '...';
}




// Регистрируеме новую таксономию и тип поста.
function register_kb() {
	register_taxonomy( 'section', array( 'knowledgebase' ),
		array(
			'labels' => array(
				'name' => 'Sections',
				'menu_name' => 'Sections',
				'singular_name' => 'Section',
				'all_items' => 'All Sections'
			),
			'public' => true,
			'hierarchical' => true,
			'show_ui' => true,
			'rewrite' => array( 'slug' => 'knowledgebase-section', 'hierarchical' => true, 'with_front' => false ),
		)
	);
    register_post_type( 'knowledgebase',
        array(
            'labels' => array(
                'name' => 'База знаний',
                'menu_name' => 'База знаний',
                'singular_name' => 'Статья',
                'all_items' => 'Все статьи'
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'menu_position ' => 4,
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'post-formats', 'revisions' ),
			'menu_icon'				=> 'dashicons-excerpt-view',
			'template_item'			=> 'single-knowledgebase.php',
			'posts_per_page'		=> 6,
            'hierarchical' => false,
            'taxonomies' => array( 'section' ),
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'knowledgebase', 'hierarchical' => true, 'with_front' => false )
        )
    );
}
add_action( 'init', 'register_kb' );
 
function kb_rewrite_rules( $wp_rewrite ) {
    $new_rules = array( 'knowledgebase/(.*)/(.*)' => 'index.php?post_type=knowledgebase&section=' . $wp_rewrite->preg_index( 1 ) . '&knowledgebase=' . $wp_rewrite->preg_index( 2 ) );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action( 'generate_rewrite_rules', 'kb_rewrite_rules' );

// показать разделы, к которым принадлежит статья
function kb_columns( $defaults ) {
    $defaults['section'] = 'Sections';
    return $defaults;
}
add_filter( 'manage_knowledgebase_posts_columns', 'kb_columns' );
 
function kb_custom_column( $column_name, $post_id ) {
    $taxonomy = $column_name;
    $post_type = get_post_type( $post_id );
    $terms = get_the_terms( $post_id, $taxonomy );
    if ( !empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $post_terms[] = "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " . esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . '</a>';
        }
        echo join( ', ', $post_terms );
    }
    else echo '<i>No terms.</i>';
}
add_action( 'manage_knowledgebase_posts_custom_column', 'kb_custom_column', 10, 2 );

//
function kb_sections( $sections = array(), $active_section = null ) {
    $taxonomy = 'section';
    $link_class = '';
    if ( empty( $sections ) ) {
        $link_class = 'root';
        $sections = get_terms( $taxonomy, array( 'parent' => 0, 'hide_empty' => 0 ) );
        $active_section = kb_active_section();
        echo '<ul id="kb-sections" class="unstyled">';
    }
    if ( empty( $active_section ) ) {
        $active_section = '';
    }
    foreach ( $sections as $section ) {
        $toggle = '';
        $section_children = get_terms( $taxonomy, array( 'parent' => $section->term_id, 'hide_empty' => 0 ) );
        if ( !empty( $section_children ) && $link_class != 'root' ) {
            $toggle = '<i class="toggle"></i>';
        }
        echo '<li class="' . ( $section->term_id == $active_section ? 'active' : '' ) . '">';
        echo '<a  href="' . get_term_link( $section, $taxonomy ) . '" class="' . $link_class . '" rel="' . $section->slug . '">' . $toggle . $section->name . '</a>';
 
        if ( !empty( $section_children ) ) {
            echo '<ul id="' . $section->slug . '" class="children">';
            kb_sections( $section_children, $active_section );
        }
        echo "</li>";
    }
    echo "</ul>";
}


function kb_active_section() {
    $taxonomy = 'section';
    $current_section = '';
    if ( is_single() ) {
        $sections = explode( '/', get_query_var( $taxonomy ) );
        $section_slug = end( $sections );
        if ( $section_slug != '' ) {
            $term = get_term_by( 'slug', $section_slug, $taxonomy );
        } else {
            $terms = wp_get_post_terms( get_the_ID(), $taxonomy );
            $term = $terms[0];
        }
        $current_section = $term->term_id;
    } else {
        $term = get_term_by( 'slug', get_query_var( $taxonomy ), get_query_var( 'taxonomy' ) );
        $current_section = $term->term_id;
    }
    return $current_section;
}


// Отображение статей
function kb_article_permalink( $article_id, $section_id ) {
	$taxonomy = 'section';
	$article = get_post( $article_id );
	$section = get_term( $section_id, $taxonomy );
	$section_ancestors = get_ancestors( $section->term_id, $taxonomy );
	krsort( $section_ancestors );
	$permalink = '<a href="/knowledgebase/';
	foreach ($section_ancestors as $ancestor):
		$section_ancestor = get_term( $ancestor, $taxonomy );
		$permalink.= $section_ancestor->slug . '/';
	endforeach;
	$permalink.= $section->slug . '/' . $article->post_name . '/" >' . $article->post_title . '</a>';
	return $permalink;
}


?>





