<?php
/*
Plugin Name: EthereumICO
Plugin URI: https://www.ethereumico.io/
Description: Sell your ERC20 ICO tokens from your WordPress site.
Version: 1.11.0
Author: ethereumicoio
Text Domain: ethereum-ico
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Explicitly globalize to support bootstrapped WordPress
global 
	$ETHEREUM_ICO_plugin_basename, $ETHEREUM_ICO_options, $ETHEREUM_ICO_plugin_dir, $ETHEREUM_ICO_plugin_url_path, 
	$ETHEREUM_ICO_services, $ETHEREUM_ICO_amp_icons_css;

$ETHEREUM_ICO_plugin_basename = plugin_basename( dirname( __FILE__ ) );
$ETHEREUM_ICO_plugin_dir = untrailingslashit( plugin_dir_path( __FILE__ ) );
$ETHEREUM_ICO_plugin_url_path = untrailingslashit( plugin_dir_url( __FILE__ ) );

// HTTPS?
$ETHEREUM_ICO_plugin_url_path = is_ssl() ? str_replace( 'http:', 'https:', $ETHEREUM_ICO_plugin_url_path ) : $ETHEREUM_ICO_plugin_url_path;
// Set plugin options
$ETHEREUM_ICO_options = stripslashes_deep( get_option( 'ethereum-ico_options', array() ) );

function ETHEREUM_ICO_init() {
	global $ETHEREUM_ICO_plugin_dir,
		$ETHEREUM_ICO_plugin_basename, 
		$ETHEREUM_ICO_options;
	
	// Load the textdomain for translations
	load_plugin_textdomain( 'ethereum-ico', false, $ETHEREUM_ICO_plugin_basename . '/languages/' );
}
add_filter( 'init', 'ETHEREUM_ICO_init' );


function ETHEREUM_ICO_shortcode( $attributes ) {
    global $ETHEREUM_ICO_plugin_url_path;
    global $ETHEREUM_ICO_plugin_dir;
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'buybuttontext'     => '',
		'minimum'   => '',
		'step'   => '',
		'placeholder' => '',
		'gaslimit' => '',
		'tokenname' => '',
		'description' => '',
	), $attributes, 'ethereum-ico' );
	$options = $ETHEREUM_ICO_options;
	
	$gaslimit = ! empty( $attributes['gaslimit'] ) ? $attributes['gaslimit'] : 
        (! empty( $options['gaslimit'] ) ? esc_attr( $options['gaslimit'] ) : "200000");

	$tokenName = ! empty( $attributes['tokenname'] ) ? $attributes['tokenname'] :
        (! empty( $options['tokenname'] ) ? esc_attr( $options['tokenname'] ) : "TESTCOIN");

	$placeholder = ! empty( $attributes['placeholder'] ) ? $attributes['placeholder'] :
        (! empty( $options['placeholder'] ) ? esc_attr( $options['placeholder'] ) : __("Input ETH amount", 'ethereum-ico'));

	$step = ! empty( $attributes['step'] ) ? $attributes['step'] :
        (! empty( $options['step'] ) ? esc_attr( $options['step'] ) : "0.1");
        
	$minimum = ! empty( $attributes['minimum'] ) ? $attributes['minimum'] :
        (! empty( $options['min'] ) ? esc_attr( $options['min'] ) : "0");
        
	$buyButtonText = ! empty( $attributes['buybuttontext'] ) ? $attributes['buybuttontext'] : 
        (! empty( $options['buyButtonText'] ) ? esc_attr( $options['buyButtonText'] ) : sprintf(__("Buy %s with<br>Metamask", 'ethereum-ico'), $tokenName));

	$description = ! empty( $attributes['description'] ) ? $attributes['description'] : 
        (! empty( $options['description'] ) ? esc_attr( $options['description'] ) : sprintf(__("Make sure that you send Ether from a wallet that supports ERC20 tokens or from an address for which you control the private key: Otherwise you will not be able to interact with the %s tokens received. Do not send ETH directly from an exchange to the ICO address.", 'ethereum-ico'), $tokenName));
    
	$coinList = ! empty( $options['coinList'] ) ? esc_attr( $options['coinList'] ) : 
        '';
    // remove all whitespaces
    $coinList = preg_replace('/\s+/', '', $coinList);
    
	$base_currency = ! empty( $options['base_currency'] ) ? esc_attr( $options['base_currency'] ) : 
        __( 'ETH', 'ethereum-ico' );
    $base_currency_lower = strtolower($base_currency);
    
    $ratesHtml = '';
    $coins = array();
    if ($coinList) {
        $coins = explode(',', str_replace(" ", "", $coinList));
        $htmlArray = array();
        foreach($coins as $coin) {
            $token = strtolower($coin);
            $tokenImg = '';
            $html = 
'<div class="row ethereum-ico-rate-token-container">'
    . '<div class="ethereum-ico-rate-token-value col-md-5 col-6">'
        . '<span id="rate' . $token . 
            '" class="ethereum-ico-rate ethereum-ico-coin-rate-' . $token . '">0</span>'
    . '</div>'
    . '<div class="col-md-7 col-6">'
        . $tokenImg . '<span class="ethereum-ico-rate-coin float-left">' . $coin . '</span>'
    . '</div>'
. '</div>';
            $htmlArray[] = $html;
        }
        $ratesHtml = '<h4 class="ethereum-ico-rate-token-container-wrapper">' . implode("", $htmlArray) . '</h4>';
    }
    
	$js = '';

	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-shortcode">
    <div class="row">
        <div class="col">
            <h2 class="ethereum-ico-gaslimit">'.sprintf(__( 'Gas Limit: %s', 'ethereum-ico' ), $gaslimit).'</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 col-12">
            <div class="ethereum-ico-quantity">
                <input type="number" name="etherInput" id="etherInput" placeholder="'.$placeholder.'" step="'.$step.'" min="'.$minimum.'" class="ethereum-ico-bottom-input-one">
                <div class="quantity-nav">
                    <div class="quantity-button quantity-up">
                        <i class="fa fa-chevron-up" aria-hidden="true"></i>
                    </div>
                    <div class="quantity-button quantity-down">
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="ethereum-ico-rate-token-value col-6 hidden-lg d-lg-none hidden-md d-md-none">
            <span id="rate'.$base_currency_lower.'" class="ethereum-ico-rate ethereum-ico-rate-eth ethereum-ico-coin-rate-'.$base_currency_lower.'">0</span>
        </div>
        <div class="ethereum-ico-rate-eth col-6 hidden-lg d-lg-none hidden-md d-md-none">
            <span class="ethereum-ico-rate-coin">'.$base_currency.'</span>
        </div>
        <div class="ethereum-ico-rate-eth col-md-2 col-md-offset-0 col-6 col-offset-6 visible-lg visible-md d-none d-md-block">
            <span class="ethereum-ico-rate-coin">'.$base_currency.'</span>
        </div>
        <div class="ethereum-ico-bottom-button col-md-5 col-12">
            <button class="button ethereum-ico-bottom-button-two" id="buyTokensButton">'.$buyButtonText.'</button>
        </div>
    </div>'
        . $ratesHtml . 
    '<div class="row">
        <div class="col">
            <p class="ethereum-ico-description">'.$description.'</p>
        </div>
    </div>
</div></div>';
    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $ret)));
}

add_shortcode( 'ethereum-ico', 'ETHEREUM_ICO_shortcode' );

function ETHEREUM_ICO_input_currency_shortcode( $attributes ) {
    global $ETHEREUM_ICO_plugin_url_path;
    global $ETHEREUM_ICO_plugin_dir;
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'basecurrency'   => '',
	), $attributes, 'ethereum-ico-input-currency' );
	$options = $ETHEREUM_ICO_options;
	
	$base_currency = ! empty( $attributes['basecurrency'] ) ? $attributes['basecurrency'] :
        (! empty( $options['base_currency'] ) ? esc_attr( $options['base_currency'] ) : __( 'ETH', 'ethereum-ico' ));
    
    $base_currency_lower = strtolower($base_currency);
    
	$js = '';

	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-shortcode ethereum-ico-input-currency-shortcode">
    <div class="row">
        <div class="ethereum-ico-rate-token-value col-6 hidden-lg d-lg-none hidden-md d-md-none">
            <span id="rate'.$base_currency_lower.'" class="ethereum-ico-rate ethereum-ico-rate-eth ethereum-ico-coin-rate-'.$base_currency_lower.'">0</span>
        </div>
        <div class="ethereum-ico-rate-eth col-6 hidden-lg d-lg-none hidden-md d-md-none">'
            .'<span class="ethereum-ico-rate-coin">'.$base_currency.'</span>
        </div>
        <div class="ethereum-ico-rate-eth col-md-2 col-md-offset-0 col-6 col-offset-6 visible-lg visible-md d-none d-md-block">'
            .'<span class="ethereum-ico-rate-coin">'.$base_currency.'</span>
        </div>
    </div>
</div></div>';
    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $ret)));
}

add_shortcode( 'ethereum-ico-input-currency', 'ETHEREUM_ICO_input_currency_shortcode' );


function ETHEREUM_ICO_limit_shortcode( $attributes ) {
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'label'     => '',
		'gaslimit' => '',
	), $attributes, 'ethereum-ico-limit' );
	$options = $ETHEREUM_ICO_options;
	
	$gaslimit = ! empty( $attributes['gaslimit'] ) ? $attributes['gaslimit'] : 
        (! empty( $options['gaslimit'] ) ? esc_attr( $options['gaslimit'] ) : "200000");
    
	$label = ! empty( $attributes['label'] ) ? $attributes['label'] : __( 'Gas Limit: %s', 'ethereum-ico' );
    
	$js = '';

	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-shortcode ethereum-ico-limit-shortcode">
    <div class="row">
        <div class="col">
            <h2 class="ethereum-ico-gaslimit">'.sprintf($label, $gaslimit).'</h2>
        </div>
    </div>
</div></div>';
    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $ret)));
}

add_shortcode( 'ethereum-ico-limit', 'ETHEREUM_ICO_limit_shortcode' );


function ETHEREUM_ICO_buy_button_shortcode( $attributes ) {
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'buybuttontext' => '',
		'tokenname' => '',
	), $attributes, 'ethereum-ico-buy-button' );
	$options = $ETHEREUM_ICO_options;

	$tokenName = ! empty( $attributes['tokenname'] ) ? $attributes['tokenname'] :
        (! empty( $options['tokenname'] ) ? esc_attr( $options['tokenname'] ) : "TESTCOIN");
        
	$buyButtonText = ! empty( $attributes['buybuttontext'] ) ? $attributes['buybuttontext'] : 
        (! empty( $options['buyButtonText'] ) ? esc_attr( $options['buyButtonText'] ) : sprintf(__("Buy %s with<br>Metamask", 'ethereum-ico'), $tokenName));
    
	$js = '';

	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-shortcode ethereum-ico-buy-button-shortcode">
    <div class="row">
        <div class="ethereum-ico-bottom-button col-12">
            <button class="button ethereum-ico-bottom-button-two" id="buyTokensButton">'.$buyButtonText.'</button>
        </div>
    </div>
</div></div>';
    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $ret)));
}

add_shortcode( 'ethereum-ico-buy-button', 'ETHEREUM_ICO_buy_button_shortcode' );


function ETHEREUM_ICO_currency_list_shortcode( $attributes ) {
    global $ETHEREUM_ICO_plugin_url_path;
    global $ETHEREUM_ICO_plugin_dir;
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'coinlist'     => '',
	), $attributes, 'ethereum-ico-currency-list' );
	$options = $ETHEREUM_ICO_options;
	
	$coinList = ! empty( $attributes['coinlist'] ) ? $attributes['coinlist'] :
        ( ! empty( $options['coinList'] ) ? esc_attr( $options['coinList'] ) : '' );
    // remove all whitespaces
    $coinList = preg_replace('/\s+/', '', $coinList);
    
    $ratesHtml = '';
    $coins = array();
    if ($coinList) {
        $coins = explode(',', str_replace(" ", "", $coinList));
        $htmlArray = array();
        foreach($coins as $coin) {
            $token = strtolower($coin);
            $html = 
'<div class="row ethereum-ico-rate-token-container">'
    . '<div class="ethereum-ico-rate-token-value col-md-5 col-6">'
        . '<span id="rate' . $token . 
            '" class="ethereum-ico-rate ethereum-ico-coin-rate-' . $token . '">0</span>'
    . '</div>'
    . '<div class="col-md-7 col-6">'
        . '<span class="ethereum-ico-rate-coin float-left">' . $coin . '</span>'
    . '</div>'
. '</div>';
            $htmlArray[] = $html;
        }
        $ratesHtml = '<h4 class="ethereum-ico-currency-list-rate-token-container-wrapper">' . implode("", $htmlArray) . '</h4>';
    }
    
	$js = '';

	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-shortcode ethereum-ico-currency-list-shortcode">'
        . $ratesHtml . 
'</div></div>';
    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $ret)));
}

add_shortcode( 'ethereum-ico-currency-list', 'ETHEREUM_ICO_currency_list_shortcode' );


function ETHEREUM_ICO_input_shortcode( $attributes ) {
    global $ETHEREUM_ICO_plugin_url_path;
    global $ETHEREUM_ICO_plugin_dir;
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'minimum'   => '',
		'maximum'   => '',
		'step'   => '',
		'placeholder' => '',
	), $attributes, 'ethereum-ico-input' );
	$options = $ETHEREUM_ICO_options;

	$placeholder = ! empty( $attributes['placeholder'] ) ? $attributes['placeholder'] :
        (! empty( $options['placeholder'] ) ? esc_attr( $options['placeholder'] ) : __("Input ETH amount", 'ethereum-ico'));

	$step = ! empty( $attributes['step'] ) ? $attributes['step'] :
        (! empty( $options['step'] ) ? esc_attr( $options['step'] ) : "0.1");
        
	$minimum = ! empty( $attributes['minimum'] ) ? $attributes['minimum'] :
        (! empty( $options['min'] ) ? esc_attr( $options['min'] ) : "0");
        
	$maximum = ! empty( $attributes['maximum'] ) ? $attributes['maximum'] :
        (! empty( $options['max'] ) ? esc_attr( $options['max'] ) : "1000000000");
    
	$js = '';

	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-shortcode ethereum-ico-input-shortcode">
    <div class="row">
        <div class="col-12">
            <div class="ethereum-ico-quantity">
                <input type="number" name="etherInput" id="etherInput" placeholder="'.$placeholder.'" step="'.$step.'" min="'.$minimum.'" max="'.$maximum.'" class="ethereum-ico-bottom-input-one">
                <div class="quantity-nav">
                    <div class="quantity-button quantity-up">
                        <i class="fa fa-chevron-up" aria-hidden="true"></i>
                    </div>
                    <div class="quantity-button quantity-down">
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div></div>';
    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $ret)));
}

add_shortcode( 'ethereum-ico-input', 'ETHEREUM_ICO_input_shortcode' );


function ETHEREUM_ICO_calc_display_value( $value ) {
    if ($value < 1) {
        return array(0.01 * round(100 * $value), '');
    }
    if ($value < 1000) {
        return array(0.1 * round(10 * $value), '');
    }
    if ($value < 1000000) {
        return array(0.1 * round(10 * 0.001 * $value), __( 'K', 'ethereum-ico' ));
    }
    return array(0.1 * round(10 * 0.000001 * $value), __( 'M', 'ethereum-ico' ));
}

function ETHEREUM_ICO_get_total_received_blockcypher($crowdsaleAddress, $cacheKey) {
    $url = "https://api.blockcypher.com/v1/eth/main/addrs/$crowdsaleAddress/balance";
    $response = wp_remote_get( $url, array('sslverify' => false) );
    ?>
<!-- LOG: blockcypher.com API is called -->
    <?php
    if( is_wp_error( $response ) ) {
        $error_string = $response->get_error_message();
        return array($error_string, null);
    }
    $http_code = wp_remote_retrieve_response_code( $response );
    if (200 != $http_code) {
        return array(__("Result code is not 200", 'ethereum-ico'), null);
    }
    $body = wp_remote_retrieve_body( $response );
    if (!$body) {
        return array(__("Empty body", 'ethereum-ico'), null);
    }
    $j = json_decode($body, true);
    if (!isset($j["total_received"])) {
        return array(__("No total_received field", 'ethereum-ico'), null);
    }
    $total_received = $j["total_received"];
    return array(null, $total_received);
}

function ETHEREUM_ICO_get_total_received_etherscan($crowdsaleAddress, $cacheKey, $etherscanApiKey, $blockchain_network) {
    $api_suffix = '';
    if ('mainnet' != $blockchain_network) {
        $api_suffix = '-' . $blockchain_network;
    }
    $url = "https://api$api_suffix.etherscan.io/api?module=account&action=txlist&address=$crowdsaleAddress&startblock=0&endblock=99999999&page=1&offset=100&sort=desc&apikey=" . $etherscanApiKey;
    $response = wp_remote_get( $url, array('sslverify' => false) );
    ?>
<!-- LOG: etherscan.io total_received API is called -->
    <?php
    if( is_wp_error( $response ) ) {
        $error_string = $response->get_error_message();
        return array($error_string, null);
    }
    $http_code = wp_remote_retrieve_response_code( $response );
    if (200 != $http_code) {
        return array(__("Result code is not 200", 'ethereum-ico'), null);
    }
    $body = wp_remote_retrieve_body( $response );
    if (!$body) {
        return array(__("Empty body", 'ethereum-ico'), null);
    }
    $j = json_decode($body, true);
    if (!isset($j["result"])) {
        return array(__("No result field", 'ethereum-ico'), null);
    }
    $trxns = $j["result"];
    $total_received = 0;
    foreach ($trxns as $tx) {
        $toAddress = $tx['to'];
        if (strtolower($toAddress) != strtolower($crowdsaleAddress)) {
            continue;
        }
        $value = doubleval($tx['value']);
        $total_received += $value;
    }
    return array(null, $total_received);
}

function ETHEREUM_ICO_progress_shortcode( $attributes ) {
    global $ETHEREUM_ICO_plugin_url_path;
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'crowdsale'  => '',
		'icostart'   => '',
		'icoperiod'  => '',
		'hardcap'    => ''
	), $attributes, 'ethereum-ico' );
	$options = $ETHEREUM_ICO_options;
	
    $blockchain_network = ! empty( $options['blockchain_network'] ) ? esc_attr( $options['blockchain_network'] ) : 'mainnet';
	$etherscanApiKey = ! empty( $options['etherscanApiKey'] ) ? esc_attr( $options['etherscanApiKey'] ) : 
        '';
	$crowdsaleAddress = ! empty( $attributes['crowdsale'] ) ? esc_attr($attributes['crowdsale']) :
        (! empty( $options['crowdsaleAddress'] ) ? esc_attr( $options['crowdsaleAddress'] ) : "");

	$icostart = ! empty( $attributes['icostart'] ) ? esc_attr($attributes['icostart']) :
        (! empty( $options['icostart'] ) ? esc_attr( $options['icostart'] ) : "");

	$icoperiod = ! empty( $attributes['icoperiod'] ) ? esc_attr($attributes['icoperiod']) :
        (! empty( $options['icoperiod'] ) ? esc_attr( $options['icoperiod'] ) : "");

	$hardcap = ! empty( $attributes['hardcap'] ) ? esc_attr($attributes['hardcap']) :
        (! empty( $options['hardcap'] ) ? esc_attr( $options['hardcap'] ) : "10000");
    
//	$base_currency = ! empty( $options['base_currency'] ) ? esc_attr( $options['base_currency'] ) : __( 'ETH', 'ethereum-ico' );
	$base_symbol = ! empty( $options['base_symbol'] ) ? esc_attr( $options['base_symbol'] ) : '';
    
    $cacheKey = "$blockchain_network:$crowdsaleAddress:balance";
    $total_received = "0";
    switch ($blockchain_network) {
        case "mainnet":
            list($error, $total_received) = ETHEREUM_ICO_get_total_received_blockcypher($crowdsaleAddress, $cacheKey);
            if ($error) {
                return '<div class="alert alert-danger"><strong>'.__( 'Error!', 'ethereum-ico' ).'</strong> '.$error.'</div>';
            }
            break;

        default:
            list($error, $total_received) = ETHEREUM_ICO_get_total_received_etherscan($crowdsaleAddress, $cacheKey, $etherscanApiKey, $blockchain_network);
            break;
    }
    
    $total_received_eth = doubleval($total_received) / 1000000000000000000;
    ?>
<!-- total_received: <?php echo $total_received ?> -->
<!-- total_received_eth: <?php echo $total_received_eth ?> -->
        <?php
    // show only two digits after decimal point
    $total_received_eth_display = ETHEREUM_ICO_calc_display_value($total_received_eth);
    $total_received_eth_display = sprintf(__('%1$s%2$s%3$s', 'ethereum-ico'), $base_symbol, $total_received_eth_display[0], $total_received_eth_display[1]);
    ?>
<!-- total_received_eth_display: <?php echo $total_received_eth_display ?> -->
    <?php
    $total_received_percent = 100 * $total_received_eth / doubleval($hardcap);
    ?>
<!-- total_received_percent: <?php echo $total_received_percent ?> -->
    <?php
    
    $hardcap_display = ETHEREUM_ICO_calc_display_value(doubleval($hardcap));
    $hardcap_display = sprintf(__('%1$s%2$s%3$s', 'ethereum-ico'), $base_symbol, $hardcap_display[0], $hardcap_display[1]);

	$js = '';
    // https://bootsnipp.com/snippets/featured/progress-bar-meter
	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-progress-shortcode">
    <div class="row ethereum-ico-progress-content">
        <div class="col-12">
            <div class="progress">
                <div style="display:none" class="ethereum-ico-progress-total-received-eth">'.$total_received_eth.'</div>
                <div class="progress-bar ethereum-ico-progress-progress-bar" role="progressbar" aria-valuenow="'.$total_received_eth.'" aria-valuemin="0" aria-valuemax="'.$hardcap.'" style="width: '.$total_received_percent.'%;" data-toggle="tooltip" data-placement="top" title="'.sprintf(__( '%sETH', 'ethereum-ico' ), $total_received_eth).'">
                    <span class="sr-only ethereum-ico-progress-percent-complete">'.sprintf(__( '%s%% Complete', 'ethereum-ico' ), $total_received_percent).'</span>
                </div>
            </div>
            <div class="progress-meter">
                <div class="meter meter-left" style="width: 50%;"><span class="meter-text ethereum-ico-progress-total-display">'.$total_received_eth_display.'</span></div>
                <div class="meter meter-right" style="width: 50%;"><span class="meter-text">'.sprintf(__( 'Hard: %s', 'ethereum-ico' ), '<span ethereum-ico-progress-hardcap-display>'.$hardcap_display.'</span>').'</span></div>
            </div>
        </div>
    </div>
</div></div>';

    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $js . $ret)));
}

add_shortcode( 'ethereum-ico-progress', 'ETHEREUM_ICO_progress_shortcode' );

function ETHEREUM_ICO_progress_value_shortcode( $attributes ) {
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'crowdsale'  => '',
	), $attributes, 'ethereum-ico' );
	$options = $ETHEREUM_ICO_options;
	
    $blockchain_network = ! empty( $options['blockchain_network'] ) ? esc_attr( $options['blockchain_network'] ) : 'mainnet';
	$etherscanApiKey = ! empty( $options['etherscanApiKey'] ) ? esc_attr( $options['etherscanApiKey'] ) : 
        '';
	$crowdsaleAddress = ! empty( $attributes['crowdsale'] ) ? esc_attr($attributes['crowdsale']) :
        (! empty( $options['crowdsaleAddress'] ) ? esc_attr( $options['crowdsaleAddress'] ) : "");

    $cacheKey = "$blockchain_network:$crowdsaleAddress:balance";
    $total_received = "0";
    switch ($blockchain_network) {
        case "mainnet":
            list($error, $total_received) = ETHEREUM_ICO_get_total_received_blockcypher($crowdsaleAddress, $cacheKey);
            if ($error) {
                return '<div class="alert alert-danger"><strong>'.__( 'Error!', 'ethereum-ico' ).'</strong> '.$error.'</div>';
            }
            break;

        default:
            list($error, $total_received) = ETHEREUM_ICO_get_total_received_etherscan($crowdsaleAddress, $cacheKey, $etherscanApiKey, $blockchain_network);
            break;
    }
    
    $total_received_eth = doubleval($total_received) / 1000000000000000000;
    
	$js = '';
    // https://bootsnipp.com/snippets/featured/progress-bar-meter
	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-progress-shortcode ethereum-ico-progress-value-shortcode">
    <div class="row ethereum-ico-progress-content ethereum-ico-progress-value-content">
        <div class="col-12">
            <div style="display:none" class="ethereum-ico-progress-value-total-received-eth">'.$total_received_eth.'</div>
            <div class="ethereum-ico-progress-value-total-display">'.$total_received_eth.' ETH</div>
        </div>
    </div>
</div></div>';
    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $js . $ret)));
}

add_shortcode( 'ethereum-ico-progress-value', 'ETHEREUM_ICO_progress_value_shortcode' );

function ETHEREUM_ICO_progress_percent_shortcode( $attributes ) {
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'crowdsale'  => '',
		'hardcap'    => ''
	), $attributes, 'ethereum-ico' );
	$options = $ETHEREUM_ICO_options;
	
    $blockchain_network = ! empty( $options['blockchain_network'] ) ? esc_attr( $options['blockchain_network'] ) : 'mainnet';
	$etherscanApiKey = ! empty( $options['etherscanApiKey'] ) ? esc_attr( $options['etherscanApiKey'] ) : 
        '';
	$crowdsaleAddress = ! empty( $attributes['crowdsale'] ) ? esc_attr($attributes['crowdsale']) :
        (! empty( $options['crowdsaleAddress'] ) ? esc_attr( $options['crowdsaleAddress'] ) : "");

	$hardcap = ! empty( $attributes['hardcap'] ) ? esc_attr($attributes['hardcap']) :
        (! empty( $options['hardcap'] ) ? esc_attr( $options['hardcap'] ) : "10000");
    
    $cacheKey = "$blockchain_network:$crowdsaleAddress:balance";
    $total_received = "0";
    switch ($blockchain_network) {
        case "mainnet":
            list($error, $total_received) = ETHEREUM_ICO_get_total_received_blockcypher($crowdsaleAddress, $cacheKey);
            if ($error) {
                return '<div class="alert alert-danger"><strong>'.__( 'Error!', 'ethereum-ico' ).'</strong> '.$error.'</div>';
            }
            break;

        default:
            list($error, $total_received) = ETHEREUM_ICO_get_total_received_etherscan($crowdsaleAddress, $cacheKey, $etherscanApiKey, $blockchain_network);
            break;
    }
    
    $total_received_eth = doubleval($total_received) / 1000000000000000000;
    $total_received_percent = 100 * $total_received_eth / doubleval($hardcap);
    
	$js = '';
    // https://bootsnipp.com/snippets/featured/progress-bar-meter
	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-progress-shortcode ethereum-ico-progress-percent-shortcode">
    <div class="row ethereum-ico-progress-content ethereum-ico-progress-percent-content">
        <div class="col-12">
            <div style="display:none" class="ethereum-ico-progress-percent-total-received-eth">'.$total_received_eth.'</div>
            <div><span class="ethereum-ico-progress-percent-display">'.$total_received_percent.'</span><span class="ethereum-ico-progress-percent-display-percent">%</span></div>
        </div>
    </div>
</div></div>';
    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $js . $ret)));
}

add_shortcode( 'ethereum-ico-progress-percent', 'ETHEREUM_ICO_progress_percent_shortcode' );

function ETHEREUM_ICO_balance_shortcode( $attributes ) {
    global $ETHEREUM_ICO_plugin_url_path;
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'tokenname' => '',
	), $attributes, 'ethereum-ico' );
	$options = $ETHEREUM_ICO_options;

    $tokenName = ! empty( $attributes['tokenname'] ) ? $attributes['tokenname'] :
        (! empty( $options['tokenname'] ) ? esc_attr( $options['tokenname'] ) : "TESTCOIN");

	$js = '';
	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-balance-shortcode">
    <div class="row ethereum-ico-balance-account-wrapper hidden" hidden>
        <div class="col-12">
            <div class="form-group">
                <label class="control-label" for="ethereum-ico-balance-account">'.__('Account', 'ethereum-ico').'</label>
                <div class="input-group" style="margin-top: 8px">
                    <input type="text"
                           value="" 
                           placeholder="'.__('Input your ethereum account address', 'ethereum-ico').'" 
                           id="ethereum-ico-balance-account" 
                           class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row ethereum-ico-balance-content">
        <div class="col-md-6 col-6 ethereum-ico-balance-value-wrapper">
            <div class="ethereum-ico-balance-value">0</div>
        </div>
        <div class="col-md-6 col-6 ethereum-ico-balance-token-name-wrapper">
            <div class="ethereum-ico-balance-token-name">'.$tokenName.'</div>
        </div>
    </div>
</div></div>';

    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $js . $ret)));
}

add_shortcode( 'ethereum-ico-balance', 'ETHEREUM_ICO_balance_shortcode' );

function ETHEREUM_ICO_referral_shortcode( $attributes ) {
    global $ETHEREUM_ICO_plugin_url_path;
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'tokenname' => '',
	), $attributes, 'ethereum-ico' );
	$options = $ETHEREUM_ICO_options;

    $tokenName = ! empty( $attributes['tokenname'] ) ? $attributes['tokenname'] :
        (! empty( $options['tokenname'] ) ? esc_attr( $options['tokenname'] ) : "TESTCOIN");

	$js = '';
	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-referral-shortcode">
    <div class="row ethereum-ico-referral-account-wrapper hidden" hidden>
        <div class="col-12">
            <div class="form-group">
                <label class="control-label" for="ethereum-ico-referral-account">'.__('Account', 'ethereum-ico').'</label>
                <div class="input-group" style="margin-top: 8px">
                    <input type="text"
                           value="" 
                           placeholder="'.__('Input your ethereum account address', 'ethereum-ico').'" 
                           id="ethereum-ico-referral-account" 
                           class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row ethereum-ico-referral-content">
        <div class="col-12">
            <div class="form-group">
                <label class="control-label" for="ethereum-ico-referral-link">'. __('Referral link', 'ethereum-ico') . '</label>
                <div class="input-group" style="margin-top: 8px">
                    <input style="cursor: text;" type="text" disabled="disabled" 
                           value="" 
                           data-clipboard-action="copy" 
                           id="ethereum-ico-referral-link" 
                           class="form-control">
                    <span class="input-group-btn">
                        <button class="button btn btn-default ico-copy-button" type="button"
                                data-input-id="ethereum-ico-referral-link"
                                title="'. __('Copy', 'ethereum-ico') . '">
                            <i class="fa fa-clipboard" aria-hidden="true"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div></div>';

    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $js . $ret)));
}

add_shortcode( 'ethereum-ico-referral', 'ETHEREUM_ICO_referral_shortcode' );

function ETHEREUM_ICO_purchases_shortcode( $attributes ) {
    global $ETHEREUM_ICO_plugin_url_path;
    global $ETHEREUM_ICO_options;

	$attributes = shortcode_atts( array(
		'tokenname' => '',
	), $attributes, 'ethereum-ico' );
	$options = $ETHEREUM_ICO_options;

    $tokenName = ! empty( $attributes['tokenname'] ) ? $attributes['tokenname'] :
        (! empty( $options['tokenname'] ) ? esc_attr( $options['tokenname'] ) : "TESTCOIN");
	$js = '';
	$ret = 
'<div class="twbs"><div class="container-fluid ethereum-ico-purchases-shortcode">
    <div class="row ethereum-ico-purchases-account-chk-wrapper">
        <div class="col-12">
            <div class="form-check form-check-inline">
                <input type="checkbox"
                       value="" 
                       id="ethereum-ico-purchases-account-chk" 
                       class="form-check-input">
                <label class="form-check-label" for="ethereum-ico-purchases-account-chk">'.__('My account only', 'ethereum-ico').'</label>
            </div>
        </div>
    </div>
    <div class="row ethereum-ico-purchases-account-wrapper hidden" hidden>
        <div class="col-12">
            <div class="form-group">
                <label class="control-label" for="ethereum-ico-purchases-account">'.__('Account', 'ethereum-ico').'</label>
                <div class="input-group" style="margin-top: 8px">
                    <input type="text"
                           value="" 
                           placeholder="'.__('Input your ethereum account address', 'ethereum-ico').'" 
                           id="ethereum-ico-purchases-account" 
                           class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row ethereum-ico-purchases-table-wrapper">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-condensed ethereum-ico-purchases-table">
                    <thead>
                        <tr>
                            <th>'.__('#', 'ethereum-ico').'</th>
                            <th>'.__('Amount', 'ethereum-ico').'</th>
                            <th>'.sprintf(__('Amount, %s', 'ethereum-ico'), $tokenName).'</th>
                            <th>'.__('Date', 'ethereum-ico').'</th>
                            <th>'.__('Tx', 'ethereum-ico').'</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div></div>';

    return $js . str_replace("\n", " ", str_replace("\r", " ", str_replace("\t", " ", $js . $ret)));
}

add_shortcode( 'ethereum-ico-purchases', 'ETHEREUM_ICO_purchases_shortcode' );


function ETHEREUM_ICO_stylesheet() {
	global $ETHEREUM_ICO_plugin_url_path;
	
    $deps = array('font-awesome', 'bootstrap-ethereum-ico');
    $min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
    if( ( ! wp_style_is( 'font-awesome', 'queue' ) ) && ( ! wp_style_is( 'font-awesome', 'done' ) ) ) {
        wp_dequeue_style('font-awesome');
        wp_deregister_style('font-awesome');
        wp_register_style(
            'font-awesome', 
            $ETHEREUM_ICO_plugin_url_path . "/css/font-awesome{$min}.css", array(), '4.7.0'
        );
    }
    if ( !wp_style_is( 'bootstrap-ethereum-ico', 'queue' ) && !wp_style_is( 'bootstrap-ethereum-ico', 'done' ) ) {
        wp_dequeue_style('bootstrap-ethereum-ico');
        wp_deregister_style('bootstrap-ethereum-ico');
        wp_register_style(
            'bootstrap-ethereum-ico', 
            $ETHEREUM_ICO_plugin_url_path . "/css/bootstrap-ns{$min}.css", array(), '4.0.0'
        );
    }

    wp_enqueue_style( 'ethereum-ico', $ETHEREUM_ICO_plugin_url_path . '/ethereum-ico.css', $deps, '1.11.0' );
}

add_action( 'wp_enqueue_scripts', 'ETHEREUM_ICO_stylesheet', 20 );

function ETHEREUM_ICO_get_rate_data_fiat() {
	global $ETHEREUM_ICO_options;
	$openexchangeratesAppId = ! empty( $ETHEREUM_ICO_options['openexchangeratesAppId'] ) ? esc_attr( $ETHEREUM_ICO_options['openexchangeratesAppId'] ) : '';
    $openexchangeratesEndpoint = "https://openexchangerates.org/api/latest.json?app_id=" . $openexchangeratesAppId;
    $rateDataFiat = "null";
    $response = wp_remote_get( $openexchangeratesEndpoint, array('sslverify' => false) );
    ?>
<!-- LOG: openexchangerates.org API is called -->
    <?php
    if( !is_wp_error( $response ) ) {
        $http_code = wp_remote_retrieve_response_code( $response );
        if (200 == $http_code) {
            $body = wp_remote_retrieve_body( $response );
            if ($body) {
                $j = json_decode($body, true);
                if (isset($j["rates"])) {
//  "rates": {
//    "AED": 3.672896,
//    "AFN": 69.496503,
//    "ALL": 107.678684,
                    $rateDataFiat = json_encode($j["rates"]);
                }
            }            
        }
    }
    return $rateDataFiat;
}

function ETHEREUM_ICO_get_rate_data() {
	global $ETHEREUM_ICO_options;
	$etherscanApiKey = ! empty( $ETHEREUM_ICO_options['etherscanApiKey'] ) ? esc_attr( $ETHEREUM_ICO_options['etherscanApiKey'] ) : '';
    $etherscanEndpoint = "https://api.etherscan.io/api?module=stats&action=ethprice&apikey=" . $etherscanApiKey;
    $rateData = "null";
    $response = wp_remote_get( $etherscanEndpoint, array('sslverify' => false) );
    ?>
<!-- LOG: etherscan.io rate_data API is called -->
    <?php
    if( !is_wp_error( $response ) ) {
        $http_code = wp_remote_retrieve_response_code( $response );
        if (200 == $http_code) {
            $body = wp_remote_retrieve_body( $response );
            if ($body) {
                $j = json_decode($body, true);
                if (isset($j["result"])) {
                    $rateData = json_encode($j["result"]);
                }
            }            
        }
    }
    return $rateData;
}

function ETHEREUM_ICO_enqueue_script() {
	global $ETHEREUM_ICO_plugin_url_path;
	global $ETHEREUM_ICO_options;
	
    $min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
    wp_enqueue_script( 'web3', $ETHEREUM_ICO_plugin_url_path . "/web3{$min}.js", array( 'jquery' ), '0.20.6' );
    wp_enqueue_script( 'ethereum-ico', $ETHEREUM_ICO_plugin_url_path . '/ethereum-ico.js', array( 'jquery', 'web3' ), '1.11.0' );

	$attributes = shortcode_atts( array(), array(), 'ethereum-ico' );
	$options = $ETHEREUM_ICO_options;
	
	$tokenName = ! empty( $attributes['tokenname'] ) ? $attributes['tokenname'] :
        (! empty( $options['tokenname'] ) ? esc_attr( $options['tokenname'] ) : "TESTCOIN");
	$blockchain_network = ! empty( $options['blockchain_network'] ) ? esc_attr( $options['blockchain_network'] ) : 
        'mainnet';
	$infuraApiKey = ! empty( $options['infuraApiKey'] ) ? esc_attr( $options['infuraApiKey'] ) : 
        '';
	$coinList = ! empty( $options['coinList'] ) ? esc_attr( $options['coinList'] ) : 
        '';
    // remove all whitespaces
    $coinList = preg_replace('/\s+/', '', $coinList);
    
	$hardcap = ! empty( $options['hardcap'] ) ? esc_attr( $options['hardcap'] ) : "10000";

	$base_currency = ! empty( $options['base_currency'] ) ? esc_attr( $options['base_currency'] ) : __( 'ETH', 'ethereum-ico' );
	$base_symbol = ! empty( $options['base_symbol'] ) ? esc_attr( $options['base_symbol'] ) : '';

    $icoperiod = ! empty( $options['icoperiod'] ) ? esc_attr( $options['icoperiod'] ) : '30';
	$icostart = ! empty( $options['icostart'] ) ? /*esc_attr*/( $options['icostart'] ) : '';
	$tokenAddress = ! empty( $options['tokenAddress'] ) ? /*esc_attr*/( $options['tokenAddress'] ) : '';
	$crowdsaleAddress = ! empty( $options['crowdsaleAddress'] ) ? /*esc_attr*/( $options['crowdsaleAddress'] ) : '';
	$decimals = ! empty( $options['decimals'] ) ? esc_attr( $options['decimals'] ) : '1000000000000000000';
	$tokenRate = ! empty( $options['tokenRate'] ) ? esc_attr( $options['tokenRate'] ) : '1';
	$contractABI = trim(! empty( $options['contractABI'] ) ? /*esc_attr*/( $options['contractABI'] ) : '[]');
    if (empty($contractABI)) {
        $contractABI = '[]';
    }
	$gaslimit = ! empty( $options['gaslimit'] ) ? esc_attr( $options['gaslimit'] ) : "200000";
	$gasprice = ! empty( $options['gasprice'] ) ? esc_attr( $options['gasprice'] ) : "21";

    $rateData = ETHEREUM_ICO_get_rate_data();
    $rateDataFiat = ETHEREUM_ICO_get_rate_data_fiat();

    $coins = array();
    if ($coinList) {
        $coins = explode(',', str_replace(" ", "", $coinList));
    }

    wp_localize_script(
        'ethereum-ico', 'ico', array(
            // variables
            'coins' => esc_html(json_encode($coins)),
            'tokenName' => esc_html($tokenName),
            'rateData' => esc_html($rateData),
            'rateDataFiat' => esc_html($rateDataFiat),
            'tokenRate' => esc_html($tokenRate),
            'icoperiod' => esc_html($icoperiod),
            'start' => esc_html($icostart),
            'web3Endpoint' => esc_html("https://" . $blockchain_network . ".infura.io/" . $infuraApiKey),
            'blockchain_network' => esc_html($blockchain_network),
            'tokenAddress' => esc_html($tokenAddress),
            'crowdsaleAddress' => esc_html($crowdsaleAddress),
            'decimals' => esc_html($decimals),
            'gasLimit' => esc_html($gaslimit),
            'gasPrice' => esc_html($gasprice),
            'hardcap' => esc_html($hardcap),
            'base_currency' => esc_html($base_currency),
            'base_symbol' => esc_html($base_symbol),
            // translations
            'str_download_metamask' => __('Download MetaMask', 'ethereum-ico'),
            'str_unlock_metamask_account' => __('Unlock your MetaMask account please', 'ethereum-ico'),
            'str_account_balance_failure' => __('Failed to get account balance', 'ethereum-ico'),
            'str_mm_network_mismatch' => __('MetaMask network mismatch. Choose another network or ask site administrator.', 'ethereum-ico'),
            'str_network_unknown' => __('This is an unknown network.', 'ethereum-ico'),
            'str_copied_msg' => __('Copied to clipboard', 'ethereum-ico'),
            'str_tx_rejected' => __('You have rejected the token buy operation.', 'ethereum-ico'),
            'str_tx_success' => __('Success! Tx hash: ', 'ethereum-ico'),
            'str_contract_get_failure' => __('Failed to get contract', 'ethereum-ico'),
            'str_empty_address_error' => __('Empty address requested for load_transactions!', 'ethereum-ico'),
            'str_table_days_label' => __(' days', 'ethereum-ico'),
            'str_table_hours_label' => __(' hours', 'ethereum-ico'),
            'str_table_minutes_label' => __(' minutes', 'ethereum-ico'),
            'str_kilo_label' => __('K', 'ethereum-ico'),
            'str_mega_label' => __('M', 'ethereum-ico'),
            'str_currency_display_format' => __('%1$s%2$s%3$s', 'ethereum-ico'),
            'str_percent_complete_format' => __( '%s%% Complete', 'ethereum-ico' ),
            'str_table_recently_label' => __('recently', 'ethereum-ico'),
            'str_currency_unknown_error' => sprintf(__('Currency is unknown or the %s is not configured correctly.', 'ethereum-ico'), __("openexchangerates.org App Id", 'ethereum-ico')),
        )
    );
}

add_action( 'wp_enqueue_scripts', 'ETHEREUM_ICO_enqueue_script' );

/**
 * Admin Options
 */

if ( is_admin() ) {
	include_once $ETHEREUM_ICO_plugin_dir . '/ethereum-ico.admin.php';
}

function ETHEREUM_ICO_add_menu_link() {
	$page = add_options_page(
		__( 'EthereumICO Settings', 'ethereum-ico' ),
		__( 'EthereumICO', 'ethereum-ico' ),
		'manage_options',
		'ethereum-ico',
		'ETHEREUM_ICO_options_page'
	);
}

add_filter( 'admin_menu', 'ETHEREUM_ICO_add_menu_link' );

// Place in Option List on Settings > Plugins page 
function ETHEREUM_ICO_actlinks( $links, $file ) {
	// Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
	
	if ( ! $this_plugin ) {
		$this_plugin = plugin_basename( __FILE__ );
	}
	
	if ( $file == $this_plugin ) {
		$settings_link = '<a href="options-general.php?page=ethereum-ico">' . __( 'Settings', 'ethereum-ico' ) . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	
	return $links;
}

add_filter( 'plugin_action_links', 'ETHEREUM_ICO_actlinks', 10, 2 );
