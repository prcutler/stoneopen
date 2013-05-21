jQuery(document).ready(function() {

    		// init
    		switch(jQuery("#ait-_ait-slider-creator-slideType option:selected").attr("value"))
        {
          case "normal":
            // vsetko co je advanced prec
            jQuery("#ait-_ait-slider-creator-advancedItemType-option").hide();  // vyber {image/video/swf}

            jQuery("#ait-_ait-slider-creator-advancedImageSource-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedImageTitle-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedImageText-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedImageLink-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedImageLinkTarget-option").hide();
            
            jQuery("#ait-_ait-slider-creator-advancedVideoSource-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedVideoTitle-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedVideoWidth-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedVideoHeight-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedVideoAutoplay-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedVideoPreview-option").hide();
            
            jQuery("#ait-_ait-slider-creator-advancedSwfSource-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedSwfTitle-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedSwfPreview-option").hide();
            
            jQuery("#ait-_ait-slider-creator-advancedTransitionPieces-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedTransitionTime-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedTransitionType-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedTransitionDelay-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedTransitionDepthOffset-option").hide();
            jQuery("#ait-_ait-slider-creator-advancedTransitionCubeDistance-option").hide();
            
            jQuery("#ait-_ait-slider-creator-itemType-option").show();
            if(jQuery("#ait-_ait-slider-creator-itemType option:selected").attr("value") == "image")
            {
              jQuery("#ait-_ait-slider-creator-topImage-option").show();
              jQuery("#ait-_ait-slider-creator-backgroundImage-option").show();
              jQuery("#ait-_ait-slider-creator-link-option").show();
              jQuery("#ait-_ait-slider-creator-videoUrl-option").hide();
              jQuery("#ait-_ait-slider-creator-description-option").show();
              jQuery("#ait-_ait-slider-creator-descriptionPosition-option").show();
              
              jQuery("#ait-_ait-slider-creator-slideBackgroundColor-option").show();
              jQuery("#ait-_ait-slider-creator-slideRepeating-option").show();
              jQuery("#ait-_ait-slider-creator-slideBackgroundPosX-option").show();
              jQuery("#ait-_ait-slider-creator-slideBackgroundPosY-option").show();
            }
            else
            {
              jQuery("#ait-_ait-slider-creator-topImage-option").hide();
              jQuery("#ait-_ait-slider-creator-backgroundImage-option").hide();
              jQuery("#ait-_ait-slider-creator-link-option").hide();
              jQuery("#ait-_ait-slider-creator-videoUrl-option").show();
              jQuery("#ait-_ait-slider-creator-description-option").hide();
              jQuery("#ait-_ait-slider-creator-descriptionPosition-option").hide();
              
              jQuery("#ait-_ait-slider-creator-slideBackgroundColor-option").hide();
              jQuery("#ait-_ait-slider-creator-slideRepeating-option").hide();
              jQuery("#ait-_ait-slider-creator-slideBackgroundPosX-option").hide();
              jQuery("#ait-_ait-slider-creator-slideBackgroundPosY-option").hide();
            }
            
            break;
          case "advanced":
            // vsetko co je normal prec
            jQuery("#ait-_ait-slider-creator-itemType-option").hide();  // vyber {image/video}
            
            jQuery("#ait-_ait-slider-creator-topImage-option").hide();
            jQuery("#ait-_ait-slider-creator-backgroundImage-option").hide();
            jQuery("#ait-_ait-slider-creator-link-option").hide();
            jQuery("#ait-_ait-slider-creator-videoUrl-option").hide();
            jQuery("#ait-_ait-slider-creator-description-option").hide();
            jQuery("#ait-_ait-slider-creator-descriptionPosition-option").hide();            
            
            jQuery("#ait-_ait-slider-creator-advancedItemType-option").show();
            jQuery("#ait-_ait-slider-creator-advancedTransitionPieces-option").show();
            jQuery("#ait-_ait-slider-creator-advancedTransitionTime-option").show();
            jQuery("#ait-_ait-slider-creator-advancedTransitionType-option").show();
            jQuery("#ait-_ait-slider-creator-advancedTransitionDelay-option").show();
            jQuery("#ait-_ait-slider-creator-advancedTransitionDepthOffset-option").show();
            jQuery("#ait-_ait-slider-creator-advancedTransitionCubeDistance-option").show();
            
            if(jQuery("#ait-_ait-slider-creator-advancedItemType option:selected").attr("value") == "image")
            {
              jQuery("#ait-_ait-slider-creator-advancedImageSource-option").show();
              jQuery("#ait-_ait-slider-creator-advancedImageTitle-option").show();
              jQuery("#ait-_ait-slider-creator-advancedImageText-option").show();
              jQuery("#ait-_ait-slider-creator-advancedImageLink-option").show();
              jQuery("#ait-_ait-slider-creator-advancedImageLinkTarget-option").show();
              
              jQuery("#ait-_ait-slider-creator-advancedVideoSource-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoTitle-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoWidth-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoHeight-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoAutoplay-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoPreview-option").hide();
              
              jQuery("#ait-_ait-slider-creator-advancedSwfSource-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedSwfTitle-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedSwfPreview-option").hide();  
            }
            else if(jQuery("#ait-_ait-slider-creator-advancedItemType option:selected").attr("value") == "video")
            { 
              jQuery("#ait-_ait-slider-creator-advancedImageSource-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageTitle-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageText-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageLink-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageLinkTarget-option").hide();
              
              jQuery("#ait-_ait-slider-creator-advancedVideoSource-option").show();
              jQuery("#ait-_ait-slider-creator-advancedVideoTitle-option").show();
              jQuery("#ait-_ait-slider-creator-advancedVideoWidth-option").show();
              jQuery("#ait-_ait-slider-creator-advancedVideoHeight-option").show();
              jQuery("#ait-_ait-slider-creator-advancedVideoAutoplay-option").show();
              jQuery("#ait-_ait-slider-creator-advancedVideoPreview-option").show();
              
              jQuery("#ait-_ait-slider-creator-advancedSwfSource-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedSwfTitle-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedSwfPreview-option").hide();
            }
            else
            {
              jQuery("#ait-_ait-slider-creator-advancedImageSource-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageTitle-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageText-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageLink-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageLinkTarget-option").hide();
              
              jQuery("#ait-_ait-slider-creator-advancedVideoSource-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoTitle-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoWidth-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoHeight-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoAutoplay-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoPreview-option").hide();
              
              jQuery("#ait-_ait-slider-creator-advancedSwfSource-option").show();
              jQuery("#ait-_ait-slider-creator-advancedSwfTitle-option").show();
              jQuery("#ait-_ait-slider-creator-advancedSwfPreview-option").show();
            }
            break;
        }
        
        // change
    		jQuery('#ait-_ait-slider-creator-slideType').change(function(){
          switch(jQuery("#ait-_ait-slider-creator-slideType option:selected").attr("value"))
          {
            case "normal":
              // vsetko co je advanced prec
              
              jQuery("#ait-_ait-slider-creator-advancedItemType-option").hide();  // vyber {image/video/swf}
              
              jQuery("#ait-_ait-slider-creator-advancedImageSource-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageTitle-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageText-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageLink-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedImageLinkTarget-option").hide();
              
              jQuery("#ait-_ait-slider-creator-advancedVideoSource-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoTitle-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoWidth-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoHeight-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoAutoplay-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedVideoPreview-option").hide();
              
              jQuery("#ait-_ait-slider-creator-advancedSwfSource-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedSwfTitle-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedSwfPreview-option").hide();
              
              jQuery("#ait-_ait-slider-creator-advancedTransitionPieces-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedTransitionTime-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedTransitionType-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedTransitionDelay-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedTransitionDepthOffset-option").hide();
              jQuery("#ait-_ait-slider-creator-advancedTransitionCubeDistance-option").hide();
              
              jQuery("#ait-_ait-slider-creator-itemType-option").show();
              if(jQuery("#ait-_ait-slider-creator-itemType option:selected").attr("value") == "image")
              {
                jQuery("#ait-_ait-slider-creator-topImage-option").show();
                jQuery("#ait-_ait-slider-creator-backgroundImage-option").show();
                jQuery("#ait-_ait-slider-creator-link-option").show();
                jQuery("#ait-_ait-slider-creator-videoUrl-option").hide();
                jQuery("#ait-_ait-slider-creator-description-option").show();
                jQuery("#ait-_ait-slider-creator-descriptionPosition-option").show();
                
                jQuery("#ait-_ait-slider-creator-slideBackgroundColor-option").show();
                jQuery("#ait-_ait-slider-creator-slideRepeating-option").show();
                jQuery("#ait-_ait-slider-creator-slideBackgroundPosX-option").show();
                jQuery("#ait-_ait-slider-creator-slideBackgroundPosY-option").show();
              }
              else
              {
                jQuery("#ait-_ait-slider-creator-topImage-option").hide();
                jQuery("#ait-_ait-slider-creator-backgroundImage-option").hide();
                jQuery("#ait-_ait-slider-creator-link-option").hide();
                jQuery("#ait-_ait-slider-creator-videoUrl-option").show();
                jQuery("#ait-_ait-slider-creator-description-option").hide();
                jQuery("#ait-_ait-slider-creator-descriptionPosition-option").hide();
                
                jQuery("#ait-_ait-slider-creator-slideBackgroundColor-option").hide();
                jQuery("#ait-_ait-slider-creator-slideRepeating-option").hide();
                jQuery("#ait-_ait-slider-creator-slideBackgroundPosX-option").hide();
                jQuery("#ait-_ait-slider-creator-slideBackgroundPosY-option").hide();
              }
              
              
              jQuery('#ait-_ait-slider-creator-itemType').change(function(){
                if(jQuery("#ait-_ait-slider-creator-itemType option:selected").attr("value") == "image")
                {
                  jQuery("#ait-_ait-slider-creator-topImage-option").show();
                  jQuery("#ait-_ait-slider-creator-backgroundImage-option").show();
                  jQuery("#ait-_ait-slider-creator-link-option").show();
                  jQuery("#ait-_ait-slider-creator-videoUrl-option").hide();
                  jQuery("#ait-_ait-slider-creator-description-option").show();
                  jQuery("#ait-_ait-slider-creator-descriptionPosition-option").show();
                  
                  jQuery("#ait-_ait-slider-creator-slideBackgroundColor-option").show();
                  jQuery("#ait-_ait-slider-creator-slideRepeating-option").show();
                  jQuery("#ait-_ait-slider-creator-slideBackgroundPosX-option").show();
                  jQuery("#ait-_ait-slider-creator-slideBackgroundPosY-option").show();
                }
                else
                {
                  jQuery("#ait-_ait-slider-creator-topImage-option").hide();
                  jQuery("#ait-_ait-slider-creator-backgroundImage-option").hide();
                  jQuery("#ait-_ait-slider-creator-link-option").hide();
                  jQuery("#ait-_ait-slider-creator-videoUrl-option").show();
                  jQuery("#ait-_ait-slider-creator-description-option").hide();
                  jQuery("#ait-_ait-slider-creator-descriptionPosition-option").hide();
                  
                  jQuery("#ait-_ait-slider-creator-slideBackgroundColor-option").hide();
                  jQuery("#ait-_ait-slider-creator-slideRepeating-option").hide();
                  jQuery("#ait-_ait-slider-creator-slideBackgroundPosX-option").hide();
                  jQuery("#ait-_ait-slider-creator-slideBackgroundPosY-option").hide();
                }
              });
            
              break;
            case "advanced":
              
              jQuery("#ait-_ait-slider-creator-itemType-option").hide();  // vyber {image/video}
            
              jQuery("#ait-_ait-slider-creator-topImage-option").hide();
              jQuery("#ait-_ait-slider-creator-backgroundImage-option").hide();
              jQuery("#ait-_ait-slider-creator-link-option").hide();
              jQuery("#ait-_ait-slider-creator-videoUrl-option").hide();
              jQuery("#ait-_ait-slider-creator-description-option").hide();
              jQuery("#ait-_ait-slider-creator-descriptionPosition-option").hide();
              jQuery("#ait-_ait-slider-creator-slideBackgroundColor-option").hide();
              jQuery("#ait-_ait-slider-creator-slideRepeating-option").hide();
              jQuery("#ait-_ait-slider-creator-slideBackgroundPosX-option").hide();
              jQuery("#ait-_ait-slider-creator-slideBackgroundPosY-option").hide();            
              
              jQuery("#ait-_ait-slider-creator-advancedItemType-option").show();
              jQuery("#ait-_ait-slider-creator-advancedTransitionPieces-option").show();
              jQuery("#ait-_ait-slider-creator-advancedTransitionTime-option").show();
              jQuery("#ait-_ait-slider-creator-advancedTransitionType-option").show();
              jQuery("#ait-_ait-slider-creator-advancedTransitionDelay-option").show();
              jQuery("#ait-_ait-slider-creator-advancedTransitionDepthOffset-option").show();
              jQuery("#ait-_ait-slider-creator-advancedTransitionCubeDistance-option").show();
              
              if(jQuery("#ait-_ait-slider-creator-advancedItemType option:selected").attr("value") == "image")
              {
                jQuery("#ait-_ait-slider-creator-advancedImageSource-option").show();
                jQuery("#ait-_ait-slider-creator-advancedImageTitle-option").show();
                jQuery("#ait-_ait-slider-creator-advancedImageText-option").show();
                jQuery("#ait-_ait-slider-creator-advancedImageLink-option").show();
                jQuery("#ait-_ait-slider-creator-advancedImageLinkTarget-option").show();
                
                jQuery("#ait-_ait-slider-creator-advancedVideoSource-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedVideoTitle-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedVideoWidth-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedVideoHeight-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedVideoAutoplay-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedVideoPreview-option").hide();
                
                jQuery("#ait-_ait-slider-creator-advancedSwfSource-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedSwfTitle-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedSwfPreview-option").hide();  
              }
              else if(jQuery("#ait-_ait-slider-creator-advancedItemType option:selected").attr("value") == "video")
              {
                jQuery("#ait-_ait-slider-creator-advancedImageSource-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedImageTitle-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedImageText-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedImageLink-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedImageLinkTarget-option").hide();
                
                jQuery("#ait-_ait-slider-creator-advancedVideoSource-option").show();
                jQuery("#ait-_ait-slider-creator-advancedVideoTitle-option").show();
                jQuery("#ait-_ait-slider-creator-advancedVideoWidth-option").show();
                jQuery("#ait-_ait-slider-creator-advancedVideoHeight-option").show();
                jQuery("#ait-_ait-slider-creator-advancedVideoAutoplay-option").show();
                jQuery("#ait-_ait-slider-creator-advancedVideoPreview-option").show();
                
                jQuery("#ait-_ait-slider-creator-advancedSwfSource-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedSwfTitle-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedSwfPreview-option").hide();
              }
              else
              {
                jQuery("#ait-_ait-slider-creator-advancedImageSource-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedImageTitle-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedImageText-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedImageLink-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedImageLinkTarget-option").hide();
                
                jQuery("#ait-_ait-slider-creator-advancedVideoSource-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedVideoTitle-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedVideoWidth-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedVideoHeight-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedVideoAutoplay-option").hide();
                jQuery("#ait-_ait-slider-creator-advancedVideoPreview-option").hide();
                
                jQuery("#ait-_ait-slider-creator-advancedSwfSource-option").show();
                jQuery("#ait-_ait-slider-creator-advancedSwfTitle-option").show();
                jQuery("#ait-_ait-slider-creator-advancedSwfPreview-option").show();
              }
              
              
              jQuery('#ait-_ait-slider-creator-advancedItemType').change(function(){
                if(jQuery("#ait-_ait-slider-creator-advancedItemType option:selected").attr("value") == "image")
                {
                  jQuery("#ait-_ait-slider-creator-advancedImageSource-option").show();
                  jQuery("#ait-_ait-slider-creator-advancedImageTitle-option").show();
                  jQuery("#ait-_ait-slider-creator-advancedImageText-option").show();
                  jQuery("#ait-_ait-slider-creator-advancedImageLink-option").show();
                  jQuery("#ait-_ait-slider-creator-advancedImageLinkTarget-option").show();
                  
                  jQuery("#ait-_ait-slider-creator-advancedVideoSource-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedVideoTitle-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedVideoWidth-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedVideoHeight-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedVideoAutoplay-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedVideoPreview-option").hide();
                  
                  jQuery("#ait-_ait-slider-creator-advancedSwfSource-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedSwfTitle-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedSwfPreview-option").hide();  
                }
                else if(jQuery("#ait-_ait-slider-creator-advancedItemType option:selected").attr("value") == "video")
                {
                  jQuery("#ait-_ait-slider-creator-advancedImageSource-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedImageTitle-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedImageText-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedImageLink-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedImageLinkTarget-option").hide();
                  
                  jQuery("#ait-_ait-slider-creator-advancedVideoSource-option").show();
                  jQuery("#ait-_ait-slider-creator-advancedVideoTitle-option").show();
                  jQuery("#ait-_ait-slider-creator-advancedVideoWidth-option").show();
                  jQuery("#ait-_ait-slider-creator-advancedVideoHeight-option").show();
                  jQuery("#ait-_ait-slider-creator-advancedVideoAutoplay-option").show();
                  jQuery("#ait-_ait-slider-creator-advancedVideoPreview-option").show();
                  
                  jQuery("#ait-_ait-slider-creator-advancedSwfSource-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedSwfTitle-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedSwfPreview-option").hide();
                }
                else
                {
                  jQuery("#ait-_ait-slider-creator-advancedImageSource-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedImageTitle-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedImageText-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedImageLink-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedImageLinkTarget-option").hide();
                  
                  jQuery("#ait-_ait-slider-creator-advancedVideoSource-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedVideoTitle-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedVideoWidth-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedVideoHeight-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedVideoAutoplay-option").hide();
                  jQuery("#ait-_ait-slider-creator-advancedVideoPreview-option").hide();
                  
                  jQuery("#ait-_ait-slider-creator-advancedSwfSource-option").show();
                  jQuery("#ait-_ait-slider-creator-advancedSwfTitle-option").show();
                  jQuery("#ait-_ait-slider-creator-advancedSwfPreview-option").show();
                }
              });
              break;
          }   
        });
           
});