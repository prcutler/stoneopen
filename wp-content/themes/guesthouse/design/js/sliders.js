// Jquery no conflict mode
$j = jQuery.noConflict();

/* ******************************************************************************************
 * Bootstrap
 * ******************************************************************************************/
$j(document).ready(function() {
	
	InitSlider();
});

/* ******************************************************************************************
 * AnythingSlider Init
 * ******************************************************************************************/
function InitSlider() {
	if($j('.header').has('ul#slider').length){

	  var delay = parseInt($j('#slider-delay').text());
	  var animTime = parseInt($j('#slider-animTime').text());
	  var animType = $j('#slider-animType').text(); 
    var sliderHeight = parseInt($j('#slider-height').text());

	  var actualSlide = 0;

	  //$j('#slider').css("width","960px");
	  $j('#slider').css("height",sliderHeight+"px");

	  $j('#slider').anythingSlider({
	  	  easing: animType,
	  	  autoPlay: true,
	  	  expand: false,
                  resizeContents: true,
                  pauseOnHover: true,
	  	  autoPlayLocked: true,
	  	  resumeOnVideoEnd    : true,
	  	  autoPlayDelayed     : true,
		  delay               : delay,
		  animationTime       : animTime,
		  resumeDelay         : delay,
		 onInitialized       : function(e, slider){
			 
       var caption = "";
			 if($j('div.anythingSlider ul li').length == 1){
        var caption = $j('div.anythingSlider ul li').find('div.caption').html();
       } else {
        var caption = $j('div.anythingSlider ul li.activePage').find('div.caption').html();
       }
    	 $j(".room-description-container").html(caption);
       if($j('body').hasClass('has-cuffon')){
        Cufon.refresh();
       }
    	 //caption = caption.replace('h1','strong');
    	 //caption = caption.replace('h1','strong');
			 $j("#subpage-room-data-description").html(caption);
			 var link = $j('div.anythingSlider ul li.activePage').find('.room-viewer-link').attr("href");
       $j("#room-controls-2").attr({"href": link});
			var newBackground = "";//slider.$currentPage.find("div.background-image").text();
			if(newBackground){
				$j("#main-background-slider-top").show();
				$j("#main-background-slider-bottom").show();
				$j("#main-background-slider-top img").attr("src",newBackground);
			}
			if($j('#slider li.activePage').hasClass('video')){
    	 		//$j(".anythingSlider .anythingControls").css("bottom","80px");
    	 	} else {
    	 		//$j(".anythingSlider .anythingControls").css("bottom","80px");
    	 	}
    	 	
		 },
    	 onSlideInit         : function(e, slider){
    	 
    	  	// set bottom background image
    	  	var newBackground = "";//slider.$targetPage.find("div.background-image").text();
			if(newBackground){
				$j("#main-background-slider-bottom").show();
				$j("#main-background-slider-bottom img").attr("src",newBackground);
				$j("#main-background-slider-top img").fadeOut(animTime);
				$j("#main-background-slider-bottom img").fadeIn(animTime);

			} else {
				$j("#main-background-slider-top img").fadeOut(animTime);
				$j("#main-background-slider-bottom").hide();
				$j("#main-background-slider-top").show();
			}
			
    	 },
    	 onSlideBegin: function(e, slider){
        $j(".room-description-container").fadeOut("slow");        
        $j("#subpage-room-data-description").fadeOut("slow");
       },   	 
    	 onSlideComplete     : function(slider){
			// set top background image
			var newTopBackground = "";//slider.$currentPage.find("div.background-image").text();
			if(newTopBackground){
				//$j("#main-background-slider-bottom").show();
				$j("#main-background-slider-top img").attr("src",newTopBackground);
				$j("#main-background-slider-top").show();
				$j("#main-background-slider-top img").fadeIn(100);
			} else {
				$j("#main-background-slider-top img").hide();
				$j("#main-background-slider-top").hide();
			}

    	 	if($j('#slider li.activePage').hasClass('video')){
    	 		//$j(".anythingSlider .anythingControls").css("bottom","80px");
    	 	} else {
    	 		//$j(".anythingSlider .anythingControls").css("bottom","80px");
    	 	}
    	 	
    	 	
	   $j(".room-description-container").hide();
	   $j("#subpage-room-data-description").hide();
     var caption = $j('div.anythingSlider ul li.activePage').find('div.caption').html();
     var link = $j('div.anythingSlider ul li.activePage').find('.room-viewer-link').attr("href");
     $j("#room-controls-2").attr({"href": link});
     $j(".room-description-container").html(caption);
     //console.log('Before: '+caption);
     if($j('body').hasClass('has-cuffon')){
        Cufon.refresh();
       }
     //caption = caption.replace('h1','strong');
     //caption = caption.replace('h1','strong');
     //console.log('After: '+caption);
	   $j("#subpage-room-data-description").html(caption);
	   $j(".room-description-container").fadeIn("slow");
	   $j("#subpage-room-data-description").fadeIn("slow");
     }
	  });

	  $j('div.anythingSlider .forward').show();
	  $j('div.anythingSlider .back').show();
	  $j('div.anythingSlider .start-stop').hide();

	  /*$j('div.anythingSlider')/*.anythingSliderFx({
	 '.fade' : [ 'fade' ],
	 // '.selector' : [ 'caption', 'distance/size', 'time', 'easing' ]
	 // 'distance/size', 'time' and 'easing' are optional parameters
	 '.caption-top'    : [ 'caption-Top', '50px' ],
	 '.caption-topfull': [ 'caption-Top', '50px' ],
	 '.caption-right'  : [ 'caption-Right', '150px', '1000', 'easeOutBounce' ],
	 '.caption-bottom' : [ 'caption-Bottom', '50px' ],
	 '.caption-bottomfull': [ 'caption-Bottom', '50px' ],
	 '.caption-left'   : [ 'caption-Left', '130px', '1000', 'easeOutBounce' ],
	 '.expand'         : [ 'expand', '20%', '800', 'easeOutBounce' ],
	 '.fromTop'		 : [ 'top', '300px', '1500', 'easeOutElastic' ]
	})*/
	/* add a close button (x) to the caption 
	.find('div[class*=caption]')
	  .css({ position: 'absolute' })
	  .prepend('<span class="close">x</span>')
	  .find('.close').click(function(){
		var cap = $j(this).parent(),
		 ani = { bottom : -50 }; // bottom
		if (cap.is('.caption-top')) { ani = { top: -50 }; }
		if (cap.is('.caption-left')) { ani = { left: -150 }; }
		if (cap.is('.caption-right')) { ani = { right: -200 }; }
		cap.animate(ani, 400, function(){ cap.hide(); } );
	  });*/
	  
	}
}