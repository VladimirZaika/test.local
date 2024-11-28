<?php
/**
 * The footer
 *
 * @package Movies
 */

$insertAfterFooterCode = get_field('insert_after_footer_code', 'options');
$footerLogo = get_field('footer_logo', 'options');
$copyRight = get_field('footer_text', 'options');
?>
<?php get_sidebar(); ?>
            <!-- Main end -->
            </main>

            <footer class="footer">
                <div class="container footer-container">
                    <?php if ( !empty($footerLogo) ): ?>
                        <div class="img-wrapper">
                            <picture>
                                <source srcset="<?php echo esc_url($footerLogo['sizes']['medium']); ?>" media="(max-width: 767px)">
                                <source srcset="<?php echo esc_url($footerLogo['sizes']['large']); ?>" media="(min-width: 768px)">
                                <img src="<?php echo esc_url($footerLogo['sizes']['large']); ?>" alt="<?php echo esc_attr($footerLogo['alt']); ?>">
                            </picture>
                        </div>
                    <?php endif;

                    if ( has_nav_menu( 'primary' ) ): ?>
                        <div class="navigation-wrapper">
                            <?php
                                get_template_part( 'template-parts/navigation' );
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="copyright-wrapper">
                        <?php if ( !empty($copyRight) ): ?>
                            <div class="copyright-item">
                                <span><?php echo $copyRight; ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="copyright-item">
                            <span><?php _e('Made with love by VZ'); ?></span>
                        </div>
                    </div>
                </div>
            </footer>
        <!-- Wrapper End -->
        </div>

        <?php
            echo $insertAfterFooterCode;

            wp_footer();
        ?>
    </body>
</html>