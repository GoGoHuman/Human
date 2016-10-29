<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package foodbiz
 */
if (is_active_sidebar('sidebar-1')) {
            ?>



            <div id="secondary" class="widget-area left_bar" role="complementary">
                        <?php dynamic_sidebar('sidebar-1'); ?>
            </div><!-- #secondary -->

            <?php
}
?>
