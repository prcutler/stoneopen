{if $slides}


{if $options->sliderType == big}
<div class="slider-content type2">

            {if $themeOptions->general->layoutStyle == 'wide'}
            <div class="slider">
            {else}
            <div class="slider defaultPageWidth">
            {/if}

<div id="slider-container" class="slider-container">
  <div id="formEnabledInputs" style="display:none">
    {if isset($reservationOptions->sliderdisplayroomFormDefaultFromText)}<div style="display:none">From</div>{/if}
    {if isset($reservationOptions->sliderdisplayroomFormDefaultToText)}<div style="display:none">To</div>{/if}
  </div>
  <div id="inputName1" style="display: None">{$reservationOptions->sliderroomFormDefaultFromText}</div>
  <div id="inputName2" style="display: None">{$reservationOptions->sliderroomFormDefaultToText}</div>
  <div id="showRoomSearchByDefault" style="display:none">{if isset($reservationOptions->sliderdisplayRoomReservationSearch)}true{else}false{/if}</div>
	<div id="slider-delay" style="display:none">{!$options->sliderDelay}</div>
    <div id="slider-animTime" style="display:none">{!$options->sliderAnimTime}</div>
    <div id="slider-animType" style="display:none">{!$options->sliderAnimation}</div>
    {if $options->sliderHeight == 'auto'}
    <div id="slider-height" style="display:none">430</div>
    {else}
    <div id="slider-height" style="display:none">{!$options->sliderHeight}</div>
    {/if}

	<div id="main-background-slider-bottom" style="display:none;"><img src="#" alt="top" /></div>
    <div id="main-background-slider-top" style="display:none;"><img src="#" alt="bottom" /></div>

    <div id="preload--background-slider-images" style="display: none;">

    </div>
	<ul id="slider" class="slider slide clear">
		{var $j = 0}
    {foreach $slides as $slide}
		<li>
			<a id="room-viewer-link-{$j}" class="room-viewer-link" href="{!$slide->options->link}">
			{if $slide->options->topImage}
			  <img class="slider-container-img" src="{$timthumbUrl}?src={$slide->options->topImage}&amp;w=980&amp;h=430" alt="{$slide->options->description}" />
			{/if}
			</a>
      {if $slide->options->descriptionPosition == 'show'}
			<div class="caption">
			  <strong class="caption-title">{!$slide->title}</strong>
				<p>{!$slide->options->description}</p>
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
           {if $reservationOptions->sliderdisplayRoomReservationSearch}
           <div class="reservation-form" style="display: none">
           <form id="reservation-form-1" action="#" method="post">
                <input type="hidden" value="{$reservationOptions->sliderFormUrl}" name="formLink" id="formLink"/>
                <div class="select-wrapper">
                  <select name="room" id="room">
                    <option value="NULL" SELECTED>{$reservationOptions->sliderroomFormDefaultRoomText}</option>
                      {if $headerOptions->searchBoxType == 'rooms'}
                        {foreach $rooms as $room}
                          <option value="{!$room->thumbnailSrc}">{!$room->title}</option>
                        {/foreach}
                      {else}
                        {if $itemSliderCategories}
                          {if $reservationOptions->sliderFormCat == '0'}
                            {foreach $itemSliderCategories as $category}
                              <optgroup label="{$category}">
                                {foreach $items as $item}
                                  {? $itemCat = WpLatteFunctions::stripTag($item->categories)}
                                  {if $itemCat == $category}
                                    <option value="{!$item->thumbnailSrc}">{!$item->title}</option>
                                  {/if}
                                {/foreach}
                              </optgroup>
                            {/foreach}
                          {else}
                            {foreach $items as $item}
                              <option value="{!$item->thumbnailSrc}">{!$item->title}</option>
                            {/foreach}
                          {/if}
                        {else}
                          {foreach $items as $item}
                             <option value="{!$item->thumbnailSrc}">{!$item->title}</option>
                          {/foreach}
                        {/if}
                      {/if}
                  </select>
                </div>
                <div id="datepickerFormat" style="display: none; visibility: hidden">{!$reservationOptions->sliderdatepickerFormat}</div>
                {if isset($reservationOptions->sliderdisplayroomFormDefaultFromText)}
                <input type="text" id="datePicker1" name="datepickerFrom" value="{$reservationOptions->sliderroomFormDefaultFromText}" />
                <input type="text" id="datePicker1-alt" name="datePicker1" style="display: none" />
                {/if}
                {if isset($reservationOptions->sliderdisplayroomFormDefaultToText)}
                <input type="text" id="datePicker2" class="second" name="datepickerTo" value="{$reservationOptions->sliderroomFormDefaultToText}" />
                <input type="text" id="datePicker2-alt" name="datePicker2" style="display: none" />
                {/if}
                <h5 class="book-now-button"><a href="javascript: PopulateForm('reservation-form-1');">{$reservationOptions->sliderroomFormBookNowText}</a></h5>
           </form>
         </div>
           {/if}
       </div>
	     <ul class="room-controls">
          {if isset($reservationOptions->sliderdisplayRoomReservation)}
            <li>
              <a href="#" id="room-controls-1">
                <img src="{$themeUrl}/design/img/room-viewer-icon1.png" alt="Room Reservation">
              <span>{$reservationOptions->sliderroomReservationText}</span>
              </a>
            </li>
          {/if}

          {if isset($reservationOptions->sliderdisplayRoomDescription)}
            <li>
              <a href="#" id="room-controls-2">
                <img src="{$themeUrl}/design/img/room-viewer-icon2.png" alt="Room Description">
              <span>{$reservationOptions->sliderroomDescriptionText}</span>
              </a>
            </li>
          {/if}

          {if isset($reservationOptions->sliderdisplayRoomAdditionalServices)}
            <li>
              <a href="{$reservationOptions->sliderroomCustomServicesLink}"  id="room-controls-3">
                <img src="{$reservationOptions->sliderroomCustomServicesImage}" alt="{$reservationOptions->sliderroomCustomServicesText}">
              <span>{$reservationOptions->sliderroomCustomServicesText}</span>
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
            <div class="white-space"></div>
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
    {if isset($reservationOptions->sliderdisplayroomFormDefaultFromText)}<div style="display:none">From</div>{/if}
    {if isset($reservationOptions->sliderdisplayroomFormDefaultToText)}<div style="display:none">To</div>{/if}
  </div>
  <div id="showRoomSearchByDefault" style="display:none">{if isset($reservationOptions->sliderdisplayRoomReservationSearch)}true{else}false{/if}</div>
	<div id="slider-delay" style="display:none">{!$options->sliderDelay}</div>
    <div id="slider-animTime" style="display:none">{!$options->sliderAnimTime}</div>
    <div id="slider-animType" style="display:none">{!$options->sliderAnimation}</div>
    {if $options->sliderHeight == auto}
    <div id="slider-height" style="display:none"></div>
    {else}
    <div id="slider-height" style="display:none">{!$options->sliderHeight}</div>
    {/if}

	<div id="main-background-slider-bottom" style="display:none;"><img src="#" alt="top" /></div>
    <div id="main-background-slider-top" style="display:none;"><img src="#" alt="bottom" /></div>

    <div id="preload--background-slider-images" style="display: none;">

    </div>

	<ul id="slider" class="subslider slide clear">
		{foreach $slides as $slide}
		<li>
			<a href="{$slide->options->link}">
			{if $slide->options->topImage}
			  <img class="slider-container-img" src="{$timthumbUrl}?src={$slide->options->topImage}&amp;w=980&amp;h=280" alt="{$slide->options->description}" />
			{/if}
			</a>

			{if $slide->options->descriptionPosition == 'show'}
			<div class="caption">
			  <strong class="caption-title">{!$slide->title}</strong>
        <p>{!$slide->options->description}</p>
      </div>
      {/if}
		</li>
		{/foreach}
	</ul>


	<div id="subpage-room-options-container" class="room-options-container subpage-room-options-container">

     <div id="subpage-room-options-container-left" class="room-options-container-left subpage-room-options-container-left">

       <h4 class="subpage-room-data-title">{$reservationOptions->sliderroomReservationText}</h4>
       <div class="room-data subpage-room-data">
           <div class="reservation-form subpage-reservation-form">
           <form id="reservation-form-1" action="#" method="post">
                <input type="hidden" value="{$reservationOptions->sliderFormUrl}" name="formLink" id="formLink"/>
                <div class="select-wrapper">
                  {if $headerOptions->searchBoxType != 'rooms' && $reservationOptions->sliderFormCat != -1 || $headerOptions->searchBoxType == 'rooms'}
                  <select name="room" id="room">
                    <option value="NULL" SELECTED>{$reservationOptions->sliderroomFormDefaultRoomText}</option>
                      {if $headerOptions->searchBoxType == 'rooms'}
                        {foreach $rooms as $room}
                          <option value="{!$room->thumbnailSrc}">{!$room->title}</option>
                        {/foreach}
                      {else}
                        {if $itemSliderCategories}
                          {if $reservationOptions->sliderFormCat == '0'}
                            {foreach $itemSliderCategories as $category}
                              <optgroup label="{$category}">
                                {foreach $items as $item}
                                  {? $itemCat = WpLatteFunctions::stripTag($item->categories)}
                                  {if $itemCat == $category}
                                    <option value="{!$item->thumbnailSrc}">{!$item->title}</option>
                                  {/if}
                                {/foreach}
                              </optgroup>
                            {/foreach}
                          {else}
                            {foreach $items as $item}
                              <option value="{!$item->thumbnailSrc}">{!$item->title}</option>
                            {/foreach}
                          {/if}
                        {else}
                          {foreach $items as $item}
                             <option value="{!$item->thumbnailSrc}">{!$item->title}</option>
                          {/foreach}
                        {/if}
                      {/if}
                  </select>
                  {/if}
                </div>
                <div id="datepickerFormat" style="display: none; visibility: hidden">{!$reservationOptions->sliderdatepickerFormat}</div>
                {if isset($reservationOptions->sliderdisplayroomFormDefaultFromText)}
                <input type="text" id="datePicker1" name="datepickerFrom" value="{$reservationOptions->sliderroomFormDefaultFromText}" />
                <input type="text" id="datePicker1-alt" name="datePicker1" style="display: none" />
                {/if}
                {if isset($reservationOptions->sliderdisplayroomFormDefaultToText)}
                <input type="text" id="datePicker2" class="second" name="datepickerTo" value="{$reservationOptions->sliderroomFormDefaultToText}" />
                <input type="text" id="datePicker2-alt" name="datePicker2" style="display: none" />
                {/if}
                <h5 class="book-now-button-subpage"><a href="javascript: PopulateForm('reservation-form-1');">{$reservationOptions->sliderroomFormBookNowText}</a></h5>
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
