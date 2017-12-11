<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "body-content-wrapper" div and all content after.
 *
 * @subpackage fCorpo
 * @author tishonator
 * @since fCorpo 1.0.0
 *
 */
?>
			<a href="#" class="scrollup"></a>

			<footer id="footer-main">

				<div id="footer-content-wrapper">

					<?php get_sidebar('footer'); ?>

					<div class="clear">
					</div>

					<div id="copyright">

						<p>
						 <?php fcorpo_show_copyright_text(); ?> <a href="<?php echo esc_url( 'https://tishonator.com/product/fcorpo' ); ?>" title="<?php esc_attr_e( 'fcorpo Theme', 'fcorpo' ); ?>">
							<?php esc_html_e('fCorpo Theme', 'fcorpo'); ?></a> <?php esc_attr_e( 'powered by', 'fcorpo' ); ?> <a href="<?php echo esc_url( 'http://wordpress.org/' ); ?>" title="<?php esc_attr_e( 'WordPress', 'fcorpo' ); ?>">
							<?php esc_html_e('WordPress', 'fcorpo'); ?></a>
						</p>
						
					</div><!-- #copyright -->

				</div><!-- #footer-content-wrapper -->

			</footer><!-- #footer-main -->

		</div><!-- #body-content-wrapper -->
		<?php wp_footer(); ?>
	</body>
</html>