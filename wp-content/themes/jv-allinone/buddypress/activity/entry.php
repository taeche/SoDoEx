<?php

/**
 * BuddyPress - Activity Stream (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php do_action( 'bp_before_activity_entry' ); ?>

<li class="<?php call_user_func( 'bp_activity_css_class' ); ?>" id="activity-<?php call_user_func( 'bp_activity_id' ); ?>">
	<div class="activity-avatar">
		<a href="<?php call_user_func( 'bp_activity_user_link' ); ?>">

			<?php call_user_func( 'bp_activity_avatar' ); ?>

		</a>
	</div>

	<div class="activity-content">

		<div class="activity-header">

			<?php call_user_func( 'bp_activity_action' ); ?>

		</div>

		<?php if ( call_user_func( 'bp_activity_has_content' ) ) : ?>

			<div class="activity-inner">

				<?php call_user_func( 'bp_activity_content_body' ); ?>

			</div>

		<?php endif; ?>

		<?php do_action( 'bp_activity_entry_content' ); ?>

		<div class="activity-meta">

			<?php if ( call_user_func( 'bp_get_activity_type' ) == 'activity_comment' ) : ?>

				<a href="<?php call_user_func( 'bp_activity_thread_permalink' ); ?>" class=" view icon-eye22 bp-secondary-action" title="<?php esc_attr_e( 'View Conversation', 'jv_allinone' ); ?>"><?php _e( 'View Conversation', 'jv_allinone' ); ?></a>

			<?php endif; ?>

			<?php if ( is_user_logged_in() ) : ?>

				<?php if ( call_user_func( 'bp_activity_can_comment' ) ) : ?>

					<a href="<?php call_user_func( 'bp_activity_comment_link' ); ?>" class=" acomment-reply icon-comments3 bp-primary-action" id="acomment-comment-<?php call_user_func( 'bp_activity_id' ); ?>"><?php printf( __( ' <span>%s</span> Comments', 'jv_allinone' ), call_user_func( 'bp_activity_get_comment_count' ) ); ?></a>

				<?php endif; ?>

				<?php if ( call_user_func( 'bp_activity_can_favorite' ) ) : ?>

					<?php if ( !call_user_func( 'bp_get_activity_is_favorite' ) ) : ?>

						<a href="<?php call_user_func( 'bp_activity_favorite_link' ); ?>" class=" fav icon-heart62 bp-secondary-action " title="<?php esc_attr_e( 'Mark as Favorite', 'jv_allinone' ); ?>"><?php _e( ' Favorite', 'jv_allinone' ); ?></a>

					<?php else : ?>

						<a href="<?php call_user_func( 'bp_activity_unfavorite_link' ); ?>" class=" unfav icon-heart62 bp-secondary-action " title="<?php esc_attr_e( 'Remove Favorite', 'jv_allinone' ); ?>"><?php _e( ' Remove Favorite', 'jv_allinone' ); ?></a>

					<?php endif; ?>

				<?php endif; ?>

				<?php if ( call_user_func( 'bp_activity_user_can_delete' ) ) call_user_func( 'bp_activity_delete_link' ); ?>

				<?php do_action( 'bp_activity_entry_meta' ); ?>

			<?php endif; ?>

		</div>

	</div>

	<?php do_action( 'bp_before_activity_entry_comments' ); ?>

	<?php if ( ( call_user_func( 'bp_activity_get_comment_count' ) || call_user_func( 'bp_activity_can_comment' ) ) || call_user_func( 'bp_is_single_activity' ) ) : ?>

		<div class="activity-comments">

			<?php call_user_func( 'bp_activity_comments' ); ?>

			<?php if ( is_user_logged_in() && call_user_func( 'bp_activity_can_comment' ) ) : ?>

				<form action="<?php call_user_func( 'bp_activity_comment_form_action' ); ?>" method="post" id="ac-form-<?php call_user_func( 'bp_activity_id' ); ?>" class="ac-form"<?php call_user_func( 'bp_activity_comment_form_nojs_display' ); ?>>
					<div class="ac-reply-avatar"><?php call_user_func( 'bp_loggedin_user_avatar', 'width=' . BP_AVATAR_THUMB_WIDTH . '&height=' . BP_AVATAR_THUMB_HEIGHT ); ?></div>
					<div class="ac-reply-content">
						<div class="ac-textarea">
							<textarea id="ac-input-<?php call_user_func( 'bp_activity_id' ); ?>" class="ac-input bp-suggestions" name="ac_input_<?php call_user_func( 'bp_activity_id' ); ?>"></textarea>
						</div>
						<input type="submit" name="ac_form_submit" value="<?php esc_attr_e( 'Post', 'jv_allinone' ); ?>" /> &nbsp; <a href="#" class="ac-reply-cancel"><?php _e( 'Cancel', 'jv_allinone' ); ?></a>
						<input type="hidden" name="comment_form_id" value="<?php call_user_func( 'bp_activity_id' ); ?>" />
					</div>

					<?php do_action( 'bp_activity_entry_comments' ); ?>

					<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ); ?>

				</form>

			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'bp_after_activity_entry_comments' ); ?>

</li>

<?php do_action( 'bp_after_activity_entry' );
