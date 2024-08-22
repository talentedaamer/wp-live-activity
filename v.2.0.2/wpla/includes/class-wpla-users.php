<?php

/**
 * Users class to handle live users
 *
 * @since      1.0.0
 *
 * @package    Wpla
 * @subpackage Wpla/includes
 */

/**
 * Main users class
 *
 * This class defines the live users feature
 * and widget on the dashboard Showing live users activity
 *
 * @since      1.0.0
 * @package    Wpla
 * @subpackage Wpla/includes
 * @author     Aamer <talentedaamer@gmail.com>
 */
class Wpla_Users {

    /**
     * Fetch recent comments.
     * 
     * @since    1.0.0
     * @return array
     */
    public function fetch_recent_users() {
        $args = array(
            'number' => 5, // Number of comments to retrieve
            'status' => 'approve', // Only approved comments
            'post_status' => 'publish', // Only comments from published posts
        );

        $users = get_comments( $args );

        return $users;
    }

}
