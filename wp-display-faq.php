<?php
/**
 * Plugin Name:     DisplayFaq
 * Plugin URI:      http://wordpress.org/plugins/wp-display-faq/
 * Description:     Accordion FAQs Plugin for WordPress to display FAQs with Category in your website.
 * Version: 	    1.4.3
 * Author:          HM Plugin
 * Author URI:      https://hmplugin.com
 * Requires at least:   5.2
 * Requires PHP:    7.2
 * Tested up to:    6.2.2
 * Text Domain:     wp-display-faq
 * Domain Path:     /languages/
 * License:		    GPL-2.0+
 * License URI:	    http://www.gnu.org/licenses/gpl-2.0.txt
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
  }
  
  if ( function_exists( 'wdf_fs ' ) ) {
  
    wdf_fs ()->set_basename( false, __FILE__ );
  
  } else {
  
    if ( ! class_exists('WFP_Master') ) {

        define('WFP_PATH', plugin_dir_path(__FILE__));
        define('WFP_ASSETS', plugins_url('/assets/', __FILE__));
        define('WFP_SLUG', plugin_basename(__FILE__));
        define('WFP_PRFX', 'wfp_');
        define('WFP_CLS_PRFX', 'cls-wfp-');
        define('WFP_TXT_DOMAIN', 'wp-display-faq');
        define('WFP_VERSION', '1.4.3');

        require_once WFP_PATH . '/lib/freemius-integrator.php';
        require_once WFP_PATH . 'inc/' . WFP_CLS_PRFX . 'master.php';
        $wfp = new WFP_Master();
        $wfp->wfp_run();


        // Add Data To Custom Post Type Columns
        function wfp_faq_column_data( $column, $post_id ) {

            switch ( $column ) {

                case 'status':
                    echo ( 'active' !== get_post_meta( $post_id , 'wfp_status' , true ) ) ? '<b style="color:red;">' . __('Inactive', WFP_TXT_DOMAIN) . '</b>' : '<b style="color:green;">' . __('Active', WFP_TXT_DOMAIN) . '</b>';
                    break;

            }
            
        }
        add_action( 'manage_wfp_faq_posts_custom_column' , 'wfp_faq_column_data', 10, 2 );

        // Sorting Column
        function wfp_list_table_sorting( $columns ) {

            $columns['status'] = 'Status';
            return $columns;

        }
        add_filter( 'manage_edit-wfp_faq_sortable_columns', 'wfp_list_table_sorting' );

        register_deactivation_hook( __FILE__, array( $wfp, WFP_PRFX . 'unregister_settings' ) );
    
    }
}