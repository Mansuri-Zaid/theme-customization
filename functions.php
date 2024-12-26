<?php
// Register Custom Post Type for Projects
function register_project_post_type()
{
    $args = array(
        'labels' => array(
            'name'               => __('Projects', 'textdomain'),
            'singular_name'      => __('Project', 'textdomain'),
            'add_new'            => __('Add New', 'textdomain'),
            'add_new_item'       => __('Add New Project', 'textdomain'),
            'edit_item'          => __('Edit Project', 'textdomain'),
            'new_item'           => __('New Project', 'textdomain'),
            'view_item'          => __('View Project', 'textdomain'),
            'all_items'          => __('All Projects', 'textdomain'),
            'search_items'       => __('Search Projects', 'textdomain'),
            'not_found'          => __('No projects found.', 'textdomain'),
            'not_found_in_trash' => __('No projects found in Trash.', 'textdomain'),
            'menu_name'          => __('Projects', 'textdomain'),
        ),
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => array('slug' => 'projects'),
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-portfolio', // Using the portfolio Dashicon
        'show_in_rest'        => true, // Enable Gutenberg editor support
    );
    register_post_type('project', $args);
}
add_action('init', 'register_project_post_type');


function custom_project_template($template)
{
    // Check if the current post is of type 'project'
    if (is_singular('project')) {

        $custom_template = locate_template('cpt/project/single-project.php');

        if ($custom_template) {
            return $custom_template;
        }
    }
    return $template;
}

add_filter('template_include', 'custom_project_template');


// Register Custom Taxonomy for Project Categories
function register_project_taxonomy()
{
    $args = array(
        'hierarchical'        => true,
        'labels'              => array(
            'name'              => __('Project Categories', 'textdomain'),
            'singular_name'     => __('Project Category', 'textdomain'),
            'search_items'      => __('Search Categories', 'textdomain'),
            'all_items'         => __('All Categories', 'textdomain'),
            'parent_item'       => __('Parent Category', 'textdomain'),
            'parent_item_colon' => __('Parent Category:', 'textdomain'),
            'edit_item'         => __('Edit Category', 'textdomain'),
            'update_item'       => __('Update Category', 'textdomain'),
            'add_new_item'      => __('Add New Category', 'textdomain'),
            'new_item_name'     => __('New Category Name', 'textdomain'),
            'menu_name'         => __('Project Categories', 'textdomain'),
        ),
        'show_ui'             => true,
        'show_admin_column'   => true,
        'show_in_rest'        => true, // Enable for block editor
        'query_var'           => true,
        'rewrite'             => array(
            'slug' => 'project-category', // Customize your category URL structure
        ),
    );
    register_taxonomy('project_category', 'project', $args);
}
add_action('init', 'register_project_taxonomy');


// Register theme options page
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title'    => 'Theme Options',
        'menu_title'    => 'Theme Options',
        'menu_slug'     => 'theme-options',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}

function register_custom_block()
{
    if (function_exists('acf_register_block_type')) {
        acf_register_block_type(array(
            'name'              => 'custom-block',
            'title'             => __('Custom Block'),
            'description'       => __('A block with two variations.'),
            'render_template'   => 'template-parts/blocks/custom-block.php',
            'category'          => 'layout',
            'icon'              => 'admin-post',
            'keywords'          => array('custom', 'variation', 'block'),
            'supports'          => array('align' => false),
        ));
    }
}
add_action('acf/init', 'register_custom_block');



// Define the shortcode function
function custom_hello_shortcode() {
    return '<p>Hello, We are KudosIntech. A web and mobile development services agency!</p>';
}
add_shortcode('hello', 'custom_hello_shortcode');



// Enqueue Font Awesome for social icons
function enqueue_font_awesome()
{
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');




// Define CPT Post & Category in Gravity form
add_filter('gform_pre_render_3', 'populate_cpt_and_categories');
add_filter('gform_pre_submission_filter_3', 'populate_cpt_and_categories');

function populate_cpt_and_categories($form) {
    $cpt_slug = 'project';
    $taxonomy = 'project_category';

    // Get the current post ID and title
    $current_post_id = get_the_ID();
    $current_post_title = get_the_title($current_post_id);

    foreach ($form['fields'] as &$field) {
        if ($field->id == 41) {
            $field->choices = [];
            $args = [
                'post_type'      => $cpt_slug,
                'posts_per_page' => -1,
                'post_status'    => 'publish',
            ];
            $posts = get_posts($args);
            foreach ($posts as $post) {
                $field->choices[] = [
                    'text'     => $post->post_title,
                    'value'    => $post->ID,
                    'isSelected' => $post->ID == $current_post_id, // Preselect current post
                ];
            }
        }

        // Populate Categories in Dropdown
        if ($field->id == 50) {
            $field->choices = [];
            $categories = get_terms([
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
            ]);
            foreach ($categories as $category) {
                $field->choices[] = [
                    'text'     => $category->name,
                    'value'    => $category->term_id,
                    'isSelected' => has_term($category->term_id, $taxonomy, $current_post_id), 
                ];
            }
        }
    }
    return $form;
}


// Enqueue jQuery

function enqueue_filter_scripts() {
    wp_enqueue_script('filter-posts', get_stylesheet_directory_uri() . '/js/filter-posts.js', array('jquery'), null, true);
    wp_localize_script('filter-posts', 'ajaxfilter', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_filter_scripts');



// Ajax Filter

function filter_posts_ajax() {
    // Get the filter values
    $dimensions = isset($_GET['dimensions']) ? sanitize_text_field($_GET['dimensions']) : '';
    $starting_price = isset($_GET['starting_price']) ? sanitize_text_field($_GET['starting_price']) : '';

    // Set up the WP_Query args
    $args = array(
        'post_type' => 'project',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'fields' => 'ids', //
    );

    if ($dimensions) {
        $args['meta_query'][] = array(
            'key' => 'dimensions',
            'value' => $dimensions,
            'compare' => '='
        );
    }

    if ($starting_price) {
        $args['meta_query'][] = array(
            'key' => 'starting_price',
            'value' => $starting_price,
            'compare' => '='
        );
    }

    // Execute the query
    $query = new WP_Query($args);

    // Check if we have posts
    if ($query->have_posts()) {
        echo '<div id="projects-container" class="cpt-archive">';
        echo '<div class="cpt-grid">';
        
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <div class="cpt-item">
                <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="cpt-image">
                            <?php the_post_thumbnail('medium', ['alt' => esc_attr(get_the_title())]); ?>
                        </div>
                    <?php endif; ?>
                    <h2 class="cpt-title"><?php the_title(); ?></h2>
                </a>
                <p class="cpt-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                <div class="cpt-details">
                    <p><strong><?php _e('Dimensions:', 'textdomain'); ?></strong> <?php echo esc_html(get_field('dimensions')); ?></p>
                    <p><strong><?php _e('Starting Price:', 'textdomain'); ?></strong> <?php echo esc_html(get_field('starting_price')); ?></p>
                </div>
            </div>
            <?php
        }
        
        echo '</div>'; // Close the cpt-grid
        echo '</div>'; // Close the cpt-archive
    } else {
        echo 'No posts found.';
    }

    // Reset post data
    wp_reset_postdata();

    // Always die in AJAX functions
    die();
}
add_action('wp_ajax_filter_posts', 'filter_posts_ajax');
add_action('wp_ajax_nopriv_filter_posts', 'filter_posts_ajax');


fsdfsfdsfdfgfdgdgfd yu6yuyuyuyu
