<?php get_header(); ?>

<div class="project-post">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="project-container">
            <h1><?php the_title(); ?></h1>
            <p><?php the_excerpt(); ?></p>

            <div class="project-image">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large'); ?>
                <?php endif; ?>
            </div>
            <div class="project-details">
                <p><strong>Dimensions:</strong> <?php the_field('dimensions'); ?></p>
                <p><strong>Starting Price:</strong> <?php the_field('starting_price'); ?></p>
                
                <!-- Button to Open Modal -->
                <div class="project-buttons">
                    <?php
                    // Get ACF link field
                    $button_link = get_field('button');
                    if ($button_link): ?>
                        <button type="button" 
                                class="btn btn-secondary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#gravityFormModal" 
                                data-form-id="<?php echo esc_attr($button_link['url']); ?>">
                            <?php echo esc_html($button_link['title']); ?>
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Modal Structure -->
                <div class="modal fade" id="gravityFormModal" tabindex="-1" aria-labelledby="gravityFormModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <!-- Dynamic Post Title in Modal Header -->
                                <h5 class="modal-title" id="gravityFormModalLabel"><?php the_title(); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <p class="modal-desc"><?php the_excerpt(); ?></p>

                            <div class="modal-body">
                                <!-- Gravity Form Shortcode -->
                                <?php echo do_shortcode('[gravityform id="3" title="true" description="false" ajax="true"]'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
