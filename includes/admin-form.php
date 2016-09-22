<?php
function cs_admin_form( $coffey_options ){
	?>
		<table class="form-table cs-settings-table">
			<tr>
				<th><label for="cs-select-style">Select style</label></th>
				<td>

					<p>
					<input type="radio" name="coffey_options[cs-select-style]" value="small-buttons" <?php checked( $coffey_options['cs-select-style'], 'small-buttons' ); ?>>
					default
					</p>


				</td>
			</tr>
			<tr>
				<th><label for="cs-select-style">Select size</label></th>
				<td>

					<p>
					<input type="radio" name="coffey_options[cs-select-size]" value="small" <?php checked( $coffey_options['cs-select-size'], 'small' ); ?>>
					small
					</p>
					<p>
					<input type="radio" name="coffey_options[cs-select-size]" value="medium" <?php checked( $coffey_options['cs-select-size'], 'medium' ); ?>>
					medium
					</p>
					<p>
					<input type="radio" name="coffey_options[cs-select-size]" value="large" <?php checked( $coffey_options['cs-select-size'], 'large' ); ?>>
					large
					</p>

				</td>
			</tr>
			<tr>
				<th><label for="cs-color-select">Select Color</label></th>
				<td>
					<p>
						<input type="checkbox" name="coffey_options[cs-color]" value="true" <?php if(!empty($coffey_options['cs-color'])) checked( in_array( 'true', (array)$coffey_options['cs-color'] ) ); ?>>
						use selected color for icons
					</p>
					<p>
						<input class="jscolor" name="coffey_options[cs-color-select]" value="<?php echo (!empty($coffey_options['cs-color-select'])) ? $coffey_options['cs-color-select'] : ''; ?>">
					</p>
				</td>
			</tr>
			<tr>
				<th><label for="cs-select-style">Select services</label></th>
				<td>
					<?php
						foreach ($coffey_options['cs-available-services'] as $service) {
							?>
							<input type="checkbox" name="coffey_options[cs-selected-services][]" value="<?php echo $service ; ?>" <?php checked( in_array( $service, (array)$coffey_options['cs-selected-services'] ) ); ?> /> <?php echo $service; ?>
							<input type="hidden" name="coffey_options[cs-available-services][]" value="<?php echo $service ; ?>" />
							<br>
							<?php
						}
					?>
				</td>
			</tr>
			<tr>
				<th><label for="cs-select-postion">Select Position</label></th>
				<td>
					<input type="checkbox" name="coffey_options[cs-select-position][]" value="after-title" <?php if(!empty($coffey_options['cs-select-position'])) checked( in_array( 'after-title', (array)$coffey_options['cs-select-position'] ) ); ?>>
					after title
					<br>
					<input type="checkbox" name="coffey_options[cs-select-position][]" value="after-content" <?php if(!empty($coffey_options['cs-select-position'])) checked( in_array( 'after-content', (array)$coffey_options['cs-select-position'] ) ); ?>>
					after content
					<br/>
					<input type="checkbox" name="coffey_options[cs-select-position][]" value="inside-featured-img" <?php if(!empty($coffey_options['cs-select-position'])) checked( in_array( 'inside-featured-img', (array)$coffey_options['cs-select-position'] ) ); ?>>
					inside featured image
					<br/>
					<input type="checkbox" name="coffey_options[cs-select-position][]" value="floating-toolbar" <?php if(!empty($coffey_options['cs-select-position'])) checked( in_array( 'floating-toolbar', (array)$coffey_options['cs-select-position'] ) ); ?>>
					floating toolbar
					<br/>

					<p>
					you can place the shortcode <code>[coffey-social-share]</code> wherever you want to display the buttons.
				</p>
				</td>
			</tr>
			<tr>
				<th><label for="cs-select-postion">Show on</label></th>
				<td>
					<input type="checkbox" name="coffey_options[cs-show-on][]" value="home" <?php checked( in_array( 'home', (array)$coffey_options['cs-show-on'] ) ); ?>>
					home page
					<br>
					<input type="checkbox" name="coffey_options[cs-show-on][]" value="pages" <?php checked( in_array( 'pages', (array)$coffey_options['cs-show-on'] ) ); ?>>
					pages
					<br>
					<input type="checkbox" name="coffey_options[cs-show-on][]" value="posts" <?php checked( in_array( 'posts', (array)$coffey_options['cs-show-on'] ) ); ?>>
					posts

					<!-- get all post types and show checkbox for them -->
					<?php
					$custom_post_types = coffey_social_share::get_registered_custom_posttypes();
						foreach ($custom_post_types as $key => $value) {
							// make the post type "pretty" for optimal user experience ( add an s at the end if term is singluar)
							$display_value = (substr($value, -1) <> 's') ? $value.'s' : $value;
							$checked = ( in_array( $value, (array)$coffey_options['cs-show-on'] ) ) ? 'checked="checked"': '';
							echo '<input type="checkbox" name="coffey_options[cs-show-on][]" value="'.$value.'" '.$checked.'>';
							echo ' ' . ucfirst($display_value) . '<br/>';
						 }
					 ?>

				</td>
			</tr>
			<tr>
				<th><label for="cs-order">Select Order</label></th>
				<td>
					<p><b>Please select a number 1-5 to rearrange icons.</b> Duplicate numbers will be ignored, as well as disabled services.</p>
					facebook <input class="cs-order-select" min="1" max="6" type="number" name="coffey_options[cs-order][facebook]" value="<?php echo (empty($coffey_options['cs-order']['facebook'])) ? 1 : $coffey_options['cs-order']['facebook']; ?>"></br>
					twitter <input class="cs-order-select" min="1" max="6" type="number" name="coffey_options[cs-order][twitter]" value="<?php echo (empty($coffey_options['cs-order']['twitter'])) ? 2 : $coffey_options['cs-order']['twitter']; ?>"></br>
					googleplus <input class="cs-order-select" min="1" max="6" type="number" name="coffey_options[cs-order][googleplus]" value="<?php echo (empty($coffey_options['cs-order']['googleplus'])) ? 3 : $coffey_options['cs-order']['googleplus']; ?>"></br>
					linkedin <input class="cs-order-select" min="1" max="6" type="number" name="coffey_options[cs-order][linkedin]" value="<?php echo (empty($coffey_options['cs-order']['linkedin'])) ? 4 : $coffey_options['cs-order']['linkedin']; ?>"></br>
					pinterest <input class="cs-order-select" min="1" max="6" type="number" name="coffey_options[cs-order][pinterest]" value="<?php echo (empty($coffey_options['cs-order']['pinterest'])) ? 5 : $coffey_options['cs-order']['pinterest']; ?>"></br>
					whatsapp <input class="cs-order-select" min="1" max="6" type="number" name="coffey_options[cs-order][whatsapp]" value="<?php echo (empty($coffey_options['cs-order']['whatsapp'])) ? 6 : $coffey_options['cs-order']['whatsapp']; ?>">
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
		</p>
	</form>
<?php
}
?>
