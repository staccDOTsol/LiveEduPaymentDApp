<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function ETHEREUM_ICO_options_page() {

	// Require admin privs
	if ( ! current_user_can( 'manage_options' ) )
		return false;
	
	$new_options = array();
	
	// Which tab is selected?
	$possible_screens = array( 'default', 'floating' );
	$current_screen = ( isset( $_GET['action'] ) && in_array( $_GET['action'], $possible_screens ) ) ? $_GET['action'] : 'default';
	
	if ( isset( $_POST['Submit'] ) ) {
		
		// Nonce verification 
		check_admin_referer( 'ethereum-ico-update-options' );

        // Standard options screen

        $new_options['tokenname']        = ( ! empty( $_POST['ETHEREUM_ICO_token_name'] )       /*&& is_numeric( $_POST['ETHEREUM_ICO_token_name'] )*/ )       ? sanitize_text_field($_POST['ETHEREUM_ICO_token_name'])        : 'TESTCOIN';
        $new_options['gaslimit']         = ( ! empty( $_POST['ETHEREUM_ICO_gaslimit'] )         && is_numeric( $_POST['ETHEREUM_ICO_gaslimit'] ) )             ? intval(sanitize_text_field($_POST['ETHEREUM_ICO_gaslimit']))  : 200000;
        $new_options['gasprice']         = ( ! empty( $_POST['ETHEREUM_ICO_gasprice'] )         && is_numeric( $_POST['ETHEREUM_ICO_gasprice'] ) )             ? intval(sanitize_text_field($_POST['ETHEREUM_ICO_gasprice']))  : 200000;
        $new_options['base_currency'] = ( ! empty( $_POST['ETHEREUM_ICO_base_currency'] ) ) ? sanitize_text_field($_POST['ETHEREUM_ICO_base_currency']) : __('ETH', 'ethereum-ico');
        $new_options['base_symbol'] = ( ! empty( $_POST['ETHEREUM_ICO_base_symbol'] ) ) ? sanitize_text_field($_POST['ETHEREUM_ICO_base_symbol'])  :  'Ξ';
        $new_options['placeholder']      = ( ! empty( $_POST['ETHEREUM_ICO_placeholder'] )      /*&& is_numeric( $_POST['ETHEREUM_ICO_placeholder'] )*/ )      ? sanitize_text_field($_POST['ETHEREUM_ICO_placeholder'])       : __('Input ETH amount', 'ethereum-ico');
        $new_options['step']             = ( ! empty( $_POST['ETHEREUM_ICO_step'] )             && is_numeric( $_POST['ETHEREUM_ICO_step'] ) )                 ? floatval(sanitize_text_field($_POST['ETHEREUM_ICO_step']))    : 0.1;
        $new_options['min']              = ( ! empty( $_POST['ETHEREUM_ICO_min'] )              && is_numeric( $_POST['ETHEREUM_ICO_min'] ) )                  ? floatval(sanitize_text_field($_POST['ETHEREUM_ICO_min']))     : 0;
        $new_options['hardcap']          = ( ! empty( $_POST['ETHEREUM_ICO_hardcap'] )          && is_numeric( $_POST['ETHEREUM_ICO_hardcap'] ) )              ? floatval(sanitize_text_field($_POST['ETHEREUM_ICO_hardcap'])) : '';
        $new_options['buyButtonText']    = ( ! empty( $_POST['ETHEREUM_ICO_buyButtonText'] )    /*&& is_numeric( $_POST['ETHEREUM_ICO_buyButtonText'] )*/ )    ? sanitize_text_field($_POST['ETHEREUM_ICO_buyButtonText'])     : __('Buy token with Metamask', 'ethereum-ico');
        $new_options['description']      = ( ! empty( $_POST['ETHEREUM_ICO_description'] )      /*&& is_numeric( $_POST['ETHEREUM_ICO_description'] )*/ )      ? sanitize_text_field($_POST['ETHEREUM_ICO_description'])       : '';
        $new_options['blockchain_network']  = ( ! empty( $_POST['ETHEREUM_ICO_blockchain_network'] )  /*&& is_numeric( $_POST['ETHEREUM_ICO_blockchain_network'] )*/ )  ? sanitize_text_field($_POST['ETHEREUM_ICO_blockchain_network'])   : '';
        $new_options['etherscanApiKey']  = ( ! empty( $_POST['ETHEREUM_ICO_etherscanApiKey'] )  /*&& is_numeric( $_POST['ETHEREUM_ICO_etherscanApiKey'] )*/ )  ? sanitize_text_field($_POST['ETHEREUM_ICO_etherscanApiKey'])   : '';
        $new_options['infuraApiKey']     = ( ! empty( $_POST['ETHEREUM_ICO_infuraApiKey'] )     /*&& is_numeric( $_POST['ETHEREUM_ICO_infuraApiKey'] )*/ )     ? sanitize_text_field($_POST['ETHEREUM_ICO_infuraApiKey'])      : '';
        $new_options['icostart']         = ( ! empty( $_POST['ETHEREUM_ICO_icostart'] )         /*&& is_numeric( $_POST['ETHEREUM_ICO_icostart'] )*/ )         ? sanitize_text_field($_POST['ETHEREUM_ICO_icostart'])          : '';
        $new_options['icoperiod']        = ( ! empty( $_POST['ETHEREUM_ICO_icoperiod'] )        && is_numeric( $_POST['ETHEREUM_ICO_icoperiod'] ) )            ? intval(sanitize_text_field($_POST['ETHEREUM_ICO_icoperiod'])) : 30;
        $new_options['tokenAddress']     = ( ! empty( $_POST['ETHEREUM_ICO_tokenAddress'] )     /*&& is_numeric( $_POST['ETHEREUM_ICO_tokenAddress'] )*/ )     ? sanitize_text_field($_POST['ETHEREUM_ICO_tokenAddress'])      : '';
        $new_options['crowdsaleAddress'] = ( ! empty( $_POST['ETHEREUM_ICO_crowdsaleAddress'] ) /*&& is_numeric( $_POST['ETHEREUM_ICO_crowdsaleAddress'] )*/ ) ? sanitize_text_field($_POST['ETHEREUM_ICO_crowdsaleAddress'])  : '';
        // it should be int, but usually, it is a very big int PHP can not handle, like 1000000000000000000
        $new_options['decimals']         = ( ! empty( $_POST['ETHEREUM_ICO_decimals'] )         && is_numeric( $_POST['ETHEREUM_ICO_decimals'] ) )             ? sanitize_text_field($_POST['ETHEREUM_ICO_decimals'])          : '';
        $new_options['tokenRate']        = ( ! empty( $_POST['ETHEREUM_ICO_tokenRate'] )        && is_numeric( $_POST['ETHEREUM_ICO_tokenRate'] ) )            ? doubleval(sanitize_text_field($_POST['ETHEREUM_ICO_tokenRate'])) : 1;
        $new_options['coinList']         = ( ! empty( $_POST['ETHEREUM_ICO_coinList'] )     /*&& is_numeric( $_POST['ETHEREUM_ICO_infuraApiKey'] )*/ )         ? sanitize_text_field($_POST['ETHEREUM_ICO_coinList'])          : '';
        $new_options['showIcons']        = ( ! empty( $_POST['ETHEREUM_ICO_showIcons'] )     /*&& is_numeric( $_POST['ETHEREUM_ICO_infuraApiKey'] )*/ )        ? sanitize_text_field($_POST['ETHEREUM_ICO_showIcons'])         : '';
        $new_options['openexchangeratesAppId']        = ( ! empty( $_POST['ETHEREUM_ICO_openexchangeratesAppId'] )     /*&& is_numeric( $_POST['ETHEREUM_ICO_infuraApiKey'] )*/ )        ? sanitize_text_field($_POST['ETHEREUM_ICO_openexchangeratesAppId'])         : '';

		// Get all existing EthereumICO options
		$existing_options = get_option( 'ethereum-ico_options', array() );
		
		// Merge $new_options into $existing_options to retain EthereumICO options from all other screens/tabs
		if ( $existing_options ) {
			$new_options = array_merge( $existing_options, $new_options );
		}
		
        if ( get_option('ethereum-ico_options') ) {
            update_option('ethereum-ico_options', $new_options);
        } else {
            $deprecated=' ';
            $autoload='no';
            add_option('ethereum-ico_options', $new_options, $deprecated, $autoload);
        }
		
		?>
		<div class="updated"><p><?php _e( 'Settings saved.' ); ?></p></div>
		<?php
		
	} else if ( isset( $_POST['Reset'] ) ) {
		// Nonce verification 
		check_admin_referer( 'ethereum-ico-update-options' );
		
		delete_option( 'ethereum-ico_options' );
	}

	$options = stripslashes_deep( get_option( 'ethereum-ico_options', array() ) );
	
	?>
	
	<div class="wrap">
	
	<h1><?php _e( 'EthereumICO Settings', 'ethereum-ico' ); ?></h1>
    <script type="text/javascript">
        /*function ICO_admin_calc_factor (currency_old, currency_new) {
            if (currency_old === currency_new) {
                console.log("currency_old === currency_new: ", currency_old);
                return 1;
            }
            var rateData = window.ico.rateData;
            var rateDataFiat = window.ico.rateDataFiat;
            var tokenName = jQuery('#ETHEREUM_ICO_token_name').val();
            if ("<?php // _e('ETH', 'ethereum-ico'); ?>" === currency_old) {
                if (tokenName === currency_new) {
                    var tokenRate = parseFloat(jQuery('#ETHEREUM_ICO_tokenRate').val());
                    return tokenRate;
                }
                else if ("BTC" === currency_new) {
                    return parseFloat(rateData.ethbtc);
                }
                else if ("USD" === currency_new) {
                   return parseFloat(rateData.ethusd);
                } 
                else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[currency_new]) {
                    return parseFloat(rateData.ethusd) * rateDataFiat[currency_new];
                } 
                else {
                    alert("<?php // echo sprintf(__('Currency is unknown or the %s is not configured correctly.', 'ethereum-ico'), __("openexchangerates.org App Id", 'ethereum-ico')) ?>");
                    return 1;
                }
            } else if (tokenName === currency_old) {
                var tokenRate = parseFloat(jQuery('#ETHEREUM_ICO_tokenRate').val());
                if ("<?php // _e('ETH', 'ethereum-ico'); ?>" === currency_new) {
                    return 1 / tokenRate;
                }
                else if ("BTC" === currency_new) {
                    return parseFloat(rateData.ethbtc) / tokenRate;
                }
                else if ("USD" === currency_new) {
                   return parseFloat(rateData.ethusd) / tokenRate;
                }
                else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[currency_new]) {
                    return parseFloat(rateData.ethusd) * rateDataFiat[currency_new] / tokenRate;
                }
                else {
                    alert("<?php // echo sprintf(__('Currency is unknown or the %s is not configured correctly.', 'ethereum-ico'), __("openexchangerates.org App Id", 'ethereum-ico')) ?>");
                    return 1;
                }
            } else if ("BTC" === currency_old) {
                if (tokenName === currency_new) {
                    var tokenRate = parseFloat(jQuery('#ETHEREUM_ICO_tokenRate').val());
                    return tokenRate / parseFloat(rateData.ethbtc);
                }
                else if ("<?php // _e('ETH', 'ethereum-ico'); ?>" === currency_new) {
                    return 1 / parseFloat(rateData.ethbtc);
                }
                else if ("USD" === currency_new) {
                   return parseFloat(rateData.ethusd) / parseFloat(rateData.ethbtc);
                }
                else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[currency_new]) {
                    return parseFloat(rateData.ethusd) * rateDataFiat[currency_new] / parseFloat(rateData.ethbtc);
                }
                else {
                    alert("<?php // echo sprintf(__('Currency is unknown or the %s is not configured correctly.', 'ethereum-ico'), __("openexchangerates.org App Id", 'ethereum-ico')) ?>");
                    return 1;
                }
            } else if ("USD" === currency_old) {
                if (tokenName === currency_new) {
                    var tokenRate = parseFloat(jQuery('#ETHEREUM_ICO_tokenRate').val());
                    return tokenRate / parseFloat(rateData.ethusd);
                }
                else if ("BTC" === currency_new) {
                    return parseFloat(rateData.ethbtc) / parseFloat(rateData.ethusd);
                }
                else if ("<?php // _e('ETH', 'ethereum-ico'); ?>" === currency_new) {
                   return 1 / parseFloat(rateData.ethusd);
                }
                else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[currency_new]) {
                    return rateDataFiat[currency_new] / parseFloat(rateData.ethusd);
                }
                else {
                    alert("<?php // echo sprintf(__('Currency is unknown or the %s is not configured correctly.', 'ethereum-ico'), __("openexchangerates.org App Id", 'ethereum-ico')) ?>");
                    return 1;
                }
            } else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[currency_old]) {
                if (tokenName === currency_new) {
                    var tokenRate = parseFloat(jQuery('#ETHEREUM_ICO_tokenRate').val());
                    return tokenRate / (parseFloat(rateData.ethusd) * rateDataFiat[currency_old]);
                }
                else if ("BTC" === currency_new) {
                    return parseFloat(rateData.ethbtc) / (parseFloat(rateData.ethusd) * rateDataFiat[currency_old]);
                }
                else if ("<?php // _e('ETH', 'ethereum-ico'); ?>" === currency_new) {
                   return 1 / (parseFloat(rateData.ethusd) * rateDataFiat[currency_old]);
                }
                else if ("USD" === currency_new) {
                   return 1 / (parseFloat(rateData.ethusd) * rateDataFiat[currency_old]);
                }
                else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[currency_new]) {
                    return rateDataFiat[currency_new] / (parseFloat(rateData.ethusd) * rateDataFiat[currency_old]);
                }
                else {
                    alert("<?php // echo sprintf(__('Currency is unknown or the %s is not configured correctly.', 'ethereum-ico'), __("openexchangerates.org App Id", 'ethereum-ico')) ?>");
                    return 1;
                }
            } else {
                alert("<?php // echo sprintf(__('Currency is unknown or the %s is not configured correctly.', 'ethereum-ico'), __("openexchangerates.org App Id", 'ethereum-ico')) ?>");
                return 1;
            }
        }*/
        function ICO_admin_change_ETHEREUM_ICO_base_currency (e) {
            if (e) {
                e.preventDefault();
            }
            var currency = jQuery("#ETHEREUM_ICO_base_currency").val();
            if ("" === currency) {
                currency = "<?php _e('ETH', 'ethereum-ico'); ?>";
                jQuery("#ETHEREUM_ICO_base_currency").val(currency);
            }
            var re = new RegExp(window.ico.initial_base_currency, 'g');
            
            var elems = [
                'ETHEREUM_ICO_placeholder'
                , 'ETHEREUM_ICO_step'
                , 'ETHEREUM_ICO_min'
                , 'ETHEREUM_ICO_max'
                , 'ETHEREUM_ICO_tokenRate'
                , 'ETHEREUM_ICO_softcap'
                , 'ETHEREUM_ICO_hardcap'
            ];
            
            //var factor = ICO_admin_calc_factor (window.ico.initial_base_currency, currency);
            
            elems.forEach(function(el){
                var $el = jQuery('#' + el);
                var placeholder = $el.attr('placeholder');
                if (!jQuery.isNumeric( placeholder )) {
                    $el.attr('placeholder', placeholder.replace(re, currency));
                } else {
                    //$el.attr('placeholder', parseFloat(placeholder) * factor);
                }
                var value = $el.val();
                if (!(jQuery.isNumeric( value ) || ("" === value && jQuery.isNumeric( placeholder )))) {
                    $el.val(value.replace(re, currency));
                } else {
                    if ("" === value) {
                        if ("" === placeholder || "0" === placeholder) {
                            $el.val("");
                        } else {
                            //$el.val(parseFloat(placeholder) * factor);
                        }
                    } else {
                        //$el.val(parseFloat(value) * factor);
                    }
                }
                $el.parent().find('p').each(function(){
                    var $p = jQuery(this);
                    $p.text($p.text().replace(re, currency));
                });
                var $title = jQuery($el.parent().parent().parent().parent().children()[0]);
                $title.html($title.html().replace(re, currency));
            });
            
            window.ico.initial_base_currency = currency;
        }
        function ICO_admin_init () {
            jQuery("#ETHEREUM_ICO_base_currency").change(ICO_admin_change_ETHEREUM_ICO_base_currency);
            if ('undefined' === typeof window['ico']) {
                window['ico'] = {};
            }
            window.ico.initial_base_currency = "<?php _e('ETH', 'ethereum-ico'); ?>";
            <?php
                $rateData = ETHEREUM_ICO_get_rate_data();
                $rateDataFiat = ETHEREUM_ICO_get_rate_data_fiat();
            ?>
            window.ico.rateData = <?php echo $rateData; ?>;
            window.ico.rateDataFiat = <?php echo $rateDataFiat; ?>;
            ICO_admin_change_ETHEREUM_ICO_base_currency();
        }
        jQuery(document).ready(ICO_admin_init);
//        // proper init if loaded by ajax
//        jQuery(document).ajaxComplete(function( event, xhr, settings ) {
//            ICO_admin_init();
//        });
    </script>
	
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo admin_url( 'options-general.php?page=ethereum-ico' ); ?>" class="nav-tab<?php if ( 'default' == $current_screen ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Standard', 'ethereum-ico' ); ?></a>
	</h2>

	<form id="ethereum-ico_admin_form" method="post" action="">
	
	<?php wp_nonce_field('ethereum-ico-update-options'); ?>

		<table class="form-table">
		
		<?php if ( 'default' == $current_screen ) : ?>
			<tr valign="top">
			<th scope="row"><?php _e("Token Symbol", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_token_name" id="ETHEREUM_ICO_token_name" type="text" maxlength="32" placeholder="TESTCOIN" value="<?php echo ! empty( $options['tokenname'] ) ? esc_attr( $options['tokenname'] ) : 'TESTCOIN'; ?>">
                    <p><?php _e('The symbol of your ICO token. E.g. TSX, not "Test Coin".', 'ethereum-ico'); ?></p>
                </label>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php _e("Gas Limit", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_gaslimit" id="ETHEREUM_ICO_gaslimit" type="number" min="0" step="10000" maxlength="8" placeholder="200000" value="<?php echo ! empty( $options['gaslimit'] ) ? esc_attr( $options['gaslimit'] ) : '200000'; ?>">
                    <p><?php _e('The gas limit to buy your ICO token', 'ethereum-ico'); ?></p>
                </label>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php _e("Gas Price, Gwei", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_gasprice" id="ETHEREUM_ICO_gasprice" type="number" min="0" step="1" maxlength="8" placeholder="21" value="<?php echo ! empty( $options['gasprice'] ) ? esc_attr( $options['gasprice'] ) : '21'; ?>">
                    <p><?php _e('The default gas price to buy your ICO token in Gwei units.', 'ethereum-ico'); ?></p>
                </label>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php _e("Base currency", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_base_currency" id="ETHEREUM_ICO_base_currency" type="text" maxlength="128" placeholder="<?php _e("Input the base currency code", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['base_currency'] ) ? esc_attr( $options['base_currency'] ) : __('ETH', 'ethereum-ico'); ?>">
                    <p><?php echo sprintf(__('The base currency code to show in the token sell widget, progress bar, e.t.c. In most cases the default ETH is OK. Use USD or other fiat currency three letter code if you have implemented %1$s interface in your %2$sCrowdsale%3$s smart contract. Make sure to configure %4$s if non-USD fiat currency is used here.', 'ethereum-ico')
                        , '<a href="http://www.oraclize.it/" target="_blank">oraclize.it</a>'
                        , '<a href="https://www.ethereum.org/crowdsale" target="_blank">'
                        , '</a>'
                        , __("openexchangerates.org App Id", 'ethereum-ico')); ?></p>
                </label>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php _e("Base symbol", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_base_symbol" id="ETHEREUM_ICO_base_symbol" type="text" maxlength="128" placeholder="<?php _e("Input the base currency symbol", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['base_symbol'] ) ? esc_attr( $options['base_symbol'] ) : 'Ξ'; ?>">
                    <p><?php echo sprintf(__('The base currency symbol to show in the progress bar widget. In most cases the default empty is OK. Use $ or other fiat currency symbol if you have implemented %1$s interface in your %2$sCrowdsale%3$s smart contract. Make sure to configure %4$s if non-USD fiat currency is used here.', 'ethereum-ico')
                        , '<a href="http://www.oraclize.it/" target="_blank">oraclize.it</a>'
                        , '<a href="https://www.ethereum.org/crowdsale" target="_blank">'
                        , '</a>'
                        , __("openexchangerates.org App Id", 'ethereum-ico')); ?></p>
                </label>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php _e("Placeholder", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_placeholder" id="ETHEREUM_ICO_placeholder" type="text" maxlength="128" placeholder="<?php echo sprintf(__("Input %s amount", 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?>" value="<?php echo ! empty( $options['placeholder'] ) ? esc_attr( $options['placeholder'] ) : sprintf(__("Input %s amount", 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?>">
                    <p><?php echo sprintf(__('It is a helper string displayed in the %1$s input field for your customer to know where to input %1$s amount to buy your tokens.', 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?></p>
                </label>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php echo sprintf(__("Increase/Decrease step, in %s", 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_step" id="ETHEREUM_ICO_step" type="text" placeholder="0.1" value="<?php echo ! empty( $options['step'] ) ? esc_attr( $options['step'] ) : '0.1'; ?>">
                    <p><?php echo sprintf(__("The step to adjust %s amount with up/down buttons", 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?></p>
                </label>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php echo sprintf(__("Min allowed value, in %s", 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_min" id="ETHEREUM_ICO_min" type="text" placeholder="0" value="<?php echo ! empty( $options['min'] ) ? esc_attr( $options['min'] ) : ''; ?>">
                    <p><?php echo sprintf(__("The minimum %s amount allowed for token purchase. Can be used to workaround some legal circumstances.", 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?></p>
                </label>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php echo sprintf(__("Max allowed value, in %s", 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?></th>
			<td><fieldset>
				<label>
                    <input disabled class="text" name="ETHEREUM_ICO_max" id="ETHEREUM_ICO_max" type="text" placeholder="10" value="<?php echo ! empty( $options['max'] ) ? esc_attr( $options['max'] ) : ''; ?>">
                    <p><?php echo sprintf(__("The maximum %s amount allowed for token purchase. Can be used to workaround some legal circumstances.", 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?></p>
                    <p><?php echo sprintf(__('%1$sPRO version only!%2$s', 'ethereum-ico')
                        , '<a href="https://ethereumico.io/" target="_blank">'
                        , '</a>') ?></p>
                </label>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php _e("Buy Button Text", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_buyButtonText" id="ETHEREUM_ICO_buyButtonText" type="text" maxlength="128" placeholder="<?php _e("Buy token with Metamask", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['buyButtonText'] ) ? esc_attr( $options['buyButtonText'] ) : __('Buy token with Metamask', 'ethereum-ico'); ?>">
                    <p><?php _e('The text to display on the BUY button', 'ethereum-ico'); ?></p>
                </label>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php _e("Description", 'ethereum-ico'); ?></th>
			<td><fieldset>
                <textarea class="large-text" name="ETHEREUM_ICO_description" id="ETHEREUM_ICO_description" type="text" maxlength="10240" placeholder="<?php _e("Add some notes", 'ethereum-ico'); ?>"><?php echo ! empty( $options['description'] ) ? esc_textarea( $options['description'] ) : ''; ?></textarea>
			</fieldset></td>
			</tr>

            <tr valign="top">
			<th scope="row"><?php _e("Transaction data", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input disabled class="text" name="ETHEREUM_ICO_txData" id="ETHEREUM_ICO_txData" type="text" maxlength="1024" placeholder="0x" value="<?php echo ! empty( $options['txData'] ) ? esc_attr( $options['txData'] ) : ''; ?>">
                    <p><?php _e('Data to be sent in the transaction. It should starts with \'0x\' without quotes.', 'ethereum-ico'); ?></p>
                    <p><?php echo sprintf(__('%1$sPRO version only!%2$s', 'ethereum-ico')
                        , '<a href="https://ethereumico.io/" target="_blank">'
                        , '</a>') ?></p>
                </label>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php _e("Bounty", 'ethereum-ico'); ?></th>
			<td><fieldset>
                <textarea disabled class="large-text" name="ETHEREUM_ICO_bounty" id="ETHEREUM_ICO_bounty" type="text" maxlength="1024000" placeholder="<?php _e("Put your bounty JSON code here", 'ethereum-ico'); ?>"><?php echo ! empty( $options['bounty'] ) ? esc_textarea( $options['bounty'] ) : ''; ?></textarea>
                <p><?php _e('The optional JSON array of your bounty values. Note that it should be supported in your crowdsale contract. Example: <code>[[7, 40], [7, 30], [7, 20], [7, 10], [7, 5]]</code>. The 7 number is for days count. The 40, 30, 20, 10, 5 are percents of additional tokens to be sent to buyer for free.', 'ethereum-ico'); ?></p>
                <p><?php echo sprintf(__('%1$sPRO version only!%2$s', 'ethereum-ico')
                    , '<a href="https://ethereumico.io/" target="_blank">'
                    , '</a>') ?></p>
			</fieldset></td>
			</tr>
			
			<tr valign="top">
			<th scope="row"><?php _e("Blockchain", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_blockchain_network" id="ETHEREUM_ICO_blockchain_network" type="text" maxlength="128" placeholder="mainnet" value="<?php echo ! empty( $options['blockchain_network'] ) ? esc_attr( $options['blockchain_network'] ) : 'mainnet'; ?>">
                    <p><?php _e("The blockchain used: mainnet or ropsten. Use mainnet in production, and ropsten in test mode. See plugin documentation for the testing guide.", 'ethereum-ico') ?></p>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php _e("Etherscan Api Key", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_etherscanApiKey" id="ETHEREUM_ICO_etherscanApiKey" type="text" maxlength="35" placeholder="<?php _e("Put your Etherscan Api Key here", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['etherscanApiKey'] ) ? esc_attr( $options['etherscanApiKey'] ) : ''; ?>">
                    <p><?php echo sprintf(__('The API key for the %1$s. You need to %2$sregister%3$s on this site to obtain it.', 'ethereum-ico')
                        , '<a target="_blank" href="https://etherscan.io/myapikey">https://etherscan.io</a>'
                        , '<a target="_blank" href="https://etherscan.io/register">'
                        , '</a>') ?></p>
                    <p><?php echo sprintf(__('Install some of the %1$spersistent cache WP plugins%2$s to overcome the etherscan API limits. In this case the API would be queried only once per 5 minutes.', 'ethereum-ico')
                        , '<a target="_blank" href="https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins">'
                        , '</a>') ?></p>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php _e("Infura.io Api Key", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_infuraApiKey" id="ETHEREUM_ICO_infuraApiKey" type="text" maxlength="35" placeholder="<?php _e("Put your Infura.io Api Key here", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['infuraApiKey'] ) ? esc_attr( $options['infuraApiKey'] ) : ''; ?>">
                    <p><?php echo sprintf(__('The API key for the %1$s. You need to register on this site to obtain it.', 'ethereum-ico')
                        , '<a target="_blank" href="https://infura.io/register">https://infura.io/</a>') ?></p>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php _e("openexchangerates.org App Id", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_openexchangeratesAppId" id="ETHEREUM_ICO_openexchangeratesAppId" type="text" maxlength="35" placeholder="<?php _e("Put your openexchangerates.org App Id here", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['openexchangeratesAppId'] ) ? esc_attr( $options['openexchangeratesAppId'] ) : ''; ?>">
                    <?php echo sprintf(__('<p>The App Id for the %1$s. You need to register on this site to obtain it.</p><p>This API is used to show rates for different currencies you want to display.</p><p><strong>Note:</strong> you do not need it if you want to display only your token, <strong>BTC</strong> and/or <strong>USD</strong>.</p><p>Install some of the %2$spersistent cache WP plugins%3$s to overcome the free account API limits in a 1000 requests per month. In this case the API would be queried only once per 1 hour.</p>', 'ethereum-ico')
                        , '<a target="_blank" href="https://openexchangerates.org/signup/free">https://openexchangerates.org</a>'
                        , '<a target="_blank" href="https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins">'
                        , '</a>') ?>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php _e("List of coins", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_coinList" id="ETHEREUM_ICO_coinList" type="text" maxlength="1024" placeholder="<?php _e("Your_token,BTC,USD,...", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['coinList'] ) ? esc_attr( $options['coinList'] ) : ''; ?>">
                    <?php _e('<p>The comma separated list of coins to convert the amount inputted by user.</p><p>Typically, it is your token symbol, USD, BTC.</p><p><strong>Note:</strong> if you want to show icons for coins, make sure that the folder <strong>icons</strong> has <strong>png</strong> files with the same names as coins you want to display, e.g. <strong>USD.png</strong>, <strong>BTC.png</strong>, <strong>YourCoinName.png</strong></p>', 'ethereum-ico') ?>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php _e("Show icons?", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input disabled class="text" name="ETHEREUM_ICO_showIcons" id="ETHEREUM_ICO_showIcons" type="checkbox" <?php echo (! empty( $options['showIcons'] ) ? 'checked' : ''); ?> >
                    <?php _e('Check to show icons for coins you display.<p><strong>Note:</strong> make sure that the folder <strong>icons</strong> has <strong>PNG</strong> files with the same names as coins you want to display, e.g. <strong>USD.png</strong>, <strong>BTC.png</strong>, <strong>YourCoinName.png</strong></p>', 'ethereum-ico') ?>
                    <?php echo sprintf(__('%1$sPRO version only!%2$s', 'ethereum-ico')
                        , '<a href="https://ethereumico.io/" target="_blank">'
                        , '</a>') ?>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php _e("The ICO token address", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_tokenAddress" id="ETHEREUM_ICO_tokenAddress" type="text" maxlength="45" placeholder="<?php _e("Put your ICO token address here", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['tokenAddress'] ) ? esc_attr( $options['tokenAddress'] ) : ''; ?>">
                    <p><?php _e('The ethereum address of your ICO ERC20 token.', 'ethereum-ico') ?></p>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php _e("The ICO crowdsale contract address", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_crowdsaleAddress" id="ETHEREUM_ICO_crowdsaleAddress" type="text" maxlength="45" placeholder="<?php _e("Put your ICO crowdsale token address here", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['crowdsaleAddress'] ) ? esc_attr( $options['crowdsaleAddress'] ) : ''; ?>">
                    <p><?php _e('The ethereum address of your ICO crowdsale contract. You can input a simple Ethereum address here instead of the Crowdsale contract address. In this case Ether would be sent directly to this your address, but note that you’ll need to send tokens to your customers manually then.', 'ethereum-ico') ?></p>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php _e("ICO period in days", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_icoperiod" id="ETHEREUM_ICO_icoperiod" type="number" min="0" step="1" maxlength="3" placeholder="30" value="<?php echo ! empty( $options['icoperiod'] ) ? esc_attr( $options['icoperiod'] ) : '30'; ?>">
                    <p><?php _e('The number of days your ICO would be opened.', 'ethereum-ico') ?></p>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php _e("The ICO token decimals number", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_decimals" id="ETHEREUM_ICO_decimals" type="number" min="0" step="100000000000000000" maxlength="60" placeholder="<?php _e("Put the decimals field of your ICO token contract", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['decimals'] ) ? esc_attr( $options['decimals'] ) : '1000000000000000000'; ?>">
                    <p><?php _e('The decimals field of your ICO ERC20 token. The typical value is 1000000000000000000.', 'ethereum-ico') ?></p>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php echo sprintf(__("The ICO token rate, in %s", 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_tokenRate" id="ETHEREUM_ICO_tokenRate" type="text" placeholder="<?php _e("Put the token rate here", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['tokenRate'] ) ? esc_attr( $options['tokenRate'] ) : '1'; ?>">
                    <p><?php echo sprintf(__('The number of tokens per 1 %1$s, i.e. the token to %1$s exchange rate', 'ethereum-ico'), __("ETH", 'ethereum-ico')) ?></p>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php _e("ICO start date", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_icostart" id="ETHEREUM_ICO_icostart" type="date" placeholder="<?php _e("Set the ICO date", 'ethereum-ico'); ?>" value="<?php echo ! empty( $options['icostart'] ) ? esc_attr( $options['icostart'] ) : ''; ?>">
                    <p><?php _e('The date when your ICO would start from.', 'ethereum-ico') ?></p>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php echo sprintf(__("ICO soft cap, in %s", 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?></th>
			<td><fieldset>
				<label>
                    <input disabled class="text" name="ETHEREUM_ICO_softcap" id="ETHEREUM_ICO_softcap" type="text" placeholder="" value="<?php echo ! empty( $options['softcap'] ) ? esc_attr( $options['softcap'] ) : ''; ?>">
                    <?php echo sprintf(__('<p>A soft cap is the amount received at which your crowdsale will be considered successful. It is the minimal amount required by your project for success. You are expected to refund all money if this cap would not be reached.</p><p>This feature uses %1$s `API`. Install some of the %2$spersistent cache WP plugins%3$s to overcome the %4$s API limits. In this case the API would be queried only once per 5 minutes.</p>', 'ethereum-ico')
                        , 'https://blockcypher.com'
                        , '<a target="_blank" href="https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins">'
                        , '</a>'
                        , 'blockcypher.com') ?>
                    <?php echo sprintf(__('%1$sPRO version only!%2$s', 'ethereum-ico')
                        , '<a href="https://ethereumico.io/" target="_blank">'
                        , '</a>') ?>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php echo sprintf(__("ICO hard cap, in %s", 'ethereum-ico'), __("ETH", 'ethereum-ico')); ?></th>
			<td><fieldset>
				<label>
                    <input class="text" name="ETHEREUM_ICO_hardcap" id="ETHEREUM_ICO_hardcap" type="text" placeholder="10000" value="<?php echo ! empty( $options['hardcap'] ) ? esc_attr( $options['hardcap'] ) : '10000'; ?>">
                    <?php echo sprintf(__('<p>A hard cap is defined as the maximum amount a crowdsale will receive. The crowdsale is expected to stop after this cap is reached.</p><p>This feature uses %1$s `API`. Install some of the %2$spersistent cache WP plugins%3$s to overcome the %4$s API limits. In this case the API would be queried only once per 5 minutes.</p>', 'ethereum-ico')
                        , 'https://blockcypher.com'
                        , '<a target="_blank" href="https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins">'
                        , '</a>'
                        , 'blockcypher.com') ?>
                </label>
			</fieldset></td>
			</tr>

			<tr valign="top">
			<th scope="row"><?php _e("Private sale seed", 'ethereum-ico'); ?></th>
			<td><fieldset>
				<label>
                    <input disabled class="text" name="ETHEREUM_ICO_private_sale_seed" id="ETHEREUM_ICO_private_sale_seed" type="text" placeholder="0" value="<?php echo ! empty( $options['private_sale_seed'] ) ? esc_attr( $options['private_sale_seed'] ) : '0'; ?>">
                    <?php echo sprintf(__('<p>An amount of funds gained in fiat or non-Ether cryptocurrency in the %s.</p>', 'ethereum-ico')
                        , __("Base currency", 'ethereum-ico')) ?>
                    <p><?php echo sprintf(__('%1$sPRO version only!%2$s', 'ethereum-ico')
                        , '<a href="https://ethereumico.io/" target="_blank">'
                        , '</a>') ?></p>
                </label>
			</fieldset></td>
			</tr>

		<?php endif; ?>
		
		</table>

		<p class="submit">
			<input class="button-primary" type="submit" name="Submit" value="<?php _e('Save Changes', 'ethereum-ico' ) ?>" />
			<input id="ETHEREUM_ICO_reset_options" type="submit" name="Reset" onclick="return confirm('<?php _e('Are you sure you want to delete all EthereumICO options?', 'ethereum-ico' ) ?>')" value="<?php _e('Reset', 'ethereum-ico' ) ?>" />
		</p>
	
	</form>
    
    <h2><?php _e("Need help to develop a ERC20 token for your ICO?", 'ethereum-ico'); ?></h2>
    <p><?php echo sprintf(
        __('Feel free to %1$shire me!%2$s', 'ethereum-ico')
        , '<a target="_blank" href="https://www.upwork.com/freelancers/~0134e80b874bd1fa5f">'
        , '</a>'
    )?></p>

    <h2><?php _e("Need help to configure this plugin?", 'ethereum-ico'); ?></h2>
    <p><?php echo sprintf(
        __('Feel free to %1$shire me!%2$s', 'ethereum-ico')
        , '<a target="_blank" href="https://www.upwork.com/freelancers/~0134e80b874bd1fa5f">'
        , '</a>'
    )?></p>

    <h2><?php _e("Want to accept paypal or Bitcoin for your ICO tokens?", 'ethereum-ico'); ?></h2>
    <p><?php echo sprintf(
        __('Try the %1$sCryptocurrency Product for WooCommerce%2$s plugin!', 'ethereum-ico')
        , '<a target="_blank" href="https://ethereumico.io/product/cryptocurrency-wordpress-plugin/">'
        , '</a>'
    )?></p>

    <h2><?php _e("Want to create Ethereum wallets on your Wordpress site?", 'ethereum-ico'); ?></h2>
    <p><?php echo sprintf(
        __('Install the %1$sWordPress Ethereum Wallet plugin%2$s!', 'ethereum-ico')
        , '<a target="_blank" href="https://ethereumico.io/product/wordpress-ethereum-wallet-plugin/">'
        , '</a>'
    )?></p>

    <h2><?php _e("Want to accept Ether or any ERC20/ERC223 token in your WooCommerce store?", 'ethereum-ico'); ?></h2>
    <p><?php echo sprintf(
        __('Install the %1$sEther and ERC20 tokens WooCommerce Payment Gateway%2$s plugin!', 'ethereum-ico')
        , '<a target="_blank" href="https://wordpress.org/plugins/ether-and-erc20-tokens-woocommerce-payment-gateway/">'
        , '</a>'
    )?></p>

    </div>

<?php

}
