<?php
/**
 * groups-list-users.php
 *
 * Copyright (c) 2015 www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author itthinx
 * @package groups-list-users
 * @since 1.0.0
 *
 * Plugin Name: Groups List Users
 * Plugin URI: http://www.itthinx.com/plugins/documentation
 * Description: An example shortcode implementation for <a href="http://www.itthinx.com/plugins/groups/">Groups</a>. List users in a group using the [groups_list_users group="Example"] shortcode.
 * Version: 1.0.0
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 * Donate-Link: http://www.itthinx.com
 * License: GPLv3
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists('Groups_List_Users' ) ) {

	/**
	 * Provides the [groups_list_users] shortcode.
	 */
	class Groups_List_Users {

		/**
		 * Registers the groups_list_users shortcode handler.
		 */
		public static function init() {
			if ( !shortcode_exists( 'groups_list_users' ) ) {
				add_shortcode('groups_list_users', array( __CLASS__, 'list_users' ) );
			}
		}

		/**
		 * Handles the groups_list_users shortcode.
		 * 
		 * @param array $atts
		 * @param string $content
		 */
		public static function list_users( $atts = array(), $content = '' ) {
			$output = '';
			$atts = shortcode_atts(
				array(
					'group' => null,
				),
				$atts
			);
			$group_name = !empty( $atts['group'] ) ? trim( $atts['group'] ) : null;
			if ( !empty( $group_name ) ) {
				if ( class_exists( 'Groups_Group' ) ) {
					if ( $group = Groups_Group::read_by_name( $group_name ) ) {
						if ( !$group instanceof Groups_Group ) {
							$group = new Groups_Group( $group->group_id );
						}
						$users = $group->users;
						if ( !empty( $users ) ) {
							$output .= '<ul>';
							foreach( $users as $user ) {
								$output .= '<li>';
								$output .=  wp_filter_nohtml_kses( $user->user_login );
								$output .= '</li>';
							}
							$output .= '</ul>';
						}
					}
				}
			}
			return $output;
		}
	}
	Groups_List_Users::init();
}
