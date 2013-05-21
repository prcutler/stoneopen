<!-- NEW SERVICE BOX LAYOUT :: START -->
{if $boxes}
  <div class="service-boxes clear">
    <div class="service-boxes-container">
      {foreach $boxes as $box}
        <div class="service-box" id="sbox{$iterator->counter}">
          <div class="service-box-content" style="width: {$box->options->boxWidth}px">
            <div class="service-box-image-mirror">
              {if $box->thumbnailSrc}
              <div class="service-box-image-container" style="background: url('{!$box->thumbnailSrc}') no-repeat; width: {$box->options->boxWidth}px; height: 120px;">
              {else}
              <div class="service-box-image-container" style="background: url('{!$themeUrl}/design/img/servicebox-0.png') no-repeat; width: {$box->options->boxWidth}px; height: 120px;">
              {/if}
                <div class="service-box-title-container">
                  <h2><a href="{$box->options->boxLink}">{$box->title}</a></h2>
                </div>
              </div>
            </div>
            <p>{$box->options->boxText}</p>
          </div>
        </div>
      {/foreach}
    </div>
  </div>
  <div class="separator-line"></div>
{/if}
<!-- NEW SERVICE BOX LAYOUT :: END -->
