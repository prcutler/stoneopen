<?php
/* **********************************************************
 * RULES
 * **********************************************************/
function theme_rule( $params, $content = null) {
  return "<div class=\"rule\"></div>";
}
add_shortcode( 'rule', 'theme_rule' );

function theme_rule_empty( $params, $content = null) {
  return "<div class=\"rule empty\"></div>";
}
add_shortcode( 'rule_empty', 'theme_rule_empty' );

function theme_rule_top( $params, $content = null) {
  return "<div class=\"rule\"><span class=\"top\">" . __( 'top', THEME_CODE_NAME ) . "</span></div>";
}
add_shortcode( 'rule_top', 'theme_rule_top' );

/* **********************************************************
 * IMAGES
 * **********************************************************/
function theme_image( $params, $content = null) {
  extract( shortcode_atts( array(
    'src' => '',
    'alt' => '',
    'align' => ''
  ), $params ) );

  if ($align == 'left') $class = 'alignleft';
  if ($align == 'right') $class = 'alignright';

  $out = '<span class="sc-thumb ' . $class . '">';
  $out .= '<img src="' . $src. '" alt="' . $alt . '"/>';
  $out .= '</span>';

  return $out;
}
add_shortcode( 'image', 'theme_image' );

/* **********************************************************
 * PORTFOLIO
 * **********************************************************/
function portfolio_cols( $params, $content = null) {
  $out = '<div class="portfolio-cols"><div class="portfolio-wrap">';
  $out .= do_shortcode($content);
  $out .= '</div></div>';

  return $out;
}
add_shortcode( 'portfolio_cols', 'portfolio_cols' );

/* **********************************************************
 * RAW
 * **********************************************************/
  function raw( $params, $content = null) {
   $out = str_replace('<br />', '', do_shortcode( force_balance_tags( $content ) ));
   $out = str_replace('<p></p>', '', $out );
   $out = str_replace('</div></p>', '</div>', $out );
   $out = str_replace("</div>\n</p>", '</div>', $out );
   return $out;
 }
 add_shortcode( 'raw', 'raw' );

 /* **********************************************************
  * CODE
  * **********************************************************/
  function code( $params, $content = null) {
   if (isset($params['escapehtml']) and $params['escapehtml'] == 'yes') {
     $content = str_replace('<p></p>', '', $content );
      $content = str_replace('<p>[', '[', $content );
     $content = str_replace(']</p>', ']', $content );
     $content = str_replace('</div></p>', '</div>', $content );
     $content = str_replace("</div>\n</p>", '</div>', $content );
     $content = htmlentities(str_replace("<br />", "", $content));
   }
   $out = '<pre><code>' . $content . '</code></pre>';

   return force_balance_tags( $out );
 }

add_shortcode( 'code', 'code' );
