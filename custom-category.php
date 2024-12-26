<?php
/**
 * Template Name: Project Categories
 * Description: A custom template to display all Project categories with images.
 */

get_header(); 

?>

<div class="container">
    <h1 class="page-title"><?php _e( 'Project Categories', 'textdomain' ); ?></h1>

    <div class="taxonomy-grid">
        <?php
        // Get all terms in the 'project_category' taxonomy
        $terms = get_terms(array(
            'taxonomy'   => 'project_category',
            'orderby'    => 'name',            
            'order'      => 'ASC',             
            'hide_empty' => false,             
        ));

        if ( !empty( $terms ) && !is_wp_error( $terms ) ) :
            foreach ( $terms as $term ) :
                $term_image_id = get_term_meta( $term->term_id, 'category_image', true );
                $term_image_url = wp_get_attachment_url( $term_image_id );
        ?>
            <div class="taxonomy-item">
                <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="taxonomy-link" aria-label="<?php echo esc_attr( $term->name ); ?>">
                    <?php if ( $term_image_url ) : ?>
                        <div class="taxonomy-image">
                            <img src="<?php echo esc_url( $term_image_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" />
                        </div>
                    <?php endif; ?>
                    <h2 class="taxonomy-title"><?php echo esc_html( $term->name ); ?></h2>
                </a>
            </div>
        <?php
            endforeach;
        else :
            echo '<p>' . __( 'No categories found.', 'textdomain' ) . '</p>';
        endif;
        ?>
    </div>
</div>

<?php
get_footer();
?>
