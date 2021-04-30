<?php
/*
 * Plugin name: WP Ping Booster
 * Description: When you publish new content or update old content on your WordPress website, the plugin sends a crawl request to Bing using the Bing Indexing API.
 * Version: 0.1
 * Author: seojacky 
 * Author URI: https://t.me/big_jacky
 * Plugin URI: https://github.com/seojacky/wp-ping-booster
 * GitHub Plugin URI: https://github.com/seojacky/wp-ping-booster
*/

add_action(
    'transition_post_status',
  /**
   * Fires actions related to the transitioning of a post's status.
   *
   * @param string  $new_status Transition to this post status.
   * @param string  $old_status Previous post status.
   * @param WP_Post $post       Post data.
   *
   * @link https://yandex.ru/dev/webmaster/doc/dg/reference/host-recrawl-post.html
   */
    function ( $new_status, $old_status, WP_Post $post ) {
      // Срабатывает только на статус publish.
      if ( 'publish' !== $new_status || 'publish' === $old_status || ! in_array( $post->post_type, [ 'post', 'movies', 'tvshows', 'seasons', 'episodes' ] ) ) {
        return;
      }

      //ping_with_yandex( $post );
      wpping_ping_with_bing( $post );
      //ping_with_google( $post );
    },
    10,
    3
);

function wpping_ping_with_bing( WP_Post $post ) {
  $token   = '83fd61b8e6cf46bea226fa4b0d4eab2d';

  $url = 'https://ssl.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey=%s';
  $url = sprintf( $url, $token );
  $args = array(
    'timeout' => 30,
    'headers' => array(
      'Content-Type'  => 'application/json',
    ),
    'body' => json_encode(
      [
        'siteUrl' => get_home_url(),
        'urlList' => [
            get_permalink( $post->ID )
                ],
      ]
    ),
  );

  $response = wp_remote_post( $url, $args );
}
