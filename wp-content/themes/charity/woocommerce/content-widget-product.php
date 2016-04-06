<?php global $product; ?>
<li><a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>"
	title="<?php echo esc_attr( $product->get_title() ); ?>"
	class="pull-left">
		<figure><?php echo $product->get_image(); ?></figure>
</a>
	<div class="media-body">
		<p>
			<a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>"><?php echo $product->get_title(); ?></a>
		</p>
	<?php if ( ! empty( $show_rating ) ) echo $product->get_rating_html(); ?>
	<span class="price"><?php echo $product->get_price_html(); ?></span>
	</div>
	
</li>