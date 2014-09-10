<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package sfpaper
 * @since sfpaper 1.0
 */
?>

	</div><!-- #main .site-main -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'sfpaper_credits' ); ?>
			<?php if(get_theme_mod('footer_credits') <> "") {echo get_theme_mod('footer_credits');}
	else {?>
			<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'sfpaper' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'sfpaper' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Design by %1$s.', 'sfpaper' ), '<a href="http://storefrontthemes.com" rel="designer">Storefront Themes</a>' ); ?>
			<?php } ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>