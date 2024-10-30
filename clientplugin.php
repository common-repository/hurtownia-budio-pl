<?php
/**
 * Plugin Name: Hurtownia - Budio.pl
 * Plugin URI: https://github.com/budiopl/ClientPlugin
 * Description: Umieszcza link do twojej hurtowni w stopce strony.
 * Version: 1.1.2
 * Author: Budio.pl
 * Author URI: https://budio.pl
 * License: GPL2
 */


add_action('wp_head', 'cphb_add_style');
function cphb_add_style()
{
      wp_enqueue_style( 'clientplugin-style', plugins_url( 'css/clientplugin.css', __FILE__ ), false );
}


add_action( 'wp_enqueue_scripts', 'cphb_add_google_fonts' );
function cphb_add_google_fonts()
{
    wp_enqueue_style( 'wpb-google-fonts', '//fonts.googleapis.com/css?family=Roboto:300&display=swap&subset=latin-ext', false );
}

function cphb_get_itnavigator_data()
{
    $domain = parse_url(get_site_url());
    $response = wp_remote_get( 'https://itnavigator.budio.pl:444/wp-plugin-data?url='.$domain['host'] );
    if(!is_wp_error($response))
    {
          $dataAPI = json_decode($response['body']);
          update_option('clientplugin_data', $dataAPI);
          return $dataAPI;
    }
}


add_action( 'wp_footer', 'cphb_add_signature' );
function cphb_add_signature ()
{

    $data = get_option('clientplugin_data') ?? cphb_get_itnavigator_data();
    if(!empty($data))
    {
          echo '<div class="budio-partner-container">
                 <div class="budio-logo-container">
                     <div class="head">Jesteśmy częścią</div>
                     <a href="https://budio.pl/" target="_blank" class="ext-link grupa-link" rel="nofollow"><img src="'.plugins_url( 'image/budiopl-logo.svg', __FILE__ ).'" alt="Budio.pl"></a>';

          if(!is_null($data->wholesale_link))
          {
             echo '<div class="links">
                 <a href="'.$data->wholesale_link.'" target="_blank">Zobacz naszą ofertę</a>
             </div>';
          }

          echo '</div>
                 <div class="budio-apps-container">
                     <div class="head">Darmowa aplikacja mobilna dla Wykonawców</div>
                     <div class="badges">
                         <a href="'.$data->android_app_link.'" target="_blank" rel="nofollow" class="ext-link"><img src="'.plugins_url( 'image/googleplay.png', __FILE__ ).'"></a>
                         <a href="'.$data->ios_app_link.'" target="_blank" rel="nofollow" class="ext-link"><img src="'.plugins_url( 'image/appstore.png', __FILE__ ).'"></a>
                     </div>
                 </div>
             </div>';
    }
 }
