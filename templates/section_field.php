<?php 
    $selected = get_option( 'header_image' );
    $images = get_posts(
        array( 
            'post_type' => 'attachment',
            'post_mime_type' => array(
                'image/png',
                'image/gif',
                'image/jpeg'
            )
        )
    );
?>

<?php if ( count( $images ) > 0 ): ?>
<select name="header_image">
    <?php foreach( $images as $img ): ?>
        <option value="<?php echo $img->ID ?>" <?php selected($img->ID, $selected); ?>>
            <?php echo $img->post_title ?>
        </option>
    <?php endforeach; ?>
</select>
<?php else: ?>
<p><?php _e( 'Sorry, no images uploaded yet!' ) ?></p>
<?php endif; ?>
