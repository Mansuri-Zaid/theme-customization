<?php
// Get block fields
$variation = get_field('variation');
$title = get_field('title');
$content = get_field('content');
$image = get_field('image');
$img_show = get_field('img_show');
$buttons = get_field('buttons');
$buttons_show = get_field('buttons_show');
$button_url = get_sub_field('url');
$button_title = get_sub_field('title');

$video = get_field('video');
$video_show = get_field('video_show');
$single_button = get_field('single_button');
$single_show = get_field('single_show');

// Start block output
if ($variation == 'variation_1') : ?>
    <div class="custom-block variation-1">
        <h2><?php echo esc_html($title); ?></h2>
            <div class="content"><?php echo wp_kses_post($content); ?></div>
             <?php if ( $img_show == 'yes' ) : ?>
                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
             <?php endif; ?>

            <?php if( $buttons_show == 'yes' ): 
                $url = $buttons['url'];
                $title = $buttons['title'];
            ?>
            <!-- Display the button -->
            <div class="buttons">
                <a href="<?php echo esc_url($url); ?>" class="button" target="_blank">
                <?php echo esc_html($title); ?></a>
            </div>
            <?php endif; ?>
        </div>
<?php elseif ($variation == 'variation_2') : ?>
    <div class="custom-block variation-2">
        <h2><?php echo esc_html($title); ?></h2>
            <div class="content"><?php echo wp_kses_post($content); ?>
        </div>

            <?php if ( $video_show == 'yes' ) : ?>
                <?php echo("$video"); ?>
            <?php endif; ?>

            <?php if( $single_show == 'yes' ): 
                $url = $single_button['url'];
                $title = $single_button['title'];
            ?>
            <!-- Display the button -->
            <div class="buttons">
                <a href="<?php echo esc_url($url); ?>" class="button" target="_blank">
                <?php echo esc_html($title); ?></a>
            </div>
<?php endif; ?>
<?php endif; ?>
