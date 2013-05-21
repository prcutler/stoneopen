<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */


require_once(ABSPATH . 'wp-admin/includes/class-wp-themes-list-table.php');

/**
 * AIT Theme Skins List Table class.
 *
 * @since 1.0
 */
class AitSkinsListTable extends WP_Themes_List_Table
{

	protected $skinsDir;

	protected $skinsUrl;

	protected $pageKey;


	function __construct($skinsDir = '', $skinsUrl = '', $pageKey)
	{
		$this->skinsDir = $skinsDir;
		$this->skinsUrl = $skinsUrl;
		$this->pageKey = $pageKey;
	}



	function prepare_items()
	{
		$iterator = new DirectoryIterator($this->skinsDir);

		$skins = array();

		foreach($iterator as $item){
			if(!$item->isDot() and $item->isDir()){
				$skin = $item->getBasename();
				$neon = $item->getPathname() . "/$skin.neon";
				if(file_exists($neon)){
					$config = loadConfig($neon);

					$skins[$skin]['name'] = $config['name'];
					$skins[$skin]['url'] = "$this->skinsUrl/$skin";
					$skins[$skin]['author'] = $config['author'];
					$skins[$skin]['desc'] = $config['desc'];
					$skins[$skin]['theme'] = isset($config['theme']) ? $config['theme'] : '';

					if(file_exists("$this->skinsDir/$skin/$skin-screenshot.png")){
						$skins[$skin]['screenshot'] = "$this->skinsUrl/$skin/$skin-screenshot.png";
					}else{
						$skins[$skin]['screenshot'] = '';
					}
				}
			}
		}

		uksort($skins, "strnatcasecmp");

		$per_page = 15;
		$page = $this->get_pagenum();

		$start = ($page - 1) * $per_page;


		$this->items = array_slice($skins, $start, $per_page);

		$this->set_pagination_args(array(
			'total_items' => count($skins),
			'per_page' => $per_page,
		));
	}



	function excludeCurrent($skin)
	{
		unset($this->items[$skin]);
	}



	function tablenav($which = 'top')
	{
		if ( $this->get_pagination_arg('total_pages') <= 1)
			return;
		?>
		<div class="tablenav <?php echo $which; ?>">
			<?php $this->pagination($which); ?>
		   <img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-loading list-ajax-loading" alt="" />
		  <br class="clear" />
		</div>
		<?php
	}



	function display()
	{
		$this->tablenav('top');
		?>
		<table id="availablethemes" cellspacing="0" cellpadding="0">
			<tbody id="the-list" class="list:themes">
				<?php $this->display_rows_or_placeholder(); ?>
			</tbody>
		</table>
		<?php
		$this->tablenav('bottom');
	}



	function display_rows()
	{
		$skins = $this->items;

		$skinNames = array_keys($skins);
		natcasesort($skinNames);
		$table = array();
		$rows = ceil(count($skinNames) / 3);
		for($row = 1; $row <= $rows; $row++){
			for($col = 1; $col <= 3; $col++){
				$table[$row][$col] = array_shift($skinNames);
			}
		}

		foreach($table as $row => $cols){
			?><tr><?php
			foreach($cols as $col => $skin){ ?>
			<td class="available-theme">
		<?php
		if(!empty($skin)){
			if(!empty($skins[$skin]['theme']) and $skins[$skin]['theme'] != THEME_CODE_NAME){
				/* translators: 1: skin name, 2: skin author */
				echo "<h3>" . sprintf( __( '%1$s by %2$s', 'ait'), $skins[$skin]['name'], $skins[$skin]['author']) . "</h3>";
				?><p class="description" style="color:crimson;"><?php _e('This skin is not for this theme', THEME_CODE_NAME); ?></p><?php
				continue;
			}

			$activateLink = wp_nonce_url("admin.php?page=ait-admin-{$this->pageKey}&amp;action=activate-skin&amp;skin=" . urlencode($skin), "use-skin-$skin");
			$downloadLink = wp_nonce_url("admin.php?page=ait-admin-{$this->pageKey}&amp;action=download-skin&amp;skin=" . urlencode($skin), "download-skin-$skin");
			$deleteLink = wp_nonce_url("admin.php?page=ait-admin-{$this->pageKey}&amp;action=delete-skin&amp;skin=$skin", 'delete-skin-' . $skin);

			$actions = array();
			$actions[] = '<a href="' . $activateLink .  '" onclick="' . "return confirm( '" . esc_js(sprintf(__("You are about to activate this skin '%s'\n  'OK' to activate. 'Cancel' to stop.", 'ait'), $skins[$skin]['name'])) . "' );\" class=\"activatelink\"><strong>" . esc_attr(__('Use this skin', 'ait')) . '</strong></a>';
			$actions[] = '<a href="' . $downloadLink .  '">' . esc_attr(__('Download ZIP', 'ait')) . '</a>';
			$actions[] = '<a class="submitdelete deletion" href="' . $deleteLink . '" onclick="' . "return confirm( '" . esc_js( sprintf( __( "You are about to delete this skin '%s'\n  'Cancel' to stop, 'OK' to delete.", 'ait'), $skins[$skin]['name'] ) ) . "' );" . '">' . __( 'Delete' , 'ait') . '</a>';

			$actions = implode (' | ', $actions);

			if(!empty($skins[$skin]['screenshot'])){
				echo "<img src=\"{$skins[$skin]['screenshot']}\" alt=\"\" /></a>";
			}

			/* translators: 1: skin name, 2: skin author */
			echo "<h3>" . sprintf( __( '%1$s by %2$s' , 'ait'), $skins[$skin]['name'], $skins[$skin]['author']) . "</h3>";
			?>

			<p class="description"><?php echo esc_html($skins[$skin]['desc']); ?></p>
			<span class='action-links'><?php echo $actions ?></span>
			<p><?php printf( __( 'All of this theme&#8217;s files are located in<br><code>%2$s</code>.' , 'ait'), $skins[$skin]['name'], str_replace(THEME_URL, '', $skins[$skin]['url'])); ?></p>

		</td>
		<?php
		}
		}
		?></tr><?php
		}
	}
}