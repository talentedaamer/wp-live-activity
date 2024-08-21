<?php

/**
 * Comments class to handle live comments
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Wpla
 * @subpackage Wpla/includes
 */

/**
 * Main comments class
 *
 * This class defines the live comments feature
 * and widget on the dashboard Showing live comments
 *
 * @since      1.0.0
 * @package    Wpla
 * @subpackage Wpla/includes
 * @author     Aamer <talentedaamer@gmail.com>
 */
class Wpla_Comments {

    /**
     * Fetch recent comments.
     * 
     * @since    1.0.0
     * @return array
     */
    public function fetch_recent_comments() {
        $args = array(
            'number' => 5, // Number of comments to retrieve
            'status' => 'approve', // Only approved comments
            'post_status' => 'publish', // Only comments from published posts
        );

        $comments = get_comments( $args );

        return $comments;
    }

}
