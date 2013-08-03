
{if $rooms}



{if $options->roomViewerType == big}
<div class="slider-content type1">

            {if $themeOptions->general->layoutStyle == 'wide'}
            <div class="slider">
            {else}
            <div class="slider defaultPageWidth">
            {/if}

<div id="slider-container" class="slider-container">
  <div id="formEnabledInputs" style="display:none">
    {if isset($reservationOptions->displayroomFormDefaultFromText)}<div style="display:none">From</div>{/if}
    {if isset($reservationOptions->displayroomFormDefaultToText)}<div style="display:none">To</div>{/if}
  </div>
  <div id="showRoomSearchByDefault" style="display:none">{if isset($reservationOptions->displayRoomReservationSearch)}true{else}false{/if}</div>
	<div id="slider-delay" style="display:none">{!$options->roomViewerDelay}</div>
    <div id="slider-animTime" style="display:none">{!$options->roomViewerAnimTime}</div>
    <div id="slider-animType" style="display:none">{!$options->roomViewerAnimation}</div>
    {if $options->roomViewerHeight == 'auto'}
    <div id="slider-height" style="display:none">430</div>
    {else}
    <div id="slider-height" style="display:none">{!$options->roomViewerHeight}</div>
    {/if}

	<div id="main-background-slider-bottom" style="display:none;"><img src="#" alt="top" /></div>
    <div id="main-background-slider-top" style="display:none;"><img src="#" alt="bottom" /></div>

    <div id="preload--background-slider-images" style="display: none;">

    </div>
	<ul id="slider" class="slider slide clear">
		{var $j = 0}
    {foreach $rooms as $room}
		<li>
			<a id="room-viewer-link-{$j}" class="room-viewer-link" href="{!$room->permalink}">
			{if $room->thumbnailSrc}
			  <img class="slider-container-img" src="{$timthumbUrl}?src={$room->thumbnailSrc}&amp;w=980&amp;h=430" alt="{$room->options->roomDescription}" />
			{/if}
			</a>

			{if isset($room->options->roomDescriptionShort)}
			<div class="caption">
			  <strong class="caption-title">{!$room->title}</strong>
				<p>{!$room->options->roomDescriptionShort}</p>
				</div>
			{/if}
		</li>
		{var $j++}
		{/foreach}
	</ul>

	<div class="room-options-container">
	   <div class="room-options-container-top">
        <div class="room-options-container-top-left"></div>
        <div class="room-options-container-top-right"></div>
     </div>
	   <div class="room-options-container-bottom">
	     <div class="room-data">
         {if isset($reservationOptions->displayRoomReservationSearch)}
                 <div class="reservation-form" style="display: none">
           <form id="reservation-form-1" action="#" method="post">
                <input type="hidden" value="{$themeOptions->general->contactFormAddress}" name="formLink" id="formLink"/>
                <div class="select-wrapper">
                  <select name="room" id="room">
                    <option value="NULL" SELECTED>{$reservationOptions->roomFormDefaultRoomText}</option>
                  {foreach $rooms as $room}
                    <option value="{!$room->thumbnailSrc}">{!$room->title}</option>
                  {/foreach}
                  </select>
                </div>
                <div id="datepickerFormat" style="display: none; visibility: hidden">{!$reservationOptions->datepickerFormat}</div>
                {if isset($reservationOptions->displayroomFormDefaultFromText)}
                <input type="text" id="datePicker1" name="datepickerFrom" value="{$reservationOptions->roomFormDefaultFromText}" />
                <input type="text" id="datePicker1-alt" name="datePicker1" style="display: none" />
                {/if}
                {if isset($reservationOptions->displayroomFormDefaultToText)}
                <input type="text" id="datePicker2" class="second" name="datepickerTo" value="{$reservationOptions->roomFormDefaultToText}" />
                <input type="text" id="datePicker2-alt" name="datePicker2" style="display: none" />
                {/if}
                <h5 class="book-now-button"><a href="javascript: PopulateForm('reservation-form-1');">{$reservationOptions->roomFormBookNowText}</a></h5>
           </form>
         </div>
         {/if}
       </div>
	     <ul class="room-controls">
          {if isset($reservationOptions->displayRoomReservation)}
            <li>
              <a href="#" id="room-controls-1">
                <img src="{$themeUrl}/design/img/room-viewer-icon1.png" alt="Room Reservation">
              <span>{$reservationOptions->roomReservationText}</span>
              </a>
            </li>
          {/if}

          {if isset($reservationOptions->displayRoomDescription)}
            <li>
              <a href="#" id="room-controls-2">
                <img src="{$themeUrl}/design/img/room-viewer-icon2.png" alt="Room Description">
              <span>{$reservationOptions->roomDescriptionText}</span>
              </a>
            </li>
          {/if}

          {if isset($reservationOptions->displayRoomAdditionalServices)}
            <li>
              <a href="{$reservationOptions->roomCustomServicesLink}"  id="room-controls-3">
                <img src="{$reservationOptions->roomCustomServicesImage}" alt="{$reservationOptions->roomCustomServicesText}">
              <span>{$reservationOptions->roomCustomServicesText}</span>
              </a>
            </li>
          {/if}
        </ul>
     </div>
	</div>

	<div class="room-description-container"></div>

