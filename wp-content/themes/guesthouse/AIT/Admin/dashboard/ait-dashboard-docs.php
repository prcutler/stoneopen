<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

$docPage = (isset($_GET['tab']) and $_GET['tab'] == 'docs' and isset($_GET['doc'])) ? $_GET['doc'] : '';

$adminDocsUrl = admin_url('admin.php?page=ait-admin&tab=docs');
$adminDocUrl = admin_url('admin.php?page=ait-admin&tab=docs&doc=');

$server = "http://www.ait-themes.com/";
$documentationUrl = $server . "documentation/";
$docUrl = $server . "doc/";

$docs = aitCachedDocsRequest($documentationUrl . THEME_CODE_NAME . "-wordpress-theme/",
	array(
		'expire' => 3 * 24 * 60 * 60,
		'id' => 'ait-theme-doc',
		'adminDocsUrl' => $adminDocsUrl,
		'adminDocUrl' => $adminDocUrl,
		'docUrl' => $docUrl,
		)
);
?>

<?php if(!empty($docPage) and $docPage == 'config-variables'): ?>

<style>
table{ width:100%; margin:1em 0; } table th{ background: #eee; padding:5px; } table tr:nth-child(2n+1){ background:#fafafa; } table tr:hover{ background:#ccc; }  table td {padding:5px;} table td small {color:#666;} table td code{ background:none; padding:0; font-size:13px; }
</style>

<table>
	<tr>
		<th>Section</th>
		<th>Variable</th>
		<th>Input Type</th>
		<th>Default Value from config file</th>
		<th>Actual Value from DB</th>
	</tr>
	<?php
	$c = aitGetThemeDefaultOptions($GLOBALS['aitThemeConfig']);
	$t = aitGetOptionsTypes($GLOBALS['aitThemeConfig']);
	$o = $GLOBALS['aitThemeOptions'];

	foreach($c as $section => $options){
		foreach($options as $key => $value){
				//echo $section . "\t\t\t" . $key. "\t\t\t";
			?>
			<tr>
				<td><code><?php echo $section; ?></code></td>
				<td><code><?php echo $key; ?></code></td>
				<td><code><?php echo $t[$section][$key]; ?></code></td>
				<?php if(empty($value)): ?>
				<td><em><small>empty</small></em></td>
				<?php else: ?>
				<?php if(is_array($value)): ?>
				<td><pre><code><?php print_r($value); ?></code></pre></td>
				<?php else: ?>
				<td><code><?php echo esc_html($value); ?></code></td>
				<?php endif; ?>
				<?php endif; ?>
				<td>
					<?php if(isset($o->{$section}) and isset($o->{$section}->{$key})): ?>
					<?php if(empty($o->{$section}->{$key})): ?>
					<em><small>empty</small></em>
					<?php else: ?>
					<?php if(!is_object($o->{$section}->{$key}) and !is_array($o->{$section}->{$key})): ?>
					<code><?php echo esc_html($o->{$section}->{$key}); ?></code>
					<?php else: ?>
					<pre><code><?php print_r($o->{$section}->{$key}); ?></code></pre>
					<?php endif; ?>
					<?php endif; ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php
		}
	}
	?>
</table>
<?php return; elseif(empty($docPage)): ?>
	<h2>General documentation</h2>
	<ul>
		<li><a href="<?php echo $adminDocUrl; ?>config-variables">List of all settings variables from config file</a></li>
	</ul>
<?php endif; ?>


<?php  if($docs === false or is_wp_error($docs) or (!is_string($docs) and isset($docs['response']) and $docs['response']['code'] == '404')):?>
	<div class="error"><p>Documentation cannot be loaded.</p></div>
<?php return; endif; ?>

<script>
	jQuery(function() {
		jQuery('#ait-reset-docs').click(function(){
			var url = jQuery(this).data('ait-doc-url');
			jQuery.post(window.location.href, {'resetDocs': 'true', 'url': url}, function(data){
				window.location.href = '<?php echo $_SERVER['REQUEST_URI'] ?>';
			});
			return false;
		});
	});
</script>

<?php
if(empty($docPage)){

	echo $docs;
	?><p><a href="#" id="ait-reset-docs" data-ait-doc-url="<?php echo md5($documentationUrl . THEME_CODE_NAME . "-wordpress-theme/") ?>" style="color: #D54E21;">Check for updates for this documentation page.</a></p><?php
}else{
	$doc = aitCachedDocsRequest($docUrl . $docPage . '/?theme=' . THEME_CODE_NAME . "-wordpress-theme",
		array(
			'expire' => 3 * 24 * 60 * 60,
			'id' => 'ait-theme-doc-single',
			'adminDocsUrl' => $adminDocsUrl,
			'adminDocUrl' => $adminDocUrl,
			'docUrl' => $docUrl,
			)
	);
	if($doc !== false){
	?>
	<div>
		<div class="metabox-holder">
			<div class="postbox-container" style="width:75%;">
				<div id="ait-theme-doc-content" class="meta-box-sortables">
			<?php echo $doc; ?>
				</div>
			</div>
			<div class="postbox-container" style="width:24%;">
				<div  class="meta-box-sortables">
					<div id="ait-theme-doc-sidebar" class="postbox">
						<h3><span>Menu</span></h3>
						<div class="inside">
							<?php echo $docs; ?>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- /#dashboard-widgets -->
		<div class="clear"></div>
	</div>
	<p><a href="#" id="ait-reset-docs" data-ait-doc-url="<?php echo md5($docUrl . $docPage . '/?theme=' . THEME_CODE_NAME . "-wordpress-theme") ?>" style="color: #D54E21;">Check for updates for this article.</a></p>
	<?php
	}
}
