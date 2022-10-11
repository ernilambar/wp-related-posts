# WP Related Posts

WordPress related posts helper class to fetch related posts based on post's taxonomies.

## Requirements

* WordPress 5.1+.
* PHP 5.6+.
* [Composer](https://getcomposer.org/) for managing PHP dependencies.

## Installation

First, you'll need to open your command line tool and change directories to your theme folder.

```bash
cd path/to/wp-content/themes/<your-theme-name>
```

Then, use Composer to install the package.

```bash
composer require ernilambar/wp-related-posts
```

Assuming you're not already including the Composer autoload file for your theme and are shipping this as part of your theme package, you'll want something like the following bit of code in your theme's `functions.php` to autoload this package (and any others).

The Composer autoload file will automatically load up the package for you and make its code available for you to use.

```php
if ( file_exists( get_parent_theme_file_path( 'vendor/autoload.php' ) ) ) {
  require_once get_parent_theme_file_path( 'vendor/autoload.php' );
}
```

## Usage

You can fetch related post using `posts` method.

```php
use Nilambar\WPRelatedPosts\RelatedPosts;

$args = array(
  'number'     => 3,
  'taxonomies' => array( 'post_tag' ),
);

$related_posts = RelatedPosts::posts( get_the_ID(), $args );
```

## Copyright and License

This project is licensed under the [MIT](http://opensource.org/licenses/MIT).

2022 &copy; [Nilambar Sharma](https://www.nilambar.net).
