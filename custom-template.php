<?php
/**
 * Template Name: Custom-template
 * Description: A custom template to display Project CPT posts.
 */

get_header(); // Includes the header
?>

<div class="container">
    <h1 class="page-title"><?php _e( 'Residential Dumpster Service', 'textdomain' ); ?></h1>

    <form id="search-form">
        <input type="text" id="search-term" name="search-term" placeholder="Search posts..." />
        <button type="submit">Search</button>
    </form>
    <div id="search-results"></div>


    <form id="filter-form">
    <select id="dimensions" name="dimensions">
        <option value="">Select Dimensions</option>
        <!-- Populate options dynamically -->
        <?php
        $dimensions = get_posts(array(
            'post_type' => 'project', // Replace with your CPT
            'posts_per_page' => -1,
            'fields' => 'ids',
        ));

        foreach ($dimensions as $post_id) {
            $dimension_value = get_field('dimensions', $post_id);
            echo '<option value="' . esc_attr($dimension_value) . '">' . esc_html($dimension_value) . '</option>';
        }
        ?>
    </select>

    <select id="starting_price" name="starting_price">
        <option value="">Select Starting Price</option>
        <!-- Populate options dynamically -->
        <?php
        foreach ($dimensions as $post_id) {
            $price_value = get_field('starting_price', $post_id);
            echo '<option value="' . esc_attr($price_value) . '">' . esc_html($price_value) . '</option>';
        }
        ?>
    </select>

    <button type="button" id="filter-button">Apply Filters</button>
</form>

<div id="filtered-posts">

    <div id="projects-container" class="cpt-archive">
            <?php
            // Get the selected taxonomy term and search query
            $tax_filter = isset($_GET['tax_filter']) ? $_GET['tax_filter'] : '';
            $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

            // Query the 'project' custom post type
            $args = array(
                'post_type'      => 'project', // Custom post type slug is 'project'
                'posts_per_page' => 10, // Number of posts to display
                'orderby'        => 'date', // Order by date
                'order'          => 'DESC', // Latest posts first
                's'              => $search_query, // Search query if provided
            );

            // Add taxonomy filter if a category is selected
            if ($tax_filter) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'project_category',
                        'field'    => 'id',
                        'terms'    => $tax_filter,
                        'operator' => 'IN', // Can also use 'AND' or 'NOT IN' depending on the use case
                    ),
                );
            }

            // Perform the query
            $project_query = new WP_Query($args);

            if ($project_query->have_posts()) :
            ?>
                <div class="cpt-grid">
                    <?php while ($project_query->have_posts()) : $project_query->the_post(); ?>
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
                                <p><strong><?php _e( 'Dimensions:', 'textdomain' ); ?></strong> <?php echo esc_html(get_field('dimensions')); ?></p>
                                <p><strong><?php _e( 'Starting Price:', 'textdomain' ); ?></strong> <?php echo esc_html(get_field('starting_price')); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="pagination">
                    <?php
                    // Display pagination
                    echo paginate_links(array(
                        'total' => $project_query->max_num_pages,
                    ));
                    ?>
                </div>
                <?php else : ?>
                    <p><?php _e('No projects found.', 'textdomain'); ?></p>
                <?php endif; ?>

                <?php wp_reset_postdata(); // Reset the query ?>
            </div>
    </div>

</div>


<?php
get_footer(); // Includes the footer
?>
