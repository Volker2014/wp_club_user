<?php
/*
Plugin Name: Club User
Plugin URI: 
Description: Modify additional user fields for a club, show user list
Version: 0.2
Author: Volker Riecken
Author URI: 
Update Server: 
Min WP Version: 4.9.4
Max WP Version: 4.9.4
*/

include_once(plugin_dir_path(__FILE__).'options.php');
include_once(plugin_dir_path(__FILE__).'templates/vorstand.php');

function clubuser_admin_head_scripts() {
    wp_enqueue_media();
    wp_enqueue_script('clubuser-js-custom', plugins_url('js/scripts.js', __FILE__), array(), '1.3', true);
}
add_action( 'admin_enqueue_scripts', 'clubuser_admin_head_scripts' ); 

add_shortcode("userlist", "user_display_table");

function userMetaClubForm(WP_User $user) {
	$clubuser_group = get_user_meta($user->ID, 'clubuser_group', true);
	if (empty($clubuser_group)) $clubuser_group = array();
	$groups = get_option('clubuser_group');
	$clubuser_position = esc_attr(get_user_meta($user->ID, 'clubuser_position', true));
	$positions = get_option('clubuser_position');
	$clubuser_avatar = get_user_meta($user->ID, 'clubuser_avatar', true);
	$userData = array();
	$fields = array('clubuser_street', 'clubuser_postal', 'clubuser_city', 'clubuser_phone', 'clubuser_mail');
	foreach ($fields as $field) {
		$userData[$field] = get_user_meta($user->ID, $field, true);
	}
?>
<h2>Adressdaten</h2>
    <table class="form-table">
        <tr>
            <th><label for="user_street">Strasse</label></th>
            <td>
				<input type="text" name="clubuser_street" value="<?php echo $userData['clubuser_street']; ?>" size="50" />
            </td>
        </tr>
        <tr>
            <th><label for="user_postal">PLZ</label></th>
            <td>
				<input type="text" name="clubuser_postal" value="<?php echo $userData['clubuser_postal']; ?>" size="5" />
            </td>
        </tr>
        <tr>
            <th><label for="user_city">Ort</label></th>
            <td>
				<input type="text" name="clubuser_city" value="<?php echo $userData['clubuser_city']; ?>" size="50" />
            </td>
        </tr>
        <tr>
            <th><label for="user_phone">Telefon</label></th>
            <td>
				<input type="text" name="clubuser_phone" value="<?php echo $userData['clubuser_phone']; ?>" size="50" />
            </td>
        </tr>
    </table>
<h2>Verein</h2>
    <table class="form-table">
        <tr>
            <th><label for="clubuser_group">Gruppe</label></th>
            <td>
			<?php
				foreach ($groups as $group)
				{
					$checked = in_array($group, $clubuser_group) ? 'checked="checked"' : '';
					echo '<input type="checkbox" value="' . $group . '" name="clubuser_group[]" ' . $checked . ' >' . $group . '</input><br/>';
				}
			?>
                <span class="description">Bitte Gruppe(n) zuordnen</span>
            </td>
        </tr>
        <tr>
            <th><label for="clubuser_position">Position</label></th>
            <td>
				<select name="clubuser_position">
			<?php
				$selected = '' == $clubuser_position ? 'selected="selected"' : '';
				echo '<option value="" ' . $selected . '>-</option>';
				foreach ($positions as $position)
				{
					$selected = $position == $clubuser_position ? 'selected="selected"' : '';
					echo '<option value="' .$position . '" ' . $selected . '>' . $position . '</option>';
				}
			?>
				</select>
                <span class="description">Bitte Position auswählen</span>
            </td>
        </tr>
        <tr>
            <th><label for="clubuser_mail">EMail</label></th>
            <td>
				<input type="text" name="clubuser_mail" value="<?php echo $userData['clubuser_mail']; ?>" size="50" />
            </td>
        </tr>
        <tr>
            <th><label for="clubuser_avatar">Avatar</label></th>
            <td>
				<input type="number" name="clubuser_avatar" class="clubuser_avatar" value="<?php echo $clubuser_avatar; ?>" style="display:none" />
				<div class="clubuser_avatar">
					<img src='<?php echo wp_get_attachment_image_src((int)$clubuser_avatar, 'thumbnail')[0]; ?>' />
				</div>
				<div class="wp-media-buttons">
					<button class="clubuser-avatar-add">Auswählen</button>
					<button class="clubuser-avatar-remove">Entfernen</button>
				</div>
            </td>
        </tr>
    </table>
<?php
}
add_action('show_user_profile', 'userMetaClubForm'); // editing your own profile
add_action('edit_user_profile', 'userMetaClubForm'); // editing another user
add_action('user_new_form', 'userMetaClubForm'); // creating a new user
 
