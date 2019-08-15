<div id="happyforms-responses-upgrade-existing" class="happyforms-upgrade-modal-container" style="display: none">
	<div class="happyforms-upgrade-modal">
		<a href="#" class="happyforms-upgrade-modal__close">
			<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-times-circle fa-w-16 fa-3x"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z" class=""></path></svg>
			<span class="screen-reader-text">Close</span>
		</a>

		<img src="<?php echo happyforms_get_plugin_url(); ?>/inc/assets/img/logo.png" alt="HappyForms" class="happyforms-logo">

		<h2>We've removed free responses…</h2>

		<p>Starting today, you'll need to upgrade HappyForms to save responses and get access to <a href="https://happyforms.me/features" target="_blank">all paid features</a>.</p>

		<p>We know this is surprising, and we're sorry! 😔 We want to continue developing the free HappyForms plugin, but we can't do this without the support of more paying customers.</p>

		<p>To make your transition easier, we're offering 50% off upgrades.<br>Use the coupon code “TRANSITION” or <a href="mailto:support@thethemefoundry.com">email us for help</a>.</p>

		<div class="happyforms-upgrade-modal__buttons">
			<?php $forms = happyforms_get_message_controller()->get_archivable_forms(); ?>
			<div class="happyforms-upgrade-modal__button">
				<a href="#" class="button happyforms-export-button">Download My Archived Responses</a>
			</div>
			<form action="<?php echo admin_url( 'edit.php?post_type=happyform' ); ?>">
				<input type="hidden" name="post_type" value="happyform" />
				<select name="archive">
				<?php foreach( $forms as $form ) : ?>
					<option value="<?php echo $form->ID; ?>"><?php echo $form->post_title; ?></option>
				<?php endforeach; ?>
				</select>
				<input type="submit" class="button" value="Download">
			</form>
			<div class="happyforms-upgrade-modal__button">
				<a href="https://happyforms.me/upgrade" target="_blank" class="button button-primary">Support HappyForms By Upgrading</a>
			</div>
			<div class="happyforms-upgrade-modal__button happyforms-upgrade-modal__button--grey">
				Or <a href="#" class="happyforms-continue-link">continue with free version</a>
			</div>
		</div>
	</div>
</div>

<div id="happyforms-responses-upgrade-new" class="happyforms-upgrade-modal-container" style="display: none">
	<div class="happyforms-upgrade-modal">
		<a href="#" class="happyforms-upgrade-modal__close">
			<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-times-circle fa-w-16 fa-3x"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z" class=""></path></svg>
			<span class="screen-reader-text">Close</span>
		</a>

		<img src="<?php echo happyforms_get_plugin_url(); ?>/inc/assets/img/logo.png" alt="HappyForms" class="happyforms-logo">

		<h2>Read responses and see recipient data…</h2>

		<p>HappyForms doesn't have the function to save responses.<br> You'll need to upgrade HappyForms to save responses and get access to <a href="https://happyforms.me/features" target="_blank">all paid features</a>.</p>

		<div class="happyforms-upgrade-modal__buttons">
			<div class="happyforms-upgrade-modal__button">
				<a href="https://happyforms.me/upgrade" target="_blank" class="button button-primary">Discover HappyForms Upgrade</a>
			</div>
			<div class="happyforms-upgrade-modal__button happyforms-upgrade-modal__button--grey">
				Or <a href="#" class="happyforms-continue-link">continue with free version</a>
			</div>
		</div>
	</div>
</div>
