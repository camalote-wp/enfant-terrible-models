<?php
/**
 * Cover Article - render.php
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 */

$selected_post_id   = $attributes['selectedPost']['id'] ?? null;
$selected_post_type = $attributes['selectedPost']['type'] ?? 'post';

if ( ! $selected_post_id ) {
    return;
}

$post = get_post( $selected_post_id );

if ( ! $post ) {
    return;
}

// Exclude this post from subsequent Query Loop blocks on the same page.
add_filter( 'query_loop_block_query_vars', function( array $query ) use ( $selected_post_id ): array {
    $query['post__not_in'] = array_merge(
        $query['post__not_in'] ?? [],
        [ (int) $selected_post_id ]
    );
    return $query;
} );

$post_link      = get_permalink( $selected_post_id );
$post_title     = get_the_title( $selected_post_id );
$post_date      = get_the_date( '', $selected_post_id );
$post_date_iso  = get_the_date( 'c', $selected_post_id );

$author_id      = $post->post_author;
$author_name    = get_the_author_meta( 'display_name', $author_id );
$author_link    = get_author_posts_url( $author_id );

$thumbnail_id   = get_post_thumbnail_id( $post );
$thumbnail      = wp_get_attachment_image( $thumbnail_id, 'large', false, [
    'class' => 'wp-block-camalote-wp-cover-article__image',
    'alt'   => esc_attr( $post_title ),
] );

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => 'alignwide' ] );
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="wp-block-camalote-wp-cover-article__inner">

        <div class="wp-block-camalote-wp-cover-article__media">
            <a href="<?php echo esc_url( $post_link ); ?>" class="wp-block-camalote-wp-cover-article__image-link" tabindex="-1" aria-hidden="true">
                <?php echo $thumbnail; ?>
            </a>
        </div>

        <div class="wp-block-camalote-wp-cover-article__content">
            <a href="<?php echo esc_url( $post_link ); ?>" class="wp-block-camalote-wp-cover-article__title-link">
                <h1 class="wp-block-camalote-wp-cover-article__title"><?php echo esc_html( $post_title ); ?></h1>
            </a>
            <div class="wp-block-camalote-wp-cover-article__meta">
                <span class="wp-block-camalote-wp-cover-article__author">
                    <a href="<?php echo esc_url( $author_link ); ?>" class="wp-block-camalote-wp-cover-article__author-link">
                        <span class="wp-block-camalote-wp-cover-article__author-name"><?php echo esc_html( $author_name ); ?></span>
                    </a>
                </span>
                <time class="wp-block-camalote-wp-cover-article__date" datetime="<?php echo esc_attr( $post_date_iso ); ?>">
                    <?php echo esc_html( $post_date ); ?>
                </time>
            </div>
        </div>

    </div>
</div>