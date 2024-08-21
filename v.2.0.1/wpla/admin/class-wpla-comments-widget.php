<?php
/**
 * Register a dashboard widget to display live comments.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Wpla
 * @subpackage Wpla/admin
 */

class Wpla_Comments_Widget {

    /**
     * Register the widget with the WordPress dashboard.
     */
    public function register_dashboard_widget() {
        wp_add_dashboard_widget(
            'wpla_comments_widget',
            __( 'Live Comments', 'wpla' ),
            array( $this, 'wpla_display_comments_widget' )
        );
    }

    /**
     * Display the content of the widget.
     */
    public function wpla_display_comments_widget() {
        $comments_instance = new Wpla_Comments();
        $comments = $comments_instance->fetch_recent_comments();

        if ( ! empty( $comments ) ) {
            echo '<ul>';
            foreach ( $comments as $comment ) {
                echo '<li>';
                echo get_avatar( $comment, 32 ); // Display the avatar of the commenter.
                echo '<strong>' . esc_html( $comment->comment_author ) . ':</strong> ';
                echo esc_html( wp_trim_words( $comment->comment_content, 10 ) ); // Display a snippet of the comment.
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . __( 'No recent comments found.', 'plugin-name' ) . '</p>';
        }
    }

}