</div><!-- end of slider -->

</div>
            {if $site->isHomepage}
            <div class="white-space"></div>
            {else}
            <div class="white-space-sub"></div>
            {/if}
            </div>
          </div>

{else}

<div class="slider-content">

            {if $themeOptions->general->layoutStyle == 'wide'}
            <div class="slider">
            {else}
            <div class="slider defaultPageWidth">
            {/if}

<!-- TOOLBAR -->
<div class="toolbar">
  <div class="defaultContentWidth">
    <div id="breadcrumb">{__ 'You are here: '}{breadcrumbs}</div>
  </div>
</div>
<!-- TOOLBAR -->
<div id="slider-container" class="slider-container subpage-slider-container">
  <div id="formEnabledInputs" style="display:none">
    {if isset($reservationOptions->displayroomFormDefaultFromText)}<div style="display:none">From</div>{/if}
    {if isset($reservationOptions->displayroomFormDefaultToText)}<div style="display:none">To</div>{/if}
  </div>
  <div id="showRoomSearchByDefault" style="display:none">{if isset($reservationOptions->displayRoomReservationSearch)}true{else}false{/if}</div>
	<div id="slider-delay" style="display:none">{!$options->roomViewerDelay}</div>
    <div id="slider-animTime" style="display:none">{!$options->roomViewerAnimTime}</div>
    <div id="slider-animType" style="display:none">{!$options->roomViewerAnimation}</div>
    {if $options->roomViewerHeight == auto}
    <div id="slider-height" style="display:none"></div>
    {else}
    <div id="slider-height" style="display:none">{!$options->roomViewerHeight}</div>
    {/if}

	<div id="main-background-slider-bottom" style="display:none;"><img src="#" alt="top" /></div>
    <div id="main-background-slider-top" style="display:none;"><img src="#" alt="bottom" /></div>

    <div id="preload--background-slider-images" style="display: none;">

    </div>

	<ul id="slider" class="subslider slide clear">
		{foreach $rooms as $room}
		<li>
			<a href="{$room->permalink}">
			{if $room->thumbnailSrc}
			  <img class="slider-container-img" src="{$timthumbUrl}?src={$room->thumbnailSrc}&amp;w=980&amp;h=280" alt="{$room->options->roomDescriptionShort}" />
			{/if}
			</a>

			{if isset($room->options->roomDescriptionShort)}
			<div class="caption">
			  <strong class="caption-title">{!$room->title}</strong>
        <p>{!$room->options->roomDescriptionShort}</p>
      </div>
      {/if}
		</li>
		{/foreach}
	</ul>


	<div id="subpage-room-options-container" class="room-options-container subpage-room-options-container">

     <div id="subpage-room-options-container-left" class="room-options-container-left subpage-room-options-container-left">

       <h4 class="subpage-room-data-title">{$reservationOptions->roomReservationText}</h4>
       <div class="room-data subpage-room-data">
           <div class="reservation-form subpage-reservation-form">
           <form id="reservation-form-1" action="#" method="post">
                <input type="hidden" value="{!$themeOptions->general->contactFormAddress}" name="formLink" id="formLink"/>
                <div class="select-wrapper">
                  <select name="room" id="room">
                    <option value="NULL" SELECTED>{$reservationOptions->roomFormDefaultRoomText}</option>
                  {foreach $rooms as $room}
                    <option value="{!$room->thumbnailSrc}">{!$room->title}</option>
                  {/foreach}
                  </select>
                </div>
                <div id="datepickerFormat" style="display: none; visibility: hidden">{!$reservationOptions->datepickerFormat}</div>
                {if isset($reservationOptions->displayroomFormDefaultFromText)}
                <input type="text" id="datePicker1" name="datepickerFrom" value="{$reservationOptions->roomFormDefaultFromText}" />
                <input type="text" id="datePicker1-alt" name="datePicker1" style="display: none" />
                {/if}
                {if isset($reservationOptions->displayroomFormDefaultToText)}
                <input type="text" id="datePicker2" class="second" name="datepickerTo" value="{$reservationOptions->roomFormDefaultToText}" />
                <input type="text" id="datePicker2-alt" name="datePicker2" style="display: none" />
                {/if}
                <h5 class="book-now-button-subpage"><a href="javascript: PopulateForm('reservation-form-1');">{$reservationOptions->roomFormBookNowText}</a></h5>
           </form>
           <div id="subpage-room-data-description" class="subpage-room-data-description"><h5></h5></div>
            </div>
       </div>
     </div>
     <img id="showHideHousing" class="container-control show" src="{$themeUrl}/design/img/ico_close_off.png" alt="Show-Hide" />
	</div>

</div><!-- end of slider -->

</div>
            {if $site->isHomepage}
            <div class="white-space"></div>
            {else}
            <div class="white-space-sub"></div>
            {/if}
            </div>
          </div>


{/if}

{/if}
