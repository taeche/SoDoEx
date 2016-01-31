<?php

/**
 * BuddyPress - Activity Stream Comment
 *
 * This template is used by bp_activity_comments() functions to show
 * each activity.
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php do_action( 'bp_before_activity_comment' ); ?>

<li id="acomment-<?php call_user_func( 'bp_activity_comment_id' ); ?>">
	<div class="acomment-avatar">
		<a href="<?php call_user_func( 'bp_activity_comment_user_link' ); ?>">
			<?php call_user_func( 'bp_activity_avatar', 'type=thumb&user_id=' . call_user_func( 'bp_get_activity_comment_user_id' ) ); ?>
		</a>
	</div>

	<div class="acomment-meta">
		<?php
		/* translators: 1: user profile link, 2: user name, 3: activity permalink, 4: activity timestamp */
		printf( __( '<a href="%1$s">%2$s</a> replied <a href="%3$s" class="activity-time-since"><span class="time-since">%4$s</span></a>', 'jv_allinone' ), call_user_func( 'bp_get_activity_comment_user_link' ), call_user_func( 'bp_get_activity_comment_name' ), call_user_func( 'bp_get_activity_comment_permalink' ), call_user_func( 'bp_get_activity_comment_date_recorded' ) );
		?>
	</div>

	<div class="acomment-content"><?php call_user_func( 'bp_activity_comment_content' ); ?></div>

	<div class="acomment-options">

		<?php if ( is_user_logged_in() && call_user_func( 'bp_activity_can_comment_reply', call_user_func( 'bp_activity_current_comment' ) ) ) : ?>

			<a href="#acomment-<?php call_user_func( 'bp_activity_comment_id' ); ?>" class="acomment-reply bp-primary-action icon-reply" id="acomment-reply-<?php call_user_func( 'bp_activity_id' ); ?>-from-<?php call_user_func( 'bp_activity_comment_id' ); ?>"></a>

		<?php endif; ?>

		<?php if ( call_user_func( 'bp_activity_user_can_delete' ) ) : ?>

			<a href="<?php call_user_func( 'bp_activity_comment_delete_link' ); ?>" class="delete acomment-delete confirm bp-secondary-action icon-remove" rel="nofollow"></a>

		<?php endif; ?>

		<?php do_action( 'bp_activity_comment_options' ); ?>

	</div>

	<?php call_user_func( 'bp_activity_recurse_comments', call_user_func( 'bp_activity_current_comment' ) ); ?>
</li>

<?php do_action( 'bp_after_activity_comment' );