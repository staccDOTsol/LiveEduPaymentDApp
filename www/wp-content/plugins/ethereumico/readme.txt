=== EthereumICO ===
Contributors: ethereumicoio
Tags: EthereumICO, ethereum, erc20, ICO, initial coin offering, cryptocurrency
Requires at least: 3.7
Tested up to: 4.9.8
Stable tag: 1.11.0
Donate link: https://etherscan.io/address/0x476Bb28Bc6D0e9De04dB5E19912C392F9a76535d
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires PHP: 5.6

Sell your Ethereum ERC20 ICO tokens from your WordPress site.

== Description ==

Ethereum ICO Wordpress plugin can be used to sell your Ethereum ERC20 ICO tokens from your Wordpress site.

> It is the only available WP plugin to sell your Ethereum ERC20 ICO tokens directly from your WordPress site.

* To show tokens sell widget insert a simple `[ethereum-ico]` shortcode wherever you like
* You also can use fine grained shortcodes for easier customization: `[ethereum-ico-limit label="%s LIMIT!" gaslimit="200001"]`, `[ethereum-ico-input placeholder="Test placeholder"]`, `[ethereum-ico-input-currency showIcons="true" baseCurrency="USD"]`, `[ethereum-ico-buy-button buyButtonText="BUY ME!"]`, `[ethereum-ico-currency-list showIcons="false"  coinList="ETH,BTC"]`
* To show an ICO progress bar widget insert a simple `[ethereum-ico-progress]` shortcode wherever you like. This feature uses https://blockcypher.com `API`. You must use any of the [persistent hash WP plugins](https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins "The persistent hash WP plugins") to overcome its rate limits.
* Use shortcodes to display the current progress value `[ethereum-ico-progress-value]` and percent `[ethereum-ico-progress-percent]`. This feature uses https://blockcypher.com `API`. You must use any of the [persistent hash WP plugins](https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins "The persistent hash WP plugins") to overcome its rate limits.
* To show tokens balance on the current user account use the `[ethereum-ico-balance]` shortcode. If MetaMask is not installed or account is not unlocked, an input field is provided for user account address.
* There is also a shortcode `[ethereum-ico-purchases]` to display a table of recent token purchases by anyone, or by the current user. In the last case if MetaMask is not installed or account is not unlocked, an input field is provided for user account address.
* Use shortcode `[ethereum-ico-referral]` to display a referral address field. User can copy it and send to friends. If they buy tokens while opened this referral link, your `Crowdsale` contract would get a referral address in the `Data` field. Your `Crowdsale` contract should be able to work with it.
* Airdrop is also supported. Just set the minimum allowed setting to zero and the Crowdsale address to your airdrop contract. Note that your airdrop contract should be able to accept zero payments and send some tokens in return.
* This plugin uses Metamask to safely perform the ERC20 token sell operation
* It will show user a link to the Metamask site if the user doesn’t have the Metamask installed
* We use a well known https://etherscan.io API to provide your client an automatic rate calculations to USD and BTC
* You can use a bounty program, if your ERC20 crowdsale contract supports it. [PRO version only!](https://ethereumico.io/product/ethereum-ico-wordpress-plugin/ "The EthereumICO Wordpress plugin")
* The transaction data to send to your crowdsale contract is supported. [PRO version only!](https://ethereumico.io/product/ethereum-ico-wordpress-plugin/ "The EthereumICO Wordpress plugin")
* Select a list of any currencies supported by the openexchangerates.org to convert the price to.
* You can use any of the [persistent hash WP plugins](https://codex.wordpress.org/Class_Reference/WP_Object_Cache#Persistent_Cache_Plugins "The persistent hash WP plugins") to overcome the etherscan.io and openexchangerates.org API rate limits. We use the cache to limit the API calling rate to a reasonable value. [Caching is available in a PRO version only!](https://ethereumico.io/product/ethereum-ico-wordpress-plugin/ "The EthereumICO Wordpress plugin")
* You can provide a comma separated list of coins to convert ETH amount inputted by user. This list is shown under the ETH input field.
* Coins and token icons display can be switched on. [PRO version only!](https://ethereumico.io/product/ethereum-ico-wordpress-plugin/ "The EthereumICO Wordpress plugin")
* Minimum and maximum ether amount can be specified to workaround some legal issues. The maximum value setting is available in a [PRO version only!](https://ethereumico.io/product/ethereum-ico-wordpress-plugin/ "The EthereumICO Wordpress plugin")
* Test networks like ropsten or rinkeby are supported. User is warned if he tries to buy tokens from one network, while having MetaMask to point to another network, effectively preventing any losses here.
* This plugin is l10n ready
* Ability to set base currency to display in token sell widget, progress bar and tx table
* Private sale seed setting to count funds obtained in a non-Crowdsale contract way. [PRO version only!](https://ethereumico.io/ "The EthereumICO Wordpress plugin")

See the official site for a live demo: [https://ethereumico.io/](https://ethereumico.io/ "The EthereumICO Wordpress plugin")

> You can accept fiat money or Bitcoin for your ICO tokens with the [Cryptocurrency Product for WooCommerce](https://ethereumico.io/product/cryptocurrency-product-for-woocommerce-standard-license/ "Cryptocurrency Product for WooCommerce") plugin.

== Disclaimer ==

**By using this plugin you accept all responsibility for handling the account balances for all your users.**

Under no circumstances is **ethereumico.io** or any of its affiliates responsible for any damages incurred by the use of this plugin.

Every effort has been made to harden the security of this plugin, but its safe operation depends on your site being secure overall. You, the site administrator, must take all necessary precautions to secure your WordPress installation before you connect it to any live wallets.

You are strongly advised to take the following actions (at a minimum):

- [Educate yourself about cold and hot cryptocurrency storage](https://en.bitcoin.it/wiki/Cold_storage)
- Obtain hardware wallet to store your coins, like [Ledger Nano S](https://www.ledgerwallet.com/r/4caf109e65ab?path=/products/ledger-nano-s), [TREZOR](https://shop.trezor.io?a=ethereumico.io) or [KeepKey](http://keepkey.go2cloud.org/aff_c?offer_id=1&aff_id=5037)
- [Educate yourself about hardening WordPress security](https://codex.wordpress.org/Hardening_WordPress)
- [Install a security plugin such as Jetpack](https://jetpack.com/pricing/?aff=9181&cid=886903) or any other security plugin
- **Enable SSL on your site** if you have not already done so.

> By continuing to use the Ethereum ICO Wordpress plugin, you indicate that you have understood and agreed to this disclaimer.

== Screenshots ==

1. This is how the plugin looks like if the Show icons feature is enabled.
2. The `[ethereum-ico-progress]` display
3. The `[ethereum-ico-balance]` display
4. The `[ethereum-ico-purchases]` display
5. The token symbol and GAS settings
6. The base currency settings
7. Token sale widget settings
8. Advanced settings
9. API keys
10. Displayed currencies list and Show icons flag
11. The ICO Token and Crowdsale contract addresses
12. The ICO Crowdsale contract properties
13. The `[ethereum-ico-progress]` shortcode settings
14. The `[ethereum-ico-referral]` display

== Installation ==

Enter your settings in admin pages and place the `[ethereum-ico]`, `[ethereum-ico-progress]` and other shortcodes wherever you need it.

> Read this step by step guide for more information: [Install and Configure](https://ethereumico.io/knowledge-base/ico-website-install-configure/)

= Main shortcodes example =

`
[ethereum-ico-referral]

[ethereum-ico-balance]

[ethereum-ico]

[ethereum-ico-progress]

[ethereum-ico-purchases]
`

= Fine grained shortcodes example =

`
[ethereum-ico-limit label="%s LIMIT!" gaslimit="200001"]

[ethereum-ico-input placeholder="Test placeholder"]

[ethereum-ico-input-currency showIcons="true" baseCurrency="USD"]

[ethereum-ico-buy-button buyButtonText="BUY ME!"]

[ethereum-ico-currency-list showIcons="false"  coinList="ETH,BTC"]

[ethereum-ico-progress-value]

[ethereum-ico-progress-percent]

`

= Placeholder =

It is a helper string displayed in the Ether input field for your customer to know where to input Ether amount to buy your tokens.

= Infura.io Api Key =

Register for an infura.io API key and put it in admin settings. It is required to interact with Ethereum blockchain. After register you'll get a mail with links like that: `https://mainnet.infura.io/1234567890`. The `1234567890` part is the API Key required.

= Description =

The `Description` text is displayed immediately after the token purchase widget. It is a good place for some warnings or bounty information.

= Transaction data =

It is an advanced feature. It can be required if your Crowdsale contract can not just accept Ether by send, but need some `payable` method to be called. Do not use if unsure.

= The ICO crowdsale contract address =

The ethereum address of your ICO crowdsale contract. It is the address EthereumICO plugin sends Ether to.

> You can input a simple Ethereum address here instead of the Crowdsale contract address. In this case Ether would be sent directly to this your address, but note that you’ll need to send tokens to your customers manually then.

= The ICO token decimals number =

The decimals field of your ICO ERC20 token. The typical value is 1000000000000000000.

> Note that it is different from the `decimals` value in your `Token` contract. If your `Token.decimals` is 18, then you neet to input `10^18 = 1000000000000000000` here. If your `Token.decimals` is 0, then you neet to input `10^0 = 1` here.

= Purchase button =

The Purchase button style has a `button` CSS class and is determined by your WP theme chosen.
You can customize it by adding these code to your `Additional CSS` section in the theme customizing:

`
.button.ethereum-ico-bottom-button-two {
    background-color: #ffd600;
    color: #ffffff;
}
.button.ethereum-ico-bottom-button-two:hover {
    background-color: #ffd6ff;
    color: #ffffff;
}
`

= Progressbar =

`
.progress {
    background-color: #f5f5f5;
    border-radius: 4px;
}
.progress-bar {
    background-color: #337ab7;
}
.progress-meter > .meter > .meter-text {
    color: rgb(160, 160, 160);
}
`

Choose your own colors and additional styles if needed.

= Sidebar small buttons issue =

It is known to have short length button and input area when put on a short width sidebar area. You can fix it with this CSS code:

`
@media (min-width: 992px) {
    .ethereum-ico-shortcode .col-md-5 {
        width: 100%!important;
        max-width: 100%!important;
        flex-basis: 100%!important;
    }
}
`

== Testing ==

You can test this plugin in some test network for free.

=== Testing in ropsten ===

* Set the `Blockchain` setting to `ropsten`
* Set `The ICO token address` setting to 0x6Fe928d427b0E339DB6FF1c7a852dc31b651bD3a or an address of your own token
* Set `The ICO crowdsale contract address` setting to 0x773F803b0393DFb7dc77e3f7a012B79CCd8A8aB9 or an address of your Crowsale contract
* Tune other plugin settings if required
* Buy some tokens with Ropsten Ether
* You can "buy" some Ropsten Ether for free using MetaMask
* Check that proper amount of tokens has been sent to your payment address

=== Testing in rinkeby ===

* Set the `Blockchain` setting to `rinkeby`
* Set `The ICO token address` setting to 0x194c35B62fF011507D6aCB55B95Ad010193d303E or an address of your own token
* Set `The ICO crowdsale contract address` setting to 0x669519e1e150dfdfcf0d747d530f2abde2ab3f0e or an address of your Crowsale contract
* Tune other plugin settings if required
* Buy some tokens with Rinkeby Ether
* You can "buy" some Rinkeby Ether for free here: [rinkeby.io](https://www.rinkeby.io/#faucet)
* Check that proper amount of tokens has been sent to your payment address

== Cache ==

Test if caching is enabled. It is important to overcome API rate limits in production.

Refresh your site page twice and check the HTML source produced for `LOG:` records.

For non-PRO plugin version and for PRO version with Cache plugin not configured properly they would looks like this:

`
LOG: etherscan.io rate_data API is called
LOG: etherscan.io rate_data API call result is stored
LOG: openexchangerates.org API is called
LOG: openexchangerates.org API call result is saved
LOG: etherscan.io total_received API is called
LOG: etherscan.io total_received API call result is stored
`

> Note: make sure to enable the `Object Cache` in the `W3 Total Cache` if you use it.

For [PRO](https://ethereumico.io/product/ethereum-ico-wordpress-plugin/) plugin version with `Cache` properly configured you should see no `LOG:` records most of the time.

See the [ICO Launch: Wordpress Cache Plugin](https://ethereumico.io/knowledge-base/ico-launch-wordpress-cache-plugin/) guide to configure the caching plugin.

== Upgrade Notice ==

If you upgrade from free version to the PRO, just deactivate and delete the free plugin version, then install the PRO version.

> Do not worry about your settings, they would be preserved.

When new plugin version is released, do not use the standard WordPress update.
Download new PRO version from the [https://ethereumico.io/](https://ethereumico.io/) site and follow the same upgrade procedure as above.

> See the [Free vs PRO version differences](https://ethereumico.io/knowledge-base/ethereum-ico-wordpress-plugin-free-vs-pro-version-differences/) if need more info on this topic.

== l10n ==

This plugin is localization ready.

Languages this plugin is available now:

* English
* Russian(Русский)

Feel free to [translate](https://translate.wordpress.org/projects/wp-plugins/ethereumico) this plugin to your language.

== Changelog ==

= 1.11.0 =

* New shortcodes are added for easier token sale widget customization: `[ethereum-ico-limit]`, `[ethereum-ico-input]`, `[ethereum-ico-input-currency]`, `[ethereum-ico-buy-button]`, `[ethereum-ico-currency-list]`
* Shortcodes for current progress value `[ethereum-ico-progress-value]` and percent `[ethereum-ico-progress-percent]` are added

= 1.10.3 =

* Fix the purchases table for contracts which do not send tokens immediately

= 1.10.2 =

* Upgrade web3.js to the latest stable release v0.20.6

= 1.10.1 =

* Fix token amount calculation for purchases table if token rate is set incorrectly.

= 1.10.0 =

* Ability to set base currency to display in token sell widget, progress bar and tx table
* Private sale seed setting
* Correct progress bar value in test networks
* Progress bar values rounding fix
