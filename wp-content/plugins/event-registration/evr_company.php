<?php
function evr_issetor(&$variable, $or = NULL) {
     return $variable === NULL ? $or : $variable;
 }
 
function evr_admin_company(){
		global $wpdb, $wp_version;
        $company_options = get_option('evr_company_settings');
        if (isset($_POST['update_company'])){$update_company = $_POST['update_company'];}
        else {$update_company = '';}
        
        switch ($update_company) {
        case "update" : 
    	    if ($_POST['company_name'] !=""){
               $company_options = get_option('evr_company_settings');
               //$company_options = $_POST['company_settings'];
               $company_options['company']           = $_POST['company_name'];
               $company_options['company_street1']   = $_POST['company_street1'];
               $company_options['company_street2']   = $_POST['company_street2'];
               $company_options['company_city']      = $_POST['company_city'];
               $company_options['company_state']     = $_POST['company_state'];
               $company_options['company_postal']    = $_POST['company_postal'];
               $company_options['company_email']     = $_POST['email'];
               $company_options['evr_page_id']       = $_POST['evr_page_id'];
               $company_options['splash']            = $_POST['splash'];
               $company_options['send_confirm']      = $_POST['send_confirm'];
               $company_options['message']           = htmlentities2($_POST['message']);
               $company_options['wait_message']      = htmlentities2($_POST['wait_message']);
               $company_options['thumbnail']         = $_POST['thumbnail'];
               $company_options['calendar_url']      = $_POST['evr_page_id'];            //$_POST['calendar_url';
               $company_options['default_currency']  = $_POST['default_currency'];
               $company_options['donations']         = $_POST['donations']; 
               $company_options['checks']            = $_POST['checks']; 
               $company_options['pay_now']           = $_POST['pay_now'];    
               $company_options['payment_vendor']    = $_POST['payment_vendor'];
               $company_options['payment_vendor_id'] = $_POST['payment_vendor_id'];
               $company_options['payment_vendor_key']= $_POST['payment_vendor_key'];
               $company_options['pay_msg']           = $_POST['pay_msg'];
               $company_options['return_url']        = $_POST['return_url'];
               $company_options['notify_url']        = $_POST['notify_url'];
               $company_options['cancel_return']     = $_POST['cancel_return'];
               $company_options['return_method']     = $_POST['return_method'];
               $company_options['use_sandbox']       = $_POST['use_sandbox'];
               $company_options['image_url']         = $_POST['image_url'];
               $company_options['admin_message']     = htmlentities2($_POST['admin_message']);
               $company_options['pay_confirm']       = $_POST['pay_confirm'];
               $company_options['payment_subj']      = $_POST['payment_subj'];
               $company_options['payment_message']   = htmlentities2($_POST['payment_message']);
               $company_options['captcha']           = $_POST['captcha'];
               $company_options['event_pop']         = $_POST['event_pop'];
               $company_options['form_css']          = $_POST['form_css'];
               $start_of_week                        = $_POST['start_of_week'];
               $company_options['use_sales_tax']     = $_POST['use_sales_tax'];
               $company_options['sales_tax_rate']    = $_POST['sales_tax_rate'];
               $company_options['start_of_week']     = $_POST['start_of_week'];
               $company_options['evr_date_select']   = $_POST['evr_date_select'];
               $company_options['evr_cal_head']      = $_POST['evr_cal_head'];
               $company_options['cal_head_txt_clr']  = $_POST['cal_head_txt_clr'];
               $company_options['evr_cal_cur_day']   = $_POST['evr_cal_cur_day'];
               $company_options['evr_cal_use_cat']   = $_POST['evr_cal_use_cat']; //true-false
               $company_options['evr_cal_pop_border']= $_POST['evr_cal_pop_border'];
               $company_options['cal_day_txt_clr']   = $_POST['cal_day_txt_clr'];
               $company_options['evr_cal_day_head']  = $_POST['evr_cal_day_head'];
               $company_options['cal_day_head_txt_clr']  = $_POST['cal_day_head_txt_clr'];
               $company_options['evr_list_format']   =   $_POST['evr_list_format']; 
            //$company_options['evr_invoice'] = $_POST['evr_invoice'];
               update_option( 'evr_company_settings', $company_options);
               update_option( 'evr_start_of_week', $start_of_week);
               $dwolla_enabled                    = $_POST['enable_dwolla'];
               
               update_option('evr_dwolla',$dwolla_enabled);
	echo '<div id="message" class="updated fade"><p><strong>';
    _e('Configuration settings saved','evr_language');
       echo '</p></strong></div>';}
       else { ?>
       <div id="message" class="error"><p><strong><?php _e('There was an error in your submission, please try again. The configuration data was not updated!','evr_language'); print mysql_error(); ?>.</strong></p>
       <?php } ?>
        <p><strong><?php _e(' . . .Now refreshing configuration settings . . ','evr_language');?><meta http-equiv="Refresh" content="1; url=<?php echo $_SERVER["REQUEST_URI"]?>"></strong></p>
<?php 
        break;
        default:
        global $wpdb;
        ?>
<div class="evr_container">
<script>
                    var tinymceConfigs = [ {
                        theme : "advanced",        
                        mode : "none",        
                        language : "en",        
                        height:"200",        
                        width:"100%",        
                        theme_advanced_layout_manager : "SimpleLayout",        
                        theme_advanced_toolbar_location : "top",        
                        theme_advanced_toolbar_align : "left",        
                        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull",        
                        theme_advanced_buttons2 : "",        
                        theme_advanced_buttons3 : "" },
                            { 
                                theme : "advanced",        
                                mode : "none",
                                skin : "o2k7",        
                                language : "en",
                                height:"200",        
                                width:"100%",        
                                theme_advanced_layout_manager : "SimpleLayout",        
                                theme_advanced_toolbar_location : "top",        
                                theme_advanced_toolbar_align : "left"
                                }];
                    function tinyfy(settingid,el_id) {    
                        tinyMCE.settings = tinymceConfigs[settingid];    
                        tinyMCE.execCommand('mceAddControl', true, el_id);}
                    </script>	
<div class="wrap">
<h2><a href="http://www.wpeventregister.com"><img src="<?php echo EVR_PLUGINFULLURL ?>images/evr_icon.png" alt="Event Registration for Wordpress" /></a></h2>
    <h2><?php _e('Event Registration Configuration Settings','evr_language');?></h2>
    <ul class="tabs">
        <li><a href="#tab1"><?php _e('Contact Info','evr_language');?></a></li>
        <li><a href="#tab2"><?php _e('Payment Info','evr_language');?></a></li>
        <li><a href="#tab3"><?php _e('Captcha','evr_language');?></a></li>
        <li><a href="#tab4"><?php _e('Page Config','evr_language');?></a></li>
        <li><a href="#tab5"><?php _e('Confirmation Info','evr_language');?></a></li>
        <li><a href="#tab6"><?php _e('Waitlist','evr_language');?></a></li> 
        <li><a href="#tab7"><?php _e('Calendar','evr_language');?></a></li>
        <li><a href="#tab8"><?php _e('Tax','evr_language');?></a></li>
        <li><a href="#tab9"><?php _e('Done','evr_language');?></a></li>
    </ul>
    <div class="evr_tab_container">
    <form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
        <div id="tab1" class="tab_content">
            <div class="postbox " >
            <div class="inside">
                    <div class="padding">
                       <table class="form-table">
                        <tr valign="top">
                        <th scope="row"><label for="company"><?php _e('Company Name:','evr_language');?></label></th>
                        <td><input name="company_name" type="text" value="<?php echo stripslashes($company_options['company']);?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="company_street1"><?php _e('Company Street 1:','evr_language');?></label></th>
                        <td><input name="company_street1" type="text"  value="<?php echo stripslashes($company_options['company_street1']);?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="company_street2"><?php _e('Company Street 2:','evr_language');?></label></th>
                        <td><input name="company_street2" type="text" size="45" value="<?php echo stripslashes($company_options['company_street2']);?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="company_city"><?php _e('Company City:','evr_language');?></label></th>
                        <td><input name="company_city" type="text" size="45" value="<?php echo stripslashes($company_options['company_city']);?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="company_state"><?php _e('Company State:','evr_language');?></label></th>
                        <td><input name="company_state" type="text" size="3" value="<?php echo $company_options['company_state'];?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="company_zip"><?php _e('Company Postal Code:','evr_language');?></label></th>
                        <td><input name="company_postal" type="text" size="10" value="<?php echo $company_options['company_postal'];?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="contact"><?php _e('Primary contact email:','evr_language');?></label></th>
                        <td><input name="email" type="text" size="45" value="<?php echo $company_options['company_email'];?>" class="regular-text" /></td>
                        </tr>
                        </table>  
                      </div>  
                </div>
        </div>
        </div>
        <div id="tab2" class="tab_content">
            <div class="postbox " >
                <div class="inside">
                    <div class="padding">
                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row"><label for="payment_vendor"><?php _e('Online Payment Vendor:','evr_language');?></label></th>
                        <td><select name="payment_vendor" class="regular-select">
                            <option value="<?php  echo $company_options['payment_vendor'];?>"><?php  echo $company_options['payment_vendor'];?></option>
                            <option value="NONE">NONE</option>
                            <option value="AUTHORIZE">AUTHORIZE.NET</option>
                            <option value="GOOGLE">GOOGLE</option>
                            <option value="PAYPAL">PAYPAL</option>
                            <option value="MONSTER">MONSTER PAY</option>
                            <option value="CUSTOM">CUSTOM</option>
                        </select></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="pay_msg"><?php _e('Payment Message on Confirmation Screen','evr_language');?></label></th>
                        <td><input  name="pay_msg" value="<?php  
                        if ($company_options['pay_msg'] != ""){echo stripslashes($company_options['pay_msg']);} else {
                            _e("To pay online, please select the Payment button to be taken to our payment vendor's site.",'evr_language');
                        }
                        ?>"  maxlength="93" size="95"/></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"></th><td></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="payment_vendor_id"><?php _e('Online Payment ID','evr_language');?></label></th>
                        <td><input name="payment_vendor_id" value="<?php  echo $company_options['payment_vendor_id'];?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="txn_key"><?php _e('Authorized.Net Txn Key','evr_language');?></label></th>
                        <td><input name="payment_vendor_key" value="<?php  echo $company_options['payment_vendor_key'];?>" class="regular-text" /><a href="https://ems.authorize.net/oap/home.aspx?SalesRepID=98&ResellerID=16334">
                            <img src="http://www.authorize.net/images/reseller/oap_sign_up.gif" height="38" width="135" border="0"/></a> </td>
                        </tr>
                         <tr valign="top">
                        <th scope="row"><label for="pay_now"><?php _e('Payment Button Text','evr_language');?></label></th>
                        <td><input name="pay_now" value="<?php  if ($company_options['pay_now'] !=""){echo $company_options['pay_now'];} else {_e('PAY NOW');}?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="currency_format"><?php _e('Currency Format:','evr_language');?></label></th>
                        <td><select name = "default_currency" class="regular-select">
                            <option value="<?php  echo $company_options['default_currency'];?>" ><?php echo $company_options['default_currency'];?> </option>
                            <option value="USD">USD</option>
                            <option value="AUD">AUD</option>
                            <option value="GBP">GBP</option>
                            <option value="CAD">CAD</option>
                            <option value="CZK">CZK</option>
                            <option value="DKK">DKK</option>
                            <option value="EUR">EUR</option>
                            <option value="HKD">HKD</option>
                            <option value="HUF">HUF</option>
                            <option value="ILS">ILS</option>
                            <option value="JPY">JPY</option>
                            <option value="MXN">MXN</option>
                            <option value="NZD">NZD</option>
                            <option value="NOK">NOK</option>
                            <option value="PLN">PLN</option>
                            <option value="SGD">SGD</option>
                            <option value="SEK">SEK</option>
                            <option value="CHF">CHF</option>
                            </select></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="checka"><?php _e('Will you accept checks/cash?','evr_language');?></label></th>
                        <td><select name = 'checks' class="regular-select">
                            <option value="<?php  echo $company_options['checks'];?>"><?php  echo $company_options['checks'];?> </option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                            </select></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="accept_donations"><?php _e('Will you accept donations?','evr_language');?></label></th>
                        <td><select name = 'donations' class="regular-select">
                            <option value="<?php  echo $company_options['donations'];?>"><?php  echo $company_options['donations'];?> </option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                            </select></td>
                        </tr>
                        </table>
                        <hr /> 
                        <table class="form-table">
                        <tr><td colspan="2"><font color="red"><u>For Paypal Users Only</u></font></td></tr>
                        <tr valign="top">
                        <th scope="row"><label for="image_url"><?php _e('Image URL','evr_language');?><br /><font size="-6"><?php _e('(For your logo on PayPal page)','evr_language');?></font></label></th>
                        <td><input name="image_url" value="<?php  echo $company_options['image_url'];?>" class="regular-text" /></td>
                        </tr>
                        <tr><td colspan="2"></td></tr>    
                            <?php /* //comment out this and uncomment the other for IPN support! ?>
                            <input type="hidden" value="" name="cancel_return">	
                            <input type="hidden" value="" name="notify_url">
                            <input type="hidden" value="" name="return_method">
                            <input type="hidden" value="" name="use_sandbox">
                            <?php */
                            //Uncomment this code if you use Paypal IPN Support  
                            ?>
                        <tr valign="top">
                        <th scope="row"><label for="cancel_return"><?php _e('Cancel Return URL','evr_language');?><br /><font size="-6"><?php _e('(page you setup for cancelled payment)','evr_language');?></font></label></th>
                        <td><input name="cancel_return" value="<?php  echo $company_options['cancel_return'];?>" class="regular-text" /></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="notify_url"><?php _e('Notify URL','evr_language');?><br /><font size="-6"><?php _e('(used to process payments)','evr_language');?></font></label></th>
                        <td><input name="" value="No Selection Required - based registration page" class="regular-text" /></td>
                        </tr>    
                        <tr valign="top">
                        <th scope="row"><label for="return_method"><?php _e('Return Method:','evr_language');?></label></th>
                        <td><select name = "return_method" class="regular-select">
                            <?php  
                            if ($company_options['return_method']=="1"){echo "<option value='1'>".__('GET','evr_language')."</option>";}
                            if ($company_options['return_method']=="2"){echo "<option value='2'>".__('POST','evr_language')."</option>";}
                            ?>
                            <option value="1"><?php _e('GET');?></option>
                            <option value="2"><?php _e('POST');?></option>
                            </select></td>
                        </tr>
                        <tr valign="top">
                        <th scope="row"><label for="use_sandbox"><?php _e('Use PayPal Sandbox','evr_language');?><br /><font size="-6"><?php _e('(used for testing/debug)','evr_language');?></font></label></th>
                        <td><input type="radio" name="use_sandbox" value="Y" <?php  if ($company_options['use_sandbox']=="Y"){echo "checked";}?>/><?php _e('Yes','evr_language');?>
                        <input type="radio" name="use_sandbox" value="N" <?php  if ($company_options['use_sandbox']=="N"){echo "checked";}?>/><?php _e('No','evr_language');?>
                        </td>
                        </tr>
                         </table>
                    </div>  
                </div>
            </div>
        </div>
        <div id="tab3" class="tab_content">
            <div class="postbox " >
                <div class="inside">
                    
                    
                    <div class="padding">
                    <label for="captcha"><?php _e('Use Captcha on registration form?','evr_language');?></label>
                        <input name="captcha" type="radio" value="Y" class="regular-radio" <?php if ($company_options['captcha']=="Y"){echo "checked";}?> /><?php _e('Yes','evr_language');?>
                        <input name="captcha" type="radio" value="N" class="regular-radio" <?php if ($company_options['captcha']=="N"){echo "checked";}?> /><?php _e('No','evr_language');?>
                     </div>  
                     <div class="padding">
                    <label for="form_css"><?php _e('CSS Overrides for registration form?','evr_language');?></label>
                    <p><a class="ev_reg-fancylink" href="#css_override_help">Help</a> </p>
                    <textarea name="form_css" id="form_css" style="width: 100%; height: 300px;">
                        <?php echo $company_options['form_css'];?></textarea>
                        <br /> 
                        </div> 
                </div>
            </div>
        </div>
        <div id="tab4" class="tab_content">
            <div class="postbox " >
                <div class="inside">
                    <div class="padding">
                    <div class="padding">
                         <?php if(  evr_issetor($_POST['evr_page_id'])|| $company_options['evr_page_id']=='0' )
                         {
                          ?>
                        <p class="updated fade red_text" align="center"><strong><span>**<?php _e('Attention','evr_language');?>**</strong><br />
                        <?php _e('These settings must be configured for the plugin to function correctly.','evr_language');?></span>.</p>
                        <?php }?>	    
                            <p><?php _e('Main registration page','evr_language');?>:"
                            <select name="evr_page_id">
                            <option value="0">
                            <?php _e ('Main page','evr_language'); ?>
                            </option>
                            <?php parent_dropdown ($default=$company_options['evr_page_id']); ?>
                            </select>
                            <a class="ev_reg-fancylink" href="#registration_page_info">
                            <img src="<?php echo EVR_PLUGINFULLURL?>/images/question-frame.png" width="16" height="16" /></a><br />
                            <font  size="-2">(This page should contain the <strong>{EVRREGIS}</strong> filter. This page can be hidden from navigation, if desired.)</font></p>
                            <p><?php _e('Return URL for Payments','evr_language');?>:
                            <select name="return_url">
                            <option value="0"><?php _e ('Main page','evr_language'); ?></option>
                            <?php parent_dropdown ($default=$company_options['return_url']); ?>
                            </select>
                            <a class="ev_reg-fancylink" href="#payment_page_info"><img src="<?php echo EVR_PLUGINFULLURL?>/images/question-frame.png" width="16" height="16" /></a><br />
                            <font  size="-2">(This page should be hidden and will contain the EVR_PAYMENT payment shortcode. This page should be hidden from navigation.)</font></p>
                            <div id="registration_page_info" style="display:none">
                                <h2>Main Events Page</h2>
                                <p>This is the page that displays your events.</p>
                                <p>Additionally, all registration process pages will use this page as well.</p>
                                <p>This page should contain the <strong>{EVRREGIS}</strong> shortcode.</p>
                            </div>  
                             <div id="payment_page_info" style="display:none">
                                <h2>Return Payment Page</h2>
                                <p>This is the page that attendees return to view/make payments.</p>
                                <p>This is the page that PayPal IPN uses to post payments.</p>
                                <p>This page should contain the <strong>[EVR_PAYMENT]</strong> shortcode.</p>
                            </div> 
                    </div>
                    <div class="padding">
                    <label for="captcha"><?php _e('Select Event Listing Type','evr_language');?></label>
                        <input name="evr_list_format" type="radio" value="popup" class="regular-radio" <?php if ($company_options['evr_list_format']=="popup"){echo "checked";}?> /><?php _e('PopUp Window','evr_language');?>
                        <input name="evr_list_format" type="radio" value="accordian" class="regular-radio" <?php if ($company_options['evr_list_format']=="accordian"){echo "checked";}?> /><?php _e('Accordian List','evr_language');?>
                        <input name="evr_list_format" type="radio" value="link" class="regular-radio" <?php if ($company_options['evr_list_format']=="link"){echo "checked";}?> /><?php _e('Link Only','evr_language');?>
                        
                    </div>
                    
                    
                    </div>  
                </div>
            </div>
        </div>        
        <div id="tab5" class="tab_content">
            <div class="postbox " >
                <div class="inside">
                    <div class="padding">
                        <p><?php _e('Do you want to send Registration Confirmation emails?','evr_language');?><input type="radio" name="send_confirm" class="regular-radio" value="Y"  <?php if ($company_options['send_confirm'] == "Y"){echo "checked";}?> /><?php _e('Yes','evr_language');?>
                        <input type="radio" name="send_confirm" class="regular-radio" value="N"  <?php if ($company_options['send_confirm'] == "N"){echo "checked";}?> /><?php _e('No','evr_language');?><br />  
                        <font size="-5" color="red"><?php _e('(This option must be enable to send custom mails in events)','evr_language');?></font></p>
                        <p><a class="ev_reg-fancylink" href="#custom_email_settings">Settings</a> | <a class="ev_reg-fancylink" href="#custom_email_example"><?php _e('Example','evr_language');?></a></p>
                        <p><?php _e('Email Body','evr_language');?>:   
                        <?php
                        $settings = array(
                                		'media_buttons' => false,
                                        'quicktags' => array('buttons' => 'b,i,ul,ol,li,link,close'),
                                		'tinymce' => array('theme_advanced_buttons1' => 'bold,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,fullscreen')
                                	);
                     
                    if (function_exists('wp_editor')){
                    wp_editor( stripslashes($company_options['message']), 'message', $settings );}
				    else{ ?>
                     <a href="javascript:void(0)" onclick="tinyfy(1,'message')"><input type="button" value="WYSIWG"/></a>
                     <br />
                     <textarea name="message" id="message" style="width: 100%; height: 200px;">
                     <?php echo stripslashes($company_options['message']);?></textarea>       
                    <?php } 
                        ?>
                        </p>
                        </div> 
                    <hr />
                    <div class="padding">
                        <p><?php _e('Do you want to send Payment Confirmation emails?','evr_language');?>
                        <input type="radio" name="pay_confirm" class="regular-radio" value="Y"  <?php if ($company_options['pay_confirm'] == "Y"){echo "checked";}?> /><?php _e('Yes','evr_language');?>
                        <input type="radio" name="pay_confirm" class="regular-radio" value="N"  <?php if ($company_options['pay_confirm'] == "N"){echo "checked";}?> /><?php _e('No','evr_language');?><br />  
                        <font size="-5" color="red">(This option must be enable to send payment confrimation emails)</font>
                        </p>
                        <p><a class="ev_reg-fancylink" href="#custom_payment_email_settings"><?php _e('Settings','evr_language');?></a> | 
                        <a class="ev_reg-fancylink" href="#custom_payment_email_example"><?php _e('Example','evr_language');?></a></p>
                        <br />
                        <p><label for="payment_subj"><?php _e('Payment Message Subject','evr_language');?></label><input name="payment_subj" value="<?php  echo $company_options['payment_subj'];?>" class="regular-text" /></p>
                        <p>Email Body:   
                        <?php
                        if (function_exists('wp_editor')){  
                            wp_editor(stripslashes($company_options['payment_message']), 'payment_message', $settings );}
                        else { 
                        ?>
                        <a href="javascript:void(0)" onclick="tinyfy(1,'payment_message')"><input type="button" value="WYSIWG"/></a><br />
                        <textarea name="payment_message" id="payment_message" style="width: 100%; height: 200px;">
                        <?php echo stripslashes($company_options['payment_message']);?></textarea>
                        <br /> 
                        <?php
                       	}
                        ?> 
                        </p>
                    <div style="clear:both;"></div>
                    </div>   
                </div>
            </div>
        </div> 
         <div id="tab6" class="tab_content">
            <div class="postbox " >
                <div class="inside">
                    <div class="padding">
                        <p><a class="ev_reg-fancylink" href="#custom_wait_settings"><?php _e('Settings','evr_language');?></a> | 
                        <a class="ev_reg-fancylink" href="#custom_wait_example"><?php _e('Example','evr_language');?></a></p>
                        <p><?php _e('Waitlist Email Body','evr_language');?>:   
                        <?php
                        $settings = array(
                                		'media_buttons' => false,
                                        'quicktags' => array('buttons' => 'b,i,ul,ol,li,link,close'),
                                		'tinymce' => array('theme_advanced_buttons1' => 'bold,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,fullscreen')
                                	);
                    if (function_exists('wp_editor')){
                    wp_editor( stripslashes($company_options['wait_message']), 'wait_message', $settings );}
				    else{ ?>
                     <a href="javascript:void(0)" onclick="tinyfy(1,'wait_message')"><input type="button" value="WYSIWG"/></a>
                     <br />
                     <textarea name="wait_message" id="wait_message" style="width: 100%; height: 200px;">
                     <?php echo stripslashes($company_options['wait_message']);?></textarea>       
                    <?php } 
                        ?>
                        </p>
                   </div>     
                </div>
            </div>
        </div> 
        <div id="tab7" class="tab_content">
            <div class="postbox " >
                <div class="inside">
                    <div class="padding">
                   <p><h2><?php _e('Calendar Settings','evr_language');?></h2></p>
                   <br />
                   <p><label><?php _e('Start Day of Week','evr_language');?> <select name="start_of_week">
                   <?php if (get_option('evr_start_of_week') == 0){ ?>
                    <option value="0">Sunday</option>
                    <?php  } if (get_option('evr_start_of_week') == 1){ ?>
                    <option value="1"><?php _e('Monday','evr_language');?></option>
                     <?php  } ?>                    
                    <option value="0"><?php _e('Sunday','evr_language');?></option>
                    <option value="1"><?php _e('Monday','evr_language');?></option>
                    </select></label></p>
                    <p><?php _e('Do you want to use Category color coding?','evr_language');?>
                        <input type="radio" name="evr_cal_use_cat" class="regular-radio" value="Y"  <?php if ($company_options['evr_cal_use_cat'] == "Y"){echo "checked";}?> /><?php _e('Yes','evr_language');?>
                        <input type="radio" name="evr_cal_use_cat" class="regular-radio" value="N"  <?php if ($company_options['evr_cal_use_cat'] == "N"){echo "checked";}?> /><?php _e('No','evr_language');?><br />  
                        </p>
                        <p><?php _e('Select color for Calendar Display','evr_language');?>:</p>
                                        <script type="text/javascript" charset="utf-8">
                                         jQuery(document).ready(function() {    
                                                jQuery('#picker').hide();    
                                                /* jQuery('#picker').farbtastic("#cat_back"); */
                                                jQuery.farbtastic('#picker').linkTo('#evr_cal_head');   
                                                jQuery("#evr_cal_head").click(function(){jQuery('#picker').slideToggle()});  
                                                });
                                         jQuery(document).ready(function() {    
                                                jQuery('#daypicker').hide();    
                                                jQuery.farbtastic('#daypicker').linkTo('#evr_cal_cur_day');   
                                                jQuery("#evr_cal_cur_day").click(function(){jQuery('#daypicker').slideToggle()});  
                                                });
                                         jQuery(document).ready(function() {    
                                                jQuery('#brdrpicker').hide();    
                                                /* jQuery('#picker').farbtastic("#cat_back"); */
                                                jQuery.farbtastic('#brdrpicker').linkTo('#evr_cal_pop_border');   
                                                jQuery("#evr_cal_pop_border").click(function(){jQuery('#brdrpicker').slideToggle()});  
                                                });
                                         jQuery(document).ready(function() {    
                                                jQuery('#hdrpicker').hide();    
                                                jQuery.farbtastic('#hdrpicker').linkTo('#evr_cal_day_head');   
                                                jQuery("#evr_cal_day_head").click(function(){jQuery('#hdrpicker').slideToggle()});  
                                                });
                                        </script>
                                        <small><?php _e('Click on each field to display the color picker. Click again to close it.','evr_language');?></small>
                                        <hr />
                                        <p><?php _e('Do you want to use the Date selector?','evr_language');?>
                        <input type="radio" name="evr_date_select" class="regular-radio" value="Y"  <?php if ($company_options['evr_date_select'] == "Y"){echo "checked";}?> /><?php _e('Yes','evr_language');?>
                        <input type="radio" name="evr_date_select" class="regular-radio" value="N"  <?php if ($company_options['evr_date_select'] == "N"){echo "checked";}?> /><?php _e('No','evr_language');?><br />  
                        </p>
                                        <p><label for="color"><?php _e('Calender Date Selector Background Color','evr_language');?>: 
                                        <input type="text" id="evr_cal_head" name="evr_cal_head" value="<?php if ($company_options['evr_cal_head'] !="") {echo $company_options['evr_cal_head'];} else {echo "#583c32";}?>"  style="width: 195px"/>
                                        </label><div id="picker" style="margin-bottom: 1em;"></div></p><p><?php _e('Selector Text Color','evr_language');?>: <select style="width:70px;" name='cal_head_txt_clr' >
                                        <option value="<?php  echo $company_options['cal_head_txt_clr'];?>"><?php if ($company_options['cal_head_txt_clr']=="#000000"){echo "Black";} if ($company_options['cal_head_txt_clr']=="#FFFFFF"){echo "White";} ?></option>
                                        <option value="#000000"><?php _e('Black','evr_language');?></option>
                                        <option value="#FFFFFF"><?php _e('White','evr_language');?></option></select></p>
                                        <hr />
                                        <p><label for="color"><?php _e('Calender Day Header Background Color','evr_language');?>: 
                                        <input type="text" id="evr_cal_day_head" name="evr_cal_day_head" value="<?php  if ($company_options['evr_cal_day_head'] !=""){
                                        echo $company_options['evr_cal_day_head'];} else {echo "#b8ced6";}?>"  style="width: 195px"/>
                                        </label><div id="hdrpicker" style="margin-bottom: 1em;"></div></p>
                                        <p><?php _e('Selector Text Color','evr_language');?>: <select style="width:70px;" name='cal_day_head_txt_clr' >
                                        <option value="<?php  echo $company_options['cal_day_head_txt_clr'];?>"><?php if ($company_options['cal_day_head_txt_clr']=="#000000"){echo "Black";} if ($company_options['cal_day_head_txt_clr']=="#FFFFFF"){echo "White";} ?></option>
                                        <option value="#000000"><?php _e('Black','evr_language');?></option>
                                        <option value="#FFFFFF"><?php _e('White','evr_language');?></option></select></p>
                                        <hr />
                                        <p><label for="color"><?php _e('Current Day Background Color','evr_language');?>: 
                                        <input type="text" id="evr_cal_cur_day" name="evr_cal_cur_day" value="<?php if ($company_options['evr_cal_cur_day'] !="") {echo $company_options['evr_cal_cur_day'];} else {echo  "#b8ced6"; }
                                        ?>"  style="width: 195px"/>
                                        </label><div id="daypicker" style="margin-bottom: 1em;"></div></p>
                                        <p><?php _e('Current Day Text Color','evr_language');?>: <select style="width:70px;" name='cal_day_txt_clr' >
                                        <option value="<?php  echo $company_options['cal_day_txt_clr'];?>"><?php if ($company_options['cal_day_txt_clr']=="#000000"){echo "Black";} if ($company_options['cal_day_txt_clr']=="#FFFFFF"){echo "White";} ?></option>
                                        <option value="#000000"><?php _e('Black','evr_language');?></option>
                                        <option value="#FFFFFF"><?php _e('White','evr_language');?></option></select></p>
                                        <hr />
                                        <p><label for="color"><?php _e('Description Pop Border Color','evr_language');?>: 
                                        <input type="text" id="evr_cal_pop_border" name="evr_cal_pop_border" value="<?php  if ($company_options['evr_cal_pop_border'] !=""){ echo $company_options['evr_cal_pop_border'];} else {echo  "#b8ced6";}?>"  style="width: 195px"/>
                                        </label><div id="brdrpicker" style="margin-bottom: 1em;"></div></p>
                    </div>  
                </div>
            </div>
        </div>
         <div id="tab8" class="tab_content">
            <div class="postbox " >
                <div class="inside">
                    <div class="padding">
                      <p><?php _e('Do you want to charge sales tax','evr_language');?> <input type="radio" name="use_sales_tax" class="regular-radio" value="Y"  <?php if ($company_options['use_sales_tax'] == "Y"){echo "checked";}?> /><?php _e('Yes','evr_language');?>
                        <input type="radio" name="use_sales_tax" class="regular-radio" value="N"  <?php if (($company_options['use_sales_tax'] == "N")||($company_options['use_sales_tax'] != "Y")){echo "checked";}?> /><?php _e('No','evr_language');?><br />  
                        <font size="-5" color="red"><?php _e('(This option must be enable to charge sales tax)','evr_language');?></font></p>
                    <table class="form-table">
                        <tr valign="top">
                        <th scope="row"><label for="sales_tax_rate"><?php _e('Sales Tax Rate: ','evr_language');?><br /><?php _e('(must be decimal, i.e. .085 )','evr_language');?></label></th>
                        <td><input name="sales_tax_rate" type="text"  value="<?php echo $company_options['sales_tax_rate'];?>" class="regular-text" /></td>
                        </tr>
                        </table>  
                    </div>  
                </div>
            </div>
        </div>                  
        <div id="tab9" class="tab_content">
            <div class="postbox " >
                <div class="inside">
                    <div class="padding">
                   <p><?php _e('Congratulations','evr_language');?>!</p>
                   <p><?php _e('You have setup your company information.  Please click the','evr_language');?>  <B> 
                   <?php _e('UPDATE COMPANY SETTINGS','evr_language');?></B> 
                   <?php _e('button, below the tabs','evr_language');?>.</p>
                    </div>  
                </div>
            </div>
        </div>        
<div style="display:none;"><div id="custom_email_settings" style="width:650px;height:350px;overflow:auto;">
    <h2>Email Settings</h2><p><strong>Email Confirmations:</strong><br>
    For customized confirmation emails, the following tags can be placed in the email form and they will pull data from the database to include in the email.</p>
    <p>[id},[fname], [lname], [phone], [event],[description], [cost], [company], [co_add1], [co_add2], [co_city],[co_state], [co_zip],[contact], [payment_url], [start_date], [start_time], [end_date], [end_time]</p>
</div>
</div>   
<div style="display:none;"><div id="custom_email_example" style="width:650px;height:350px;overflow:auto;">
    <h2>Sample Mail Send:</h2>
    <p>***This is an automated response - Do Not Reply***</p>
    <p>Thank you [fname] [lname] for registering for [event]. We hope that you will find this event both informative and enjoyable. Should have any questions, please contact [contact].</p>
    <p>If you have not done so already, please submit your payment in the amount of [cost].</p>
    <p>Your unique registration ID is: [id].</p>
    <p>Click here to review your payment information [payment_url].</p>
    <p>Thank You.</p>
</div>
</div>
<div style="display:none;"><div id="custom_wait_settings" style="width:650px;height:350px;overflow:auto;">
    <h2>Email Settings</h2><p><strong>Waitlist:</strong><br>
    For customized wait list emails, the following tags can be placed in the email form and they will pull data from the database to include in the email.</p>
    <p>[fname], [lname], [event]</p>
</div>
</div>
<div style="display:none;"><div id="custom_wait_example" style="width:650px;height:350px;overflow:auto;">
    <p>Thank you [fname] [lname] for your interest in registering for [event].</p>
    <p>At this time, all seats for the event have been taken.  
    Your information has been placed on our waiting list.  
    The waiting list is on a first come, first serve basis.</p>  
    <p>You will be notified by email with directions for completing registration and payment should a seat become available.</p>
    <p>Thank You</p>
</div>
</div>
<div style="display:none;"><div id="custom_payment_email_settings" style="width:650px;height:350px;overflow:auto;">
    <h2>Payment Confirmation Email Settings</h2><p><strong>Payment Confirmations:</strong><br>
    For customized payment confirmation emails, the following tags can be placed in the email form and they will pull data from the database to include in the email.</p>
    <p>[id],[fname], [lname], [payer_email], [event_name],[amnt_pd], [txn_id],[address_street],[address_city],[address_state],[address_zip],[address_country],[start_date],[start_time],[end_date],[end_time] 
</div></div> 
<div style="display:none;"><div id="custom_payment_email_example" style="width:650px;height:350px;overflow:auto;">
    <h2>Sample Payment Mail Send:</h2>
    <p>***This is an automated response - Do Not Reply***</p>
    <p>Thank you [fname] [lname] for your recent payment of [amnt_pd] ([txn_id]) for [event_name]. We hope that you will find this event both informative and enjoyable. Should have any questions, please contact [contact].</p>
    <p>Your unique registration ID is: [id].</p>
    <p>Click here to review your payment information [payment_url].</p>
    <p>Thank You.</p>
</div> </div>     
<div style="display:none;"><div id="css_override_help" style="width:650px;height:350px;overflow:auto;">
    <p>enter css to override theme css on form</p>
    <p>D0 NOT use style  tags (< style > </ style >)</p>
</div></div>     
</div>
<div style="clear: both; display: block; padding: 10px 0; text-align:center;"><font color="blue"><?php _e('Please make sure you complete each section before submitting!','evr_language');?></font></div>
 <p align="center">
 <input type="hidden" name="update_company" value="update">
 <input  type="submit" name="update_button" value="<?php _e('Update Configuration Settings','evr_language'); ?>" id="update_button" /></form></p>
 <div style="clear: both; display: block; padding: 10px 0; text-align:center;">If you find this plugin useful, please contribute to enable its continued development!<br />
<p align="center">
<!--New Button for wpeventregister.com-->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="4G8G3YUK9QEDA">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
</div></div>
<?php
    break;
    }
}
?>