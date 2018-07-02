Google Analytics Tracker
========================
Provides easy way to add google analytics tracking with just your tracking ID.  No need to connect to your google account.

### Filters
**mc_ga_show_tracking_code**

This will allow the tracking to be enabled (true, default) or disabled (false).

Example to disable tracking via filter.  This must happen before wp_head is called.
```
add_filter( 'mc_ga_show_tracking_code', function( $status ){
    return false;
});
```
