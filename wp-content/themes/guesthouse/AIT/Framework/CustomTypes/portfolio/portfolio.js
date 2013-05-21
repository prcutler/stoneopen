jQuery(document).ready(function() {

	var $radios = jQuery('#ait-custom-fields-0 input[type=radio]'),
		$checked = jQuery('#ait-custom-fields-0 input[type=radio]:checked'),
		$sections = jQuery('.ait-custom-fields-section');

	$sections.css({'display': 'none'});

	function switchThat($checked, $that, i){
		if($checked.val() == $that.val()){
			$sections.filter('#ait-custom-fields-' + i).removeClass().css({'display': ''});
			$sections.filter('#ait-custom-fields-' + i).find('h3').remove();
		}
	}


	$radios.each(function(i){
		i++;
		var $that = jQuery(this);

		switchThat($checked, $that, i);

		$that.click(function(){
			$sections.css({'display': 'none'});
			$checked = jQuery(this);
			switchThat($checked, $that, i);
		});
	});
});