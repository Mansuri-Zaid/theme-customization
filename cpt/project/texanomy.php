<?php
get_header();

$current_term = get_queried_object();
?>

<div class="container">
    <h1 class="page-title"><?php single_term_title(); ?></h1>
    
    <div class="taxonomy-description">
        <?php echo term_description(); ?>
    </div>

    <div class="project-archive">
        <?php
        $args = array(
            'post_type'      => 'project', 
            'posts_per_page' => 10, 
            'orderby'        => 'date', 
            'order'          => 'DESC', 
            'tax_query' => array(
                array(
                    'taxonomy' => 'project_category', 
                    'field'    => 'term_id',
                    'terms'    => $current_term->term_id, 
                    'operator' => 'IN',
                ),
            ),
        );

        $project_query = new WP_Query($args);

        if ($project_query->have_posts()) :
        ?>
            <div class="project-grid">
                <?php while ($project_query->have_posts()) : $project_query->the_post(); ?>
                    <div class="project-item">
                        <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="project-image">
                                    <?php the_post_thumbnail('medium', ['alt' => esc_attr(get_the_title())]); ?>
                                </div>
                            <?php endif; ?>
                            <h2 class="project-title"><?php the_title(); ?></h2>
                        </a>
                        <p class="project-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                        <div class="project-details">
                            <p><strong><?php _e( 'Dimensions:', 'textdomain' ); ?></strong> <?php echo esc_html(get_field('dimensions')); ?></p>
                            <p><strong><?php _e( 'Starting Price:', 'textdomain' ); ?></strong> <?php echo esc_html(get_field('starting_price')); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'total' => $project_query->max_num_pages,
                ));
                ?>
            </div>
        <?php else : ?>
            <p><?php _e('No projects found in this category.', 'textdomain'); ?></p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</div>

<?php
get_footer();
?>
