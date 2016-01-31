<p data-tag="wjvheader">
	<label style="display:block; margin-bottom:10px;" for="<?php echo self::$prefix?>"><strong><?php _e( "Header", 'jvheader' ); ?></strong></label>
	<select class="widefat" name="<?php echo self::$prefix?>" id="<?php echo self::$prefix?>">   
		<?php foreach( $data as $key => $text ) : ?>
        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $header, $key ); ?>><?php echo apply_filters( 'jvheader_text', $text ); ?></option>
        <?php endforeach; ?>           
	</select>
</p>
<style>
#jvheader_optionmeta,
[for^="jvheader_optionmeta"] { display: none !important; }
</style>
<script type="text/javascript">
jQuery( function( $ ) {
    
    $( '[data-tag="wjvheader"]' ).insertAfter( $( '#page_template') );
    
} );
</script>