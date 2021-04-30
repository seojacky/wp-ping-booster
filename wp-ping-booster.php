<?php
/*
 * Plugin name: WP Ping Booster
 * Description: When you publish new content or update old content on your WordPress website, the plugin sends a crawl request to Bing using the Bing Indexing API.
 * Version: 0.3
 * Author: seojacky 
 * Author URI: https://t.me/big_jacky
 * Plugin URI: https://github.com/seojacky/wp-ping-booster
 * GitHub Plugin URI: https://github.com/seojacky/wp-ping-booster
*/

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {	return;}

define('WPPING_FILE', __FILE__); // url of the file directory
define('WPPING_DIR', __DIR__); // url plugins folder /var/www/...
define('WPPING_FOLDER', trailingslashit( plugin_dir_url(__FILE__) ) ); // url plugins folder http://.../wp-content/plugins/true-lazy-analytics
define('WPPING_SLUG', 'wp-ping-booster');

/* Plugin settings links  */
add_filter('plugin_action_links_'.plugin_basename(WPPING_FILE), function ( $links ) {
	$links[] = '<a href="' .		
		admin_url( 'admin.php?page='. WPPING_SLUG ) .
		'">' . __('Settings') . '</a>';
	$links[] = '<a href="https://t.me/big_jacky">' . __('Author') . '</a>';
	return $links;
});

/*
 *****************************************************************
  Создаем страницу настроек плагина
****************************************************************
*/
/* Добавляем  с проверкой типовой пункт меню  SEO Boost */
add_action('admin_menu', 'wpping_creat_admin_page', 8, 0);

function wpping_creat_admin_page(){	
	global $admin_page_hooks;	
	if (isset($admin_page_hooks['wp-booster'])  ) {
		return;
	}
	
add_menu_page(
        esc_html__('WP Booster', WPPING_SLUG),
        esc_html_x('WP Booster', 'Menu item', WPPING_SLUG),
		'manage_options',
		'wp-booster',
		'wpping_options_page_output',
		'dashicons-backup',
		92.3 
            );
}	

add_action('admin_head', function(){
  	echo '<style>
    .toplevel_page_wp-booster li.wp-first-item {
    display: none;}
  </style>';
});

	add_action('admin_menu', function(){
	$submenu = add_submenu_page(
	'wp-booster',
	'Настройки плагина WP Ping Booster',
	'WP Ping Booster',
	'manage_options',
	'wp-ping-booster',
	'wpping_options_page_output'
	);

}, 99 );

function wpping_options_page_output(){
	?>
<div class="wrap">    
      <h1  style="display:inline;">WP Ping Booster</h1></span> 
   		<h2 class="nav-tab-wrapper"></h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">

					<div class="postbox">						

						<div class="inside">
		       	 <form method="post" action="options.php">
                     			<?php
				settings_fields( 'wpping_option_group' );     // скрытые защитные поля
				do_settings_sections( 'wpping_page' ); // секции с настройками (опциями). У нас она всего одна 'section_id'
				submit_button();
			?>
			</form>
							</div>
						<!-- .inside -->
					</div>
					<!-- .postbox -->
				</div>
				<!-- .meta-box-sortables .ui-sortable -->
			</div>
			<!-- post-body-content -->
			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox">
					<h4><?php _e( 'About plugin', 'wp-ping-booster' ); ?></h4>
						<div class="inside">
			<a href="https://wordpress.org/plugins/wp-ping-booster/#faq" target="_blank"><?php _e( 'FAQ', 'wp-ping-booster' ); ?></a>
			<br />
			<a href="https://wordpress.org/support/plugin/wp-ping-booster/" target="_blank"><?php _e( 'Community Support', 'wp-ping-booster' ); ?></a>
			<br />
			<a href="https://wordpress.org/support/plugin/wp-ping-booster/reviews/#new-post" target="_blank"><?php _e( 'Review this plugin', 'wp-ping-booster' ); ?></a>
			<br />
			<?php echo " <span class='rating-stars'><a href='//wordpress.org/support/plugin/wp-ping-booster/reviews/?rate=1#new-post' target='_blank' data-rating='1' title='" . __('Poor', 'wp-ping-booster') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/wp-ping-booster/reviews/?rate=2#new-post' target='_blank' data-rating='2' title='" . __('Works', 'wp-ping-booster') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/wp-ping-booster/reviews/?rate=3#new-post' target='_blank' data-rating='3' title='" . __('Good', 'wp-ping-booster') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/wp-ping-booster/reviews/?rate=4#new-post' target='_blank' data-rating='4' title='" . __('Great', 'wp-ping-booster') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><a href='//wordpress.org/support/plugin/wp-ping-booster/reviews/?rate=5#new-post' target='_blank' data-rating='5' title='" . __('Fantastic!', 'wp-ping-booster') . "'><span class='dashicons dashicons-star-filled' style='color:#ffb900 !important;'></span></a><span>"; ?>			
				</div>
						<!-- .inside -->
					</div>
					<!-- .postbox -->
				</div>
				<!-- .meta-box-sortables -->
			</div>
			<!-- #postbox-container-1 .postbox-container -->
		</div>
		<!-- #post-body .metabox-holder .columns-2 -->
		<br class="clear">
	</div>
	<!-- #poststuff -->
</div> <!-- .wrap -->
	<?php
}

add_action('admin_init', 'wpping_plugin_settings');
function wpping_plugin_settings(){
		register_setting( 
		'wpping_add_option', // Option group
		'wpping_add_option', // Option name
		'wpping_sanitize_callback' // Sanitize
	);
	add_settings_section(
		'setting_section_id', // ID
		esc_html__('Settings', WPPING_SLUG), // Title
		'', // Callback
		'wpping_page' // Page
	);
	
	add_settings_field(
		'bing_token',
		esc_html__('Bing token', WPPING__SLUG),
		'wpping_fill_bing_token',
		'wpping_page', // Page
		'setting_section_id' // ID
	);
}

## fill option exclude page
function wpping_fill_bing_token(){
	?>
<span><input size="80" type="text" name="wpping_add_option[bing_token]" value="" placeholder="<?php echo __('Bing token', 'true-lazy-analytics'); ?>"  />&#9;</span>
<?php
}

## sanitize
function wpping_sanitize_callback( $options ){ 
	// очищаем
	foreach( $options as $name => & $val ){
		
		if( $name == 'bing_token' )			
		$val = htmlspecialchars($val, ENT_QUOTES);		

	}
	return $options;
}


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
