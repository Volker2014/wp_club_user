<?php
function clubuser_register_settings() {
   add_option( 'clubuser_group', '');
   add_option( 'clubuser_position', '');
   register_setting( 'clubuser_options_group', 'clubuser_group', 'clubuser_textarea_callback' );
   register_setting( 'clubuser_options_group', 'clubuser_position', 'clubuser_textarea_callback' );
}
add_action( 'admin_init', 'clubuser_register_settings' );

function clubuser_register_options_page() {
  add_options_page('Page Title', 'Club User', 'manage_options', 'ClubUser', 'clubuser_options_page');
}
add_action('admin_menu', 'clubuser_register_options_page');

function clubuser_textarea_callback($input)
{
	return preg_split('/[\r\n]+/', $input, -1, PREG_SPLIT_NO_EMPTY);
}

function clubuser_options_page()
{
?>
  <div>
  <?php screen_icon(); ?>
  <h2>Club User Settings</h2>
  <form method="post" action="options.php">
  <?php settings_fields( 'clubuser_options_group' ); ?>
  <table>
  <tr valign="top">
  <th scope="row"><label for="clubuser_group">Gruppen</label></th>
  <td><textarea name="clubuser_group" cols="40" rows="10"><?php echo implode(PHP_EOL, get_option('clubuser_group')); ?></textarea></td>
  </tr>
  <tr valign="top">
  <th scope="row"><label for="clubuser_position">Position</label></th>
  <td><textarea name="clubuser_position" cols="40" rows="10"><?php echo implode(PHP_EOL, get_option('clubuser_position')); ?></textarea></td>
  </tr>
  </table>
  <?php  submit_button(); ?>
  </form>
  </div>
<?php
}
?>