<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package TWG_Material
 */

?>
</div><!-- #content -->

<?php if (is_active_sidebar('after-content')) : ?>
    <div id="after-content" class="widget-area">
        <?php dynamic_sidebar('after-content'); ?>
    </div>
<?php endif; ?>

<?php get_template_part("template-parts/foot"); ?>

<?php wp_footer(); ?>

<?php if (twg_material_option('hook-closing-body')) : ?>
    <?php echo apply_filters("material-hook-closing-body", twg_material_option('hook-closing-body')) ?>
<?php endif; ?>

<?php
if (twg_material_page_option('custom_js')) {
?>
    <script type="text/javascript">
        <?php echo twg_material_page_option('custom_js') ?>
    </script>
<?php } ?>

<!-- ADA SVG icon add aria-label start -->
<script>
    jQuery(document).ready(function() {
        jQuery('.svg-link svg').attr('aria-label', 'social-icons');
        jQuery('.twg-logo-st0').attr('aria-label', 'twg-logo');
    });
</script>
<!-- ADA SVG icon add aria-label end -->

<!-- Footer Section -->
<footer class="site-footer">
    <div class="footer-content">
        <!-- Social Icons -->
        <div class="social-icons">
            <?php
            $footer_text = get_field('content', 'option');
            $social_icons = get_field('social_icons', 'option');
            ?>
            <footer>
                <p><?php echo esc_html($footer_text); ?></p>
                <ul class="social-icons">
                    <?php if ($social_icons): ?>
                        <?php foreach ($social_icons as $icon): ?>
                            <?php
                            $platform_name = strtolower($icon['platform_name']);
                            $fa_icon = '';

                            // Map platform names to Font Awesome classes
                            switch ($platform_name) {
                                case 'facebook':
                                    $fa_icon = 'fab fa-facebook-f';
                                    break;
                                case 'twitter':
                                    $fa_icon = 'fab fa-twitter';
                                    break;
                                case 'instagram':
                                    $fa_icon = 'fab fa-instagram';
                                    break;
                                case 'linkedin':
                                    $fa_icon = 'fab fa-linkedin-in';
                                    break;
                                case 'youtube':
                                    $fa_icon = 'fab fa-youtube';
                                    break;
                                default:
                                    $fa_icon = 'fas fa-link'; // Default icon for unknown platforms
                                    break;
                            }
                            ?>
                            <li>
                                <a href="<?php echo esc_url($icon['url']); ?>" target="_blank">
                                    <i class="<?php echo esc_attr($fa_icon); ?>"></i>
                                    <span class="screen-reader-text"><?php echo esc_html($icon['platform_name']); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </footer>


        </div>
    </div>
</footer>
<!-- End of Footer Section -->