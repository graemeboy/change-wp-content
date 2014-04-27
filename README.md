Change wp-content
=================

A Wordpress plugin that will rename the wp-content directory to "media", which might help to improve site security.

What does the plugin do?
------------------------

For one, it adds the following definitions to your wp-config file, in the appropriate place:

```php
define ( 'WP_CONTENT_FOLDERNAME', 'media' );
define ( 'WP_CONTENT_DIR', ABSPATH . WP_CONTENT_FOLDERNAME );
define ( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] . '/' );
define ( 'WP_CONTENT_URL', WP_SITEURL . WP_CONTENT_FOLDERNAME );
```

Then, it renames your wp-content directory to "media", which ought to work now, because all of the settings that your plugins and themes use have been configured to use the "media" directory!