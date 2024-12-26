<?php 
// Get ACF fields
$image = get_field('image'); 
$title = get_field('title');
$description = get_field('description');
$button_text = get_field('button_text');
$button_link = get_field('button_link');
$button_alignment = get_field('button_alignment'); // Left or Right
$image_alignment = get_field('image_alignment'); // Left or Right
?>


<div class="zaid-block">
    <?php if( $image ): ?>
        <div class="zaid-block__image zaid-block__image--<?php echo esc_attr(strtolower($image_alignment)); ?>">
            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
        </div>
    <?php endif; ?>

    <?php if( $title ): ?>
        <h2 class="zaid-block__title"><?php echo esc_html($title); ?></h2>
    <?php endif; ?>

    <?php if( $description ): ?>
        <p class="zaid-block__description"><?php echo esc_html($description); ?></p>
    <?php endif; ?>

    <?php if( $button_text && $button_link ): ?>
        <div class="zaid-block__button" style="text-align: <?php echo esc_attr($button_alignment); ?>">
            <a href="<?php echo esc_url($button_link); ?>" class="button"><?php echo esc_html($button_text); ?></a>
        </div>
    <?php endif; ?>
</div>