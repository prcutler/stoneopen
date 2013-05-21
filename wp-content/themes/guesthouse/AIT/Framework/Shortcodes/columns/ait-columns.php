<?php
/* **********************************************************
 * TWO
 * **********************************************************/
function theme_one_half( $params, $content = null ) {
  $result = '<div class="sc-column one-half">';
  $result .= do_shortcode(  $content );   
  $result .= '</div>';
  
  return force_balance_tags( $result );
}
add_shortcode( 'one_half', 'theme_one_half');

function theme_one_half_last( $params, $content = null ) {
  $result = '<div class="sc-column sc-column-last one-half-last">';
  $result .= do_shortcode( $content );   
  $result .= '</div>';
  $result .= '<div class="clearing"></div>';
  
  return force_balance_tags( $result );
}
add_shortcode( 'one_half_last', 'theme_one_half_last');

/* **********************************************************
 * THIRD
 * **********************************************************/
function theme_one_third( $params, $content = null ) {
  $result = '<div class="sc-column one-third">';
  $result .= do_shortcode( $content );   
  $result .= '</div>';
  
  return force_balance_tags( $result );
}
add_shortcode( 'one_third', 'theme_one_third');

function theme_one_third_last( $params, $content = null ) {
  $result = '<div class="sc-column sc-column-last one-third-last">';
  $result .= do_shortcode( $content );   
  $result .= '</div>';
  $result .= '<div class="clearing"></div>';
  
  return force_balance_tags( $result );
}
add_shortcode( 'one_third_last', 'theme_one_third_last');

function theme_two_third( $params, $content = null ) {
  $result = '<div class="sc-column two-third">';
  $result .= do_shortcode( $content );   
  $result .= '</div>';
  
  return force_balance_tags( $result );
}
add_shortcode( 'two_third', 'theme_two_third');

function theme_two_third_last( $params, $content = null ) {
  $result = '<div class="sc-column sc-column-last two-third-last">';
  $result .= do_shortcode( $content );   
  $result .= '</div>';
  $result .= '<div class="clearing"></div>';
  
  return force_balance_tags( $result );
}
add_shortcode( 'two_third_last', 'theme_two_third_last');

/* **********************************************************
 * FOURTH
 * **********************************************************/
function theme_one_fourth( $params, $content = null ) {
  $result = '<div class="sc-column one-fourth">';
  $result .= do_shortcode( $content );   
  $result .= '</div>';
  
  return force_balance_tags( $result );
}
add_shortcode( 'one_fourth', 'theme_one_fourth');

function theme_one_fourth_last( $params, $content = null ) {
  $result = '<div class="sc-column sc-column-last one-fourth-last">';
  $result .= do_shortcode( $content );   
  $result .= '</div>';
  $result .= '<div class="clearing"></div>';
  
  return force_balance_tags( $result );
}
add_shortcode( 'one_fourth_last', 'theme_one_fourth_last');

function theme_three_fourth( $params, $content = null ) {
  $result = '<div class="sc-column three-fourth">';
  $result .= do_shortcode( $content );   
  $result .= '</div>';
  
  return force_balance_tags( $result );
}
add_shortcode( 'three_fourth', 'theme_three_fourth');

function theme_three_fourth_last( $params, $content = null ) {
  $result = '<div class="sc-column sc-column-last three-fourth-last">';
  $result .= do_shortcode( $content );   
  $result .= '</div>';
  $result .= '<div class="clearing"></div>';
  
  return force_balance_tags( $result );
}
add_shortcode( 'three_fourth_last', 'theme_three_fourth_last');
