<?php
/**
 * Related posts helper class
 *
 * @package RelatedPosts
 */

namespace Nilambar\WPRelatedPosts;

/**
 * Related Posts class.
 *
 * @since 1.0.0
 */
class RelatedPosts {

  /**
   * Get related posts.
   *
   * @since 1.0.0
   *
   * @param int   $post_id Post ID.
   * @param array $args Related posts arguments.
   * @return array Aray of related posts IDS or objects.
   */
  public static function posts( $post_id, $args = array() ) {
    $output = array();

    $post_id = absint( $post_id );

    // Bail if not valid ID.
    if ( 0 === $post_id ) {
      return $output;
    }

    // Defaults.
    $defaults = array(
      'taxonomies'        => array(),
      'taxonomy_relation' => 'OR',
      'number'            => 3,
      'orderby'           => 'date',
      'order'             => 'desc',
      'same_author'       => false,
      'exclude_ids'       => array(),
      'fields'            => 'all',
      'older_posts_only'  => false,
    );

    $args = wp_parse_args( $args, $defaults );

    // Exclude IDs.
    $exclude_ids = array_merge( $args['exclude_ids'], array( $post_id ) );

    // Query arguments.
    $qargs = array(
      'post_type'           => get_post_type( $post_id ),
      'post_status'         => 'publish',
      'posts_per_page'      => absint( $args['number'] ),
      'post__not_in'        => $exclude_ids,
      'orderby'             => $args['orderby'],
      'order'               => $args['order'],
      'fields'              => $args['fields'],
      'ignore_sticky_posts' => true,
      'no_found_rows'       => true,
      'cache_results'       => false,
    );

    if ( true === $args['same_author'] ) {
      $author_id       = get_post_field( 'post_author', $post_id );
      $qargs['author'] = absint( $author_id );
    }

    if ( true === $args['older_posts_only'] ) {
      $qargs['date_query'] = array(
        array(
          'before' => get_the_time( 'Y-m-d', $post_id ),
        ),
      );
    }

    $tq_elements = array();

    if ( is_array( $args['taxonomies'] ) && ! empty( $args['taxonomies'] ) ) {

      foreach ( $args['taxonomies'] as $tax ) {
        $post_taxes = get_the_terms( $post_id, $tax );

        $tax_ids = array();

        if ( ! empty( $post_taxes ) && ! is_wp_error( $post_taxes ) ) {
          $tax_ids = wp_list_pluck( $post_taxes, 'term_id' );
        }

        if ( ! empty( $tax_ids ) ) {
          $tq_elements[] = array(
            'taxonomy' => $tax,
            'field'    => 'term_id',
            'terms'    => $tax_ids,
          );
        }
      }
    }

    if ( ! empty( $tq_elements ) ) {
      $qargs['tax_query'] = array_merge( array( 'relation' => $args['taxonomy_relation'] ), $tq_elements );
    }

    return get_posts( $qargs );
  }
}
