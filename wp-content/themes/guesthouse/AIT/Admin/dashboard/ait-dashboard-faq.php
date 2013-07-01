<?php

/**
 * AIT WordPress Framework
 *
 * Copyright (c) 2011, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */


$docPage = (isset($_GET['tab']) and $_GET['tab'] == 'faq' and isset($_GET['qa'])) ? $_GET['qa'] : '';

$adminDocsUrl = admin_url('admin.php?page=ait-admin&tab=faq');
$adminDocUrl = admin_url('admin.php?page=ait-admin&tab=faq&qa=');

$server = "http://www.ait-themes.com/";
$faqUrl = $server . "documentation/faq/";
$qaUrl = $server . "doc/";

$error = false;

$docs = aitCachedDocsRequest($faqUrl,
	array(
		'expire' => 3 * 24 * 60 * 60,
		'id' => 'ait-theme-doc',
		'adminDocsUrl' => $adminDocsUrl,
		'adminDocUrl' => $adminDocUrl,
		'docUrl' => $qaUrl,
		)
);
if($docs !== false){
?>
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

<div id="ait-theme-faq">
<?php
	if(empty($docPage)){
		echo $docs;
		?><p><a href="#" id="ait-reset-docs" data-ait-doc-url="<?php echo md5($faqUrl) ?>" style="color: #D54E21;">Check for updates for this FAQ page.</a></p><?php
	}else{
		$doc = aitCachedDocsRequest($qaUrl . $docPage . '/?theme=faq',
			array(
				'expire' => 3 * 24 * 60 * 60,
				'id' => 'ait-theme-doc-single',
				'adminDocsUrl' => $adminDocsUrl,
				'adminDocUrl' => $adminDocUrl,
				'docUrl' => $qaUrl,
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
		<p><a href="#" id="ait-reset-docs" data-ait-doc-url="<?php echo md5($qaUrl . $docPage . '/?theme=faq') ?>" style="color: #D54E21;">Check for updates for this FAQ article.</a></p>
		<?php
		}else{
			$error = true;
		}
	}
?> </div> <?php
}else{
	$error = true;
}

if($error):
?>
	<div class="error"><p>Documentation cannot be loaded.</p></div>
<?php
endif;