function userMetaClubSave($userId) {
    if (!current_user_can('edit_user', $userId)) {
        return;
    }
	
    update_user_meta($userId, 'clubuser_group', $_REQUEST["clubuser_group"]);
	$position = $_REQUEST["clubuser_position"] == '-' ? '' : $_REQUEST["clubuser_position"];
    update_user_meta($userId, 'clubuser_position', $position);
    update_user_meta($userId, 'clubuser_mail', $_REQUEST["clubuser_mail"]);
    update_user_meta($userId, 'clubuser_street', $_REQUEST["clubuser_street"]);
    update_user_meta($userId, 'clubuser_postal', $_REQUEST["clubuser_postal"]);
    update_user_meta($userId, 'clubuser_city', $_REQUEST["clubuser_city"]);
    update_user_meta($userId, 'clubuser_phone', $_REQUEST["clubuser_phone"]);
    update_user_meta($userId, 'clubuser_avatar', $_REQUEST["clubuser_avatar"]);
}

add_action('personal_options_update', 'userMetaClubSave');
add_action('edit_user_profile_update', 'userMetaClubSave');
add_action('user_register', 'userMetaClubSave');

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'clubuser_action_links' );

function clubuser_action_links( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=ClubUser') ) .'">Settings</a>';
   return $links;
}

function get_clubuser_avatar($user_id) {
    $clubuser_avatar = (int)get_user_meta( (int)$user_id, 'clubuser_avatar', true );
    $avatar = wp_get_attachment_image_src((int)$clubuser_avatar, 'thumbnail')[0];

    return $avatar;
}

function cmp_order($a, $b)
{
    return $a['order'] - $b['order'];
}

function get_user_data($atts) { 
    extract(shortcode_atts(array(
                    "group" => '' 
                    ), $atts));
	$args = array( 'fields' => 'all' );

	$user_query = new WP_User_Query( $args );

	$userData = array();
	
	if ( !empty( $user_query->get_results() ) ) {
		$positions = get_option('clubuser_position');
		foreach ($user_query->get_results() as $user) {
			$clubuser_group = get_user_meta($user->ID, 'clubuser_group', true);
			if (!is_null($clubuser_group)) {
				if(in_array($group, $clubuser_group)) {
					$meta = get_user_meta($user->ID);
					$data = array();
					$data['first_name'] = $meta['first_name'][0];
					$data['last_name'] = $meta['last_name'][0];
					$data['position'] = $meta['clubuser_position'][0];
					$data['order'] = array_search($data['position'], $positions);
					$data['street'] = $meta['clubuser_street'][0];
					$data['postal'] = $meta['clubuser_postal'][0];
					$data['city'] = $meta['clubuser_city'][0];
					$data['phone'] = $meta['clubuser_phone'][0];
					$data['position_mail'] = $meta['clubuser_mail'][0];
					$data['avatar'] = get_clubuser_avatar($user->ID);
					$userData[] = $data;
				}
			}
		}
	}
	usort($userData, "cmp_order");
	return $userData;
}
?>
