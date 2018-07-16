<?php
/**
 * Plugin Name: MC Google Analytics
 * Plugin URI: https://github.com/umdigital/mc-google-analytics/
 * Description: Basic google analytics tracking code
 * Version: 1.1.3
 * Author: U-M: Digital
 * Author URI: http://vpcomm.umich.edu
 */

define( 'MCGOOGLEANALYTICS_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

class MCGoogleAnalytics {
    static public function init()
    {
        // UPDATER SETUP
        if( !class_exists( 'WP_GitHub_Updater' ) ) {
            include_once MCGOOGLEANALYTICS_PATH .'includes'. DIRECTORY_SEPARATOR .'updater.php';
        }
        if( isset( $_GET['force-check'] ) && $_GET['force-check'] && !defined( 'WP_GITHUB_FORCE_UPDATE' ) ) {
            define( 'WP_GITHUB_FORCE_UPDATE', true );
        }
        if( is_admin() ) {
            new WP_GitHub_Updater(array(
                // this is the slug of your plugin
                'slug' => plugin_basename(__FILE__),
                // this is the name of the folder your plugin lives in
                'proper_folder_name' => dirname( plugin_basename( __FILE__ ) ),
                // the github API url of your github repo
                'api_url' => 'https://api.github.com/repos/umdigital/mc-google-analytics',
                // the github raw url of your github repo
                'raw_url' => 'https://raw.githubusercontent.com/umdigital/mc-google-analytics/master',
                // the github url of your github repo
                'github_url' => 'https://github.com/umdigital/mc-google-analytics',
                 // the zip url of the github repo
                'zip_url' => 'https://github.com/umdigital/mc-google-analytics/zipball/master',
                // wether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
                'sslverify' => true,
                // which version of WordPress does your plugin require?
                'requires' => '3.0',
                // which version of WordPress is your plugin tested up to?
                'tested' => '3.9.1',
                // which file to use as the readme for the version number
                'readme' => 'README.md',
                // Access private repositories by authorizing under Appearance > Github Updates when this example plugin is installed
                'access_token' => '',
            ));
        }

        add_action( 'admin_init', 'MCGoogleAnalytics::adminInit' );
        add_action( 'admin_menu', 'MCGoogleAnalytics::adminMenu' );

        add_filter( 'plugin_action_links_'. plugin_basename(__FILE__), 'MCGoogleAnalytics::actionLinks' );

        if( !is_admin() ) {
            add_action( 'wp_head', 'MCGoogleAnalytics::trackingCode' );
        }
    }

    static public function adminInit()
    {
        register_setting(
            'mc_google_analytics',
            'mc_ga_tracking_id'
        );

        register_setting(
            'mc_google_analytics',
            'mc_ga_events'
        );

        // default to old plugin used tracking id
        if( !get_option( 'mc_ga_tracking_id' ) && ($id = get_option( 'web_property_id' )) ) {
            if( preg_match( '/^UA-/', $id ) && is_plugin_active( 'googleanalytics/googleanalytics.php' ) ) {
                add_option( 'mc_ga_tracking_id', get_option( 'web_property_id' ) );
            }
        }
    }

    static public function adminMenu()
    {
        add_options_page(
            'Google Analytics',
            'Google Analytics',
            'administrator',
            'mc_google_analytics',
            function(){
                include MCGOOGLEANALYTICS_PATH .'templates'. DIRECTORY_SEPARATOR .'admin.tpl';
            }
        );
    }

    static public function actionLinks( $links )
    {
        return array_merge(
            $links,
            array(
                '<a href="'. admin_url( 'options-general.php?page=mc_google_analytics' ) .'">Settings</a>'
            )
        );
    }

    static public function trackingCode()
    {
        $showTrackingCode  = apply_filters( 'mc_ga_show_tracking_code', true );
        $mcGACreateOptions = apply_filters( 'mc_ga_create_options', array() );

        if( !is_array( $mcGaCreateOptions ) ) {
            $mcGACreateOptions = (object) $mcGACreateOptions;
        }

        if( $showTrackingCode && ($mcGATrackingID = get_option( 'mc_ga_tracking_id' )) ) {
            if( $mcGATrackingID != 'UA-000000-0' ) {
                $eventOpts = get_option( 'mc_ga_events', array() );

                $mcGATrackingParams = self::_merge(
                    array(
                        'email' => array(
                            'status'   => 0,
                            'category' => 'MailTo'
                        ),
                        'download' => array(
                            'status'     => 0,
                            'category'   => 'Downloads-{EXT}',
                            'extensions' => 'doc|docx|xls|xlsx|ppt|pptx|jpg|png|gif|pdf|zip|txt|mov'
                        ),
                        'external' => array(
                            'status'   => 0,
                            'category' => 'External'
                        ),
                    ),
                    $eventOpts 
                );

                foreach( $mcGATrackingParams as $type => $event ) {
                    if( $event['status'] ) {
                        wp_enqueue_script(
                            'mc-google-analytics',
                            plugins_url('mc-google-analytics.js', __FILE__),
                            array(),
                            '1.0',
                            true
                        );
                        break;
                    }
                }

                include MCGOOGLEANALYTICS_PATH .'templates'. DIRECTORY_SEPARATOR .'tracking-code.tpl';
            }
        }
    }

    static private function _merge( $default, $changes )
    {
        foreach( $changes as $key => $val ) {
            foreach( $val as $skey => $sval ) {
                switch( $skey ) {
                    case 'status':
                        $sval = (int) $sval;
                        break;

                    case 'extensions':
                        if( $exts = explode( ',', $sval ) ) {
                            $exts = array_map( 'trim', $exts );
                            $sval = implode( '|', $exts );
                        }
                        else {
                            $exts = explode( '|', $sval );
                            $exts = array_map( 'trim', $exts );
                            $sval = implode( '|', $exts );
                        }
                        break;

                    default:
                        break;
                }

                if( strlen( $sval ) ) {
                    $default[ $key ][ $skey ] = $sval;
                }
            }
        }

        return $default;
    }
}
MCGoogleAnalytics::init();
