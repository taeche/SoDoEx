<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Database Sync Custom - Sync with Remote Server</h2>

	<h3>Push</h3>

	<form method="post" action="<?php dbsc_url(); ?>">
		<input type="hidden" name="dbsc_action" value="push">
		<input type="hidden" name="url" value="<?php echo $url; ?>">
		<p><?php echo dbsc_stripHttp(get_bloginfo('wpurl')) . " &#x21d2 " . dbsc_stripHttp($url); ?></p>
		<p>
			<b>Delete all data except for <span style="color: red">user</span> and <span style="color: red">order</span> data</b> in the remote WordPress database and replace with the data from this database.
			<input type="submit" value="Push" class="button-primary">
		</p>
	</form>

	<h3>Pull</h3>

	<form method="post" action="<?php dbsc_url(); ?>">
		<input type="hidden" name="dbsc_action" value="pull">
		<input type="hidden" name="url" value="<?php echo $url; ?>">
		<p><?php echo dbsc_stripHttp(get_bloginfo('wpurl')) . " &#x21d0 " . dbsc_stripHttp($url); ?></p>
		<p>
			<b>Delete all data except for <span style="color: red">user</span> and <span style="color: red">order</span> data data</b> in this database and replace with the data from the remote WordPress database.
			<input type="submit" value="Pull" class="button-primary">
		</p>
	</form>