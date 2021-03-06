
<?php
/*
  This template is called from 404.php as part of the workaround to have both:

  - a custom taxonomy "root" page to serve up all top-level $terms (i.e. this template)
      * this root page will resolve as /artwork/gallery/
      * all taxonomy term categories will act as gallery directories (e.g. /artwork/gallery/sculpture/)

  - an actual post type archive page for "artwork"
      * this archive page will resolve as /artwork/
      * this is a dump of all artwork items, and will be searchable
      * on-page filters will include tags, medium, categories, year, etc
*/
?>

<header class="page-header heading heading--centered">
  <h1 class="heading">Gallery of Work</h1>
  <hr class="heading__line" />
</header><!-- .page-header -->


<?php

$terms = get_terms( array(
  'taxonomy' => 'gallery',
  'hide_empty' => 1,
  'childless' => false,		// includes top-level categories that have subcategories
  'parent' => 0, 					// returns only top-level categories
  'orderby' => 'description',
  'order' => 'asc'
  )
);

$itemCount = 0;

foreach ( $terms as $childTerm ) {
/*

  Displaying gallery thumbs as inline-block divs allows them to naturally
  flow from one line to the next, obviating the need to row/column logic.

*/
  echo '<div class="gallery-index-item">';

    $image = get_field('term_image', $childTerm);

    $label = $childTerm->name;

    if ($childTerm->parent) {
      $childOf = get_term($childTerm->parent);
      $label = $childOf->name . '<hr>' . $label;
    }

    $galleryThumbURL = $image['sizes']['gallery-category'];

    $imgTag = '<img src="' . $galleryThumbURL . '" alt="' . $label . '" />';

    printf( '<a href="%1$s" title="View Gallery Category - %2$s">%3$s</a>',
        esc_url( get_term_link( $childTerm->term_id ) ),
        $label,
        $imgTag
    );

    ?>

    <a href="<?php echo esc_url( get_term_link( $childTerm->term_id ) ) ?>"
       title="View Gallery Category - <?php echo $label; ?>">
      <div class="gallery-index-item__shadow-overlay">
        <div class="gallery-index-item__text-content">
          <?php	echo $label; ?>
        </div>
      </div>
    </a>

    <?php

    echo '</div>';

}  //for each item in gallery

// BELOW: One last card for the "Explore All Work" section, if JS is enabled
?>

<div class="gallery-index-item js-dependent">

  <?php

    $allWorkImage = get_field('all_work_thumbnail', 'options');
    $allWorkImageURL = $allWorkImage['sizes']['gallery-category'];

    $imgTag = '<img src="' . $allWorkImageURL . '" alt="Explore All Work" />';

    printf( '<a href="%1$s" title="View Gallery Category - %2$s">%3$s</a>',
        esc_url( get_post_type_archive_link( 'artwork' ) ),
        'Explore All Work',
        $imgTag
    );
  ?>

  <a href="<?php echo esc_url( get_post_type_archive_link( 'artwork' ) ) ?>"
     title="Explore All Work">
    <div class="gallery-index-item__shadow-overlay">
      <div class="gallery-index-item__text-content">
        Explore All Work
      </div>
    </div>
  </a>

</div>
