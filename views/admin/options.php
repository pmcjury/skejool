<div id="pmc_skejool" class="wrap">
    <h2>Schedule Settings</h2>
    <form method="post" action="options.php">
        <?php settings_fields( 'pmc_skejool_options_settings_fields' ); ?>
        <?php do_settings_sections( 'pmc_skejool_options_settings' ); ?>
        <p>
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
        </p>
    </form>
</div>
