<?php
	wp_enqueue_style( 'plugin-install' );
	wp_enqueue_script( 'plugin-install' );
	wp_enqueue_script( 'updates' );
	$req_plugins = $this->companion_plugins;
	$show_success_notice = $this->all_required_plugins_installed();

	if($show_success_notice) :
		?>
		<div class="action-tab success">
			<h3><?php printf( 'All Recommended action has been successfully completed.', 'accesspress-parallax'); ?></h3>
			<a class="button button-primary" href="<?php echo esc_url(admin_url('customize.php')); ?>"><?php echo esc_html__('Customize Theme', 'accesspress-parallax'); ?></a>
			<h3><?php esc_html_e('Or', 'accesspress-parallax'); ?></h3>
			<a class="button button-primary" href="<?php echo esc_url(admin_url('themes.php?page=accesspressparallax-welcome&section=import_demo')); ?>"><?php echo esc_html__('Import Demo', 'accesspress-parallax'); ?></a>
		</div>

		<?php
	else :

		foreach($req_plugins as $plugin) :
			if($plugin['bundled'] == false) {

				$info = $this->accesspressparallax_call_plugin_api($plugin['slug']);
				if(!isset($info->errors)) :

					$icon_url = $this->accesspressparallax_check_for_icon($info->icons);
					$status = $this->accesspressparallax_plugin_active($plugin);
					$btn_url = $this->accesspressparallax_plugin_generate_url($status, $plugin);

					switch($status) {
						case 'install' :
							$btn_class = 'install button';
							$label = esc_html__('Install and Activate', 'accesspress-parallax');
							break;

						case 'inactive' :
							$btn_class = 'button';
							$label = esc_html__('Deactivate', 'accesspress-parallax');
							break;

						case 'active' :
							$btn_class = 'activate button button-primary';
							$label = esc_html__('Activate', 'accesspress-parallax');
							break;
					}
					$path = WP_PLUGIN_DIR.'/'.esc_attr($plugin['slug']).'/'.esc_attr($plugin['filename']);
					?>
					
					<div class="action-tab warning">
						<h3><?php printf( esc_html__("Install : %s Plugin", 'accesspress-parallax'), $info->name ); ?></h3>
						<p><?php echo esc_html($plugin['info']); ?></p>

						<span class="plugin-card-<?php echo esc_attr($plugin['slug']); ?>" action_button>
							<a class="<?php echo esc_attr($btn_class); ?>" data-slug="<?php echo esc_attr($plugin['slug']); ?>" href="<?php echo esc_url($btn_url); ?>"><?php echo $label; ?></a>
						</span>
					</div>
					<?php
				endif;
			} else {
				$status = $this->accesspressparallax_plugin_active($plugin);
				switch($status) {
					case 'install' :
						$btn_class = 'install-offline button';
						$label = esc_html__('Install and Activate', 'accesspress-parallax');
						$link = $plugin['location'];
						break;

					case 'inactive' :
						$btn_class = 'button';
						$label = esc_html__('Deactivate', 'accesspress-parallax');
						$link = admin_url('plugins.php');
						break;

					case 'active' :
						$btn_class = 'activate-offline button button-primary';
						$label = esc_html__('Activate', 'accesspress-parallax');
						$link = $plugin['location'];
						break;
				}

				?>
				<div class="action-tab warning">
					<h3><?php printf( esc_html__("Install : %s Plugin", 'accesspress-parallax'), $plugin['name'] ); ?></h3>
					<p><?php echo esc_html($plugin['info']); ?></p>

					<span class="plugin-card-<?php echo esc_attr($plugin['slug']); ?>" action_button>
						<a class="<?php echo esc_attr($btn_class); ?>" data-file='<?php echo esc_attr($plugin['slug']).'/'.esc_attr($plugin['filename']); ?>' data-slug="<?php echo esc_attr($plugin['slug']); ?>" href="<?php echo esc_html($link); ?>"><?php echo $label; ?></a>
					</span>
				</div>
				<?php
			}

		endforeach;
	endif;