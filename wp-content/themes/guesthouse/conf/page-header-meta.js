jQuery(document).ready(function() {
	chooseDisplay(jQuery("select.ait-headerType-option").val());
	
  jQuery("select.ait-headerType-option").click(function(){
    chooseDisplay(jQuery("select.ait-headerType-option").val());   
  });
});

function chooseDisplay(value){
  switch(value){
    case "slider":
    showMetabox('#_ait_slider_options_metabox');
    hideMetabox('#_ait_room_options_metabox');
    jQuery('#ait-_ait_header_options-searchBoxType-option').show();
    break;
    case "roomViewer":
    showMetabox('#_ait_room_options_metabox');
    hideMetabox('#_ait_slider_options_metabox');
    jQuery('#ait-_ait_header_options-searchBoxType-option').hide();    
    break;
    case "none":
    hideMetabox('#_ait_slider_options_metabox');
    hideMetabox('#_ait_room_options_metabox');
    jQuery('#ait-_ait_header_options-searchBoxType-option').hide();
    break;
  }
} 

function hideMetabox(id){
  jQuery(id).hide();
}

function showMetabox(id){
  jQuery(id).show();
}