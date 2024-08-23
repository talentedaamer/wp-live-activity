<?php

/**
 * Comments class to handle live comments
 *
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
	 * The main configurations of the plugin
	 *
	 * @since    1.0.0
	 * @access   private 
	 * @var      array    the saved plugin configurations.
	 */
	private  $config;

    /**
	 * Initialize the class and load configurations
	 *
	 * @since    1.0.0
	 */
	public function __construct( $config ) {
		$this->config = $config;
	}
    
    /**
     * Fetch recent comments.
     * 
     * @since    1.0.0
     * @return array
     */
    public function fetch_recent_comments() {
        $live_comments = get_transient($this->config->get('cache_key_comments'));
        if ($live_comments === false) {
            $args = array(
                // Comment status (approve, hold, spam, trash, post-trashed, all)
                'status' => array('approve', 'hold', 'spam'),
                'number' => apply_filters( 'wpla_filter_number_comments', 5 ),
                'orderby' => 'comment_date',
                'order' => 'DESC',
                // Type of comment (comment, pingback, trackback)
                'type' => 'comment',
            );
            $comments = get_comments( $args );
            $live_comments = array();
            if ( ! empty( $comments ) ) {
                foreach ( $comments as $comment ) {
                    $comment_date = date_i18n($this->config->get('site_date_format'), strtotime($comment->comment_date_gmt));
                    $comment_time = date_i18n($this->config->get('site_time_format'), strtotime($comment->comment_date_gmt));
                    $comment_datetime = $comment_date . ' ' . $comment_time;

                    $comment_content = wp_strip_all_tags($comment->comment_content);
                    $live_comments[] = array(
                        'ID' => $comment->comment_ID,
                        'comment_status' => $comment->comment_approved,
                        'author' => $this->wpla_trim_string($comment->comment_author, 30, '..'),
                        'author_email' => $this->wpla_trim_string($comment->comment_author_email, 30, '..'),
                        'author_avatar' => get_avatar( $comment->comment_author_email, apply_filters( 'wpla_comment_user_avatar_size', 40 ) ),
                        'content' => $this->wpla_trim_string($comment_content, 120, '..'),
                        'date' => $comment->comment_date,
                        'date_gmt' => $comment->comment_date_gmt,
                        'date_time' => $comment_datetime,
                        'parent' => $comment->comment_parent,
                        'post_ID' => $comment->comment_post_ID,
                        'post_title' => $this->wpla_trim_string(get_the_title($comment->comment_post_ID), 40, '..'),
                        'type' => $comment->comment_type,
                    );
                }
            }

            set_transient($this->config->get('cache_key_comments'), $live_comments, $this->config->get('cache_expiry_comments'));
        }

        return $live_comments;
    }

    /**
     * Trim string to specific length
     * 
     * @since    1.0.0
     * 
     * @param   string  $content    required. content to be trimmed.
     * @param   string  $length     optional. length of the trimmed content.
     * @return  string              trimmed content to specific length with elipsis.
     */
    public function wpla_trim_string($content, $length = 10) {
        if (strlen($content) > $length) {
            return  substr($content, 0, $length) . '...';
        } else {
            return $content;
        }
    }

}
