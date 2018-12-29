<?php
/**
 * The template for displaying wardrobe.
 *
 */

get_header("wardrobe"); ?>

    <div id="primary" class="content-area wardrobe">
        <main id="main" class="site-main center hover" role="main">

                 <?php while ( have_posts() ) : the_post();
                 echo "<br><h2>";
                the_title();
                echo "</h2><br>";
                echo "<h3>Click the desired product</h3>";
                $topProducts = get_post_meta($post->ID, 'wardrobe_products_top', true);
  ?>

                <div class="w3-content w3-display-container">
<?php
                foreach ($topProducts as $topProductID) {

                    $wwthumbnailtop = wp_get_attachment_image_url(get_post_thumbnail_id($topProductID));
                    $wwthumbnailtopfull = wp_get_attachment_image_url(get_post_thumbnail_id($topProductID), "full");
                    if (!empty($wwthumbnailtop)) {
                    ?>


<div class="w3-display-container mySlides mySlidesjs">
      <?php
      $productTop = wc_get_product( $topProductID );
      $topPrice = wc_price($productTop->get_price());
      echo $topPrice;
      ?>
    <a href="<?php echo $productTop->add_to_cart_url();?>">Tilføj produkt</a>
    <?php
      echo '<p class="producttext">' . get_the_title($topProductID) . '<br></p>';

      echo "<br>";

      ?>
                        <img src="<?php echo $wwthumbnailtop ?>" class="topImage hvr-pulse" alt="" data-rawPrice="<?php echo $productTop->get_price() ?>" data-fullImg="<?php echo $wwthumbnailtopfull ?>">
</div>

<?php
                    }

                }

?>
                    <button class="w3-button w3-display-left w3-black" onclick="plusDivs(-1)">&#10094;</button>
                    <button class="w3-button w3-display-right w3-black" onclick="plusDivs(1)">&#10095;</button>
                </div>


            <!-- Show full image -->

            <figure>  <img class="presentTop automargin" id="fulltop" src="" alt=""></figure>
      <figure>  <img class="presentBottom automargin" id="fullbottom" src="" alt=""></figure>

            <div class="w3-content w3-display-container">
                 <?php
                $bottomProducts = get_post_meta($post->ID, 'wardrobe_products_bottom', true);
                foreach ($bottomProducts as $bottomProductID) {
                    $wwthumbnailbottom = wp_get_attachment_image_url(get_post_thumbnail_id($bottomProductID));
                    if (!empty($wwthumbnailbottom)) {
                    ?>
                        <div class="w3-display-container mySlides mySlidesTwo">
                            <?php
                            $productBottom = wc_get_product( $bottomProductID );
                            $bottomPrice = wc_price($productBottom->get_price());
                            echo $bottomPrice;
                            $wwthumbnailbottomfull = wp_get_attachment_image_url(get_post_thumbnail_id($bottomProductID), "full");
                            ?>

                            <a href="<?php echo $productBottom->add_to_cart_url();?>">Tilføj produkt</a>
                        <?php  echo '<p class="producttext">' . get_the_title($bottomProductID) . '</p><br>'; ?>
                            <img src="<?php echo $wwthumbnailbottom ?>" class="bottomImage hvr-pulse" alt="" data-rawPrice="<?php echo $productBottom->get_price() ?>" data-fullImg="<?php echo $wwthumbnailbottomfull ?>">
                        </div>
                            <?php
                    }
                }
?>
                <button class="w3-button w3-display-left w3-black" onclick="plusDivsTwo(-1)">&#10094;</button>
                <button class="w3-button w3-display-right w3-black" onclick="plusDivsTwo(1)">&#10095;</button>
            </div>

<?php
            endwhile; // End of the loop.

                 ?>
            <h3>Top product price:<span id="topPrice"></span></h3>
            <h3>Bottom product price:<span id="bottomPrice"></span></h3>
            <h3>Total Price:<span id="totalPrice"></span></h3>

       </div>
    <script type="text/javascript">
        var topPrice = 0;
        var bottomPrice = 0;
        document.addEventListener('click', function (event) {

            // If the clicked element doesn't have the right selector, bail
            if (!event.target.matches('.topImage')) return;
            // Don't follow the link
            event.preventDefault();

            // Log the clicked element in the console
            document.getElementById("fulltop").src=event.target.getAttribute("data-fullImg");

            //Change Price
            topPrice = parseInt(event.target.getAttribute("data-rawPrice"));
            document.getElementById("topPrice").innerHTML=topPrice;
            document.getElementById("totalPrice").innerHTML=topPrice + bottomPrice;
        }, false);

        document.addEventListener('click', function (event) {

            // If the clicked element doesn't have the right selector, bail
            if (!event.target.matches('.bottomImage')) return;
            console.log(event);
            // Don't follow the link
            event.preventDefault();
            console.log(event.target.src);
            // Log the clicked element in the console

            document.getElementById("fullbottom").src=event.target.getAttribute("data-fullImg");

            //Change Price
            bottomPrice = parseInt(event.target.getAttribute("data-rawPrice"));
            document.getElementById("bottomPrice").innerHTML=bottomPrice;
            document.getElementById("totalPrice").innerHTML=topPrice + bottomPrice;

        }, false);
    </script>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer("wardrobe");
