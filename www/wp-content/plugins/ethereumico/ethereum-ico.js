// @see http://javascript.ru/php/sprintf
function ETHEREUM_ICO_sprintf( ) {	// Return a formatted string
	// 
	// +   original by: Ash Searle (http://hexmen.com/blog/)
	// + namespaced by: Michael White (http://crestidg.com)

	var regex = /%%|%(\d+\$)?([-+#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuidfegEG])/g;
	var a = arguments, i = 0, format = a[i++];

	// pad()
	var pad = function(str, len, chr, leftJustify) {
		var padding = (str.length >= len) ? '' : Array(1 + len - str.length >>> 0).join(chr);
		return leftJustify ? str + padding : padding + str;
	};

	// justify()
	var justify = function(value, prefix, leftJustify, minWidth, zeroPad) {
		var diff = minWidth - value.length;
		if (diff > 0) {
			if (leftJustify || !zeroPad) {
			value = pad(value, minWidth, ' ', leftJustify);
			} else {
			value = value.slice(0, prefix.length) + pad('', diff, '0', true) + value.slice(prefix.length);
			}
		}
		return value;
	};

	// formatBaseX()
	var formatBaseX = function(value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
		// Note: casts negative numbers to positive ones
		var number = value >>> 0;
		prefix = prefix && number && {'2': '0b', '8': '0', '16': '0x'}[base] || '';
		value = prefix + pad(number.toString(base), precision || 0, '0', false);
		return justify(value, prefix, leftJustify, minWidth, zeroPad);
	};

	// formatString()
	var formatString = function(value, leftJustify, minWidth, precision, zeroPad) {
		if (precision != null) {
			value = value.slice(0, precision);
		}
		return justify(value, '', leftJustify, minWidth, zeroPad);
	};

	// finalFormat()
	var doFormat = function(substring, valueIndex, flags, minWidth, _, precision, type) {
		if (substring == '%%') return '%';

		// parse flags
		var leftJustify = false, positivePrefix = '', zeroPad = false, prefixBaseX = false;
		for (var j = 0; flags && j < flags.length; j++) switch (flags.charAt(j)) {
			case ' ': positivePrefix = ' '; break;
			case '+': positivePrefix = '+'; break;
			case '-': leftJustify = true; break;
			case '0': zeroPad = true; break;
			case '#': prefixBaseX = true; break;
		}

		// parameters may be null, undefined, empty-string or real valued
		// we want to ignore null, undefined and empty-string values
		if (!minWidth) {
			minWidth = 0;
		} else if (minWidth == '*') {
			minWidth = +a[i++];
		} else if (minWidth.charAt(0) == '*') {
			minWidth = +a[minWidth.slice(1, -1)];
		} else {
			minWidth = +minWidth;
		}

		// Note: undocumented perl feature:
		if (minWidth < 0) {
			minWidth = -minWidth;
			leftJustify = true;
		}

		if (!isFinite(minWidth)) {
			throw new Error('sprintf: (minimum-)width must be finite');
		}

		if (!precision) {
			precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type == 'd') ? 0 : void(0);
		} else if (precision == '*') {
			precision = +a[i++];
		} else if (precision.charAt(0) == '*') {
			precision = +a[precision.slice(1, -1)];
		} else {
			precision = +precision;
		}

		// grab value using valueIndex if required?
		var value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

		switch (type) {
			case 's': return formatString(String(value), leftJustify, minWidth, precision, zeroPad);
			case 'c': return formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
			case 'b': return formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
			case 'o': return formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
			case 'x': return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
			case 'X': return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad).toUpperCase();
			case 'u': return formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
			case 'i':
			case 'd': {
						var number = parseInt(+value);
						var prefix = number < 0 ? '-' : positivePrefix;
						value = prefix + pad(String(Math.abs(number)), precision, '0', false);
						return justify(value, prefix, leftJustify, minWidth, zeroPad);
					}
			case 'e':
			case 'E':
			case 'f':
			case 'F':
			case 'g':
			case 'G':
						{
						var number = +value;
						var prefix = number < 0 ? '-' : positivePrefix;
						var method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
						var textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
						value = prefix + Math.abs(number)[method](precision);
						return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
					}
			default: return substring;
		}
	};

	return format.replace(regex, doFormat);
}


// http://locutus.io/php/math/round/
function ETHEREUM_ICO_round (value, precision, mode) {
  //  discuss at: http://locutus.io/php/round/
  // original by: Philip Peterson
  //  revised by: Onno Marsman (https://twitter.com/onnomarsman)
  //  revised by: T.Wild
  //  revised by: Rafał Kukawski (http://blog.kukawski.pl)
  //    input by: Greenseed
  //    input by: meo
  //    input by: William
  //    input by: Josep Sanz (http://www.ws3.es/)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //      note 1: Great work. Ideas for improvement:
  //      note 1: - code more compliant with developer guidelines
  //      note 1: - for implementing PHP constant arguments look at
  //      note 1: the pathinfo() function, it offers the greatest
  //      note 1: flexibility & compatibility possible
  //   example 1: round(1241757, -3)
  //   returns 1: 1242000
  //   example 2: round(3.6)
  //   returns 2: 4
  //   example 3: round(2.835, 2)
  //   returns 3: 2.84
  //   example 4: round(1.1749999999999, 2)
  //   returns 4: 1.17
  //   example 5: round(58551.799999999996, 2)
  //   returns 5: 58551.8

  var m, f, isHalf, sgn // helper variables
  // making sure precision is integer
  precision |= 0
  m = Math.pow(10, precision)
  value *= m
  // sign of the number
  sgn = (value > 0) | -(value < 0)
  isHalf = value % 1 === 0.5 * sgn
  f = Math.floor(value)

  if (isHalf) {
    switch (mode) {
      case 'PHP_ROUND_HALF_DOWN':
      // rounds .5 toward zero
        value = f + (sgn < 0)
        break
      case 'PHP_ROUND_HALF_EVEN':
      // rouds .5 towards the next even integer
        value = f + (f % 2 * sgn)
        break
      case 'PHP_ROUND_HALF_ODD':
      // rounds .5 towards the next odd integer
        value = f + !(f % 2)
        break
      default:
      // rounds .5 away from zero
        value = f + (sgn > 0)
    }
  }

  return (isHalf ? value : Math.round(value)) / m
}

if (!String.prototype.startsWith) {
  Object.defineProperty(String.prototype, 'startsWith', {
    enumerable: false,
    configurable: false,
    writable: false,
    value: function(searchString, position) {
      position = position || 0;
      return this.indexOf(searchString, position) === position;
    }
  });
}

// https://stackoverflow.com/a/979995/4256005
function parse_query_string(query) {
  var vars = query.split("&");
  var query_string = {};
  for (var i = 0; i < vars.length; i++) {
    if ("" === vars[i]) {
        continue;
    }
    var pair = vars[i].split("=");
    var key = decodeURIComponent(pair[0]);
    var value = decodeURIComponent(pair[1]);
    // If first entry with this name
    if (typeof query_string[key] === "undefined") {
      query_string[key] = decodeURIComponent(value);
      // If second entry with this name
    } else if (typeof query_string[key] === "string") {
      var arr = [query_string[key], decodeURIComponent(value)];
      query_string[key] = arr;
      // If third or later entry with this name
    } else {
      query_string[key].push(decodeURIComponent(value));
    }
  }
  return query_string;
}

//var query_string = "a=1&b=3&c=m2-m3-m4-m5";
//var parsed_qs = parse_query_string(query_string);
//console.log(parsed_qs.c);

function ico_copyAddress(e) {
	e.preventDefault();
	// copy in any case
	var $temp = jQuery("<input>");
	jQuery("body").append($temp);

    var target = e.target;
    if ("BUTTON" !== e.target.tagName) {
        target = target.parentElement;
    }
	var id = jQuery(target).data("input-id");
	console.log("Copy from: ", id);

	var value = jQuery("#" + id).val();
	console.log("Value to copy: ", value);

	$temp.val(value).select();		
	document.execCommand("copy");
	$temp.remove();

    alert(window.ico.str_copied_msg);
}

function checkMMNetworkMismatch(cb) {
    if (window.ico.web3metamask) {
        // https://ethereum.stackexchange.com/a/23905/34760
        window.ico.web3metamask.version.getNetwork(function(err, netId) {
            if (err) {
                console.log(err); 
                cb.call(null, err, null);
                return;
            }
            var mm_network_mismatch = false;
            switch (netId) {
                case "1":
                    mm_network_mismatch = (-1 === window.ico.web3Endpoint.indexOf('mainnet'));
                    break
//                    case "2":
//                        console.log('This is the deprecated Morden test network.')
//                        break
                case "3":
                    mm_network_mismatch = (-1 === window.ico.web3Endpoint.indexOf('ropsten'));
                    break
                case "4":
                    mm_network_mismatch = (-1 === window.ico.web3Endpoint.indexOf('rinkeby'));
                    break;
                case "42":
                    mm_network_mismatch = (-1 === window.ico.web3Endpoint.indexOf('kovan'));
                    break;
                default: {
                    console.log(window.ico.str_network_unknown)
                    mm_network_mismatch = true;
                }
            }
            cb.call(null, null, mm_network_mismatch);
        });
    } else {
        // no MM. can not mismatch
        cb.call(null, null, false);
    }
}

function doBuyTokens(e) {
	e.preventDefault();
    checkMMNetworkMismatch(function(err, mm_network_mismatch) {
        if (err) {
            console.log(err);
            return;
        }
        if (mm_network_mismatch) {
            alert(window.ico.str_mm_network_mismatch);
            return;
        }
        window.ico.web3metamask.eth.getAccounts(function (err, accounts) {

            if (err) {
                console.log(err);
                return;
            }

            if (0 === accounts.length) {
                console.log("Metamask account not found");
                alert(window.ico.str_unlock_metamask_account);
                return;
            }

            var v = jQuery("#etherInput").val();
            if ("" === v) {
                v = "0";
            }
            var input = jQuery('.ethereum-ico-quantity #etherInput');
            var val = parseFloat(v);
            var min = parseFloat(input.attr('min'));
            if (val < min) {
                val = min;
            }
            var factor = ETHEREUM_ICO_calc_factor (window.ico.base_currency, "ETH");
            val = val * factor;
            val = 1000000000000000000 * val;
            var data = "0x";
            
            // Can not use referral link and Data setting simultaneously
            // The Data setting has a priority.
            // try to get data from referral link
            var args = parse_query_string(location.search);
            if ('undefined' !== typeof(args['icoreferral'])) {
                data = args['icoreferral'];
            } else if ('undefined' !== typeof(args['?icoreferral'])) {
                data = args['?icoreferral'];
            }

            var address = accounts[0];
            var transactionObject = {
                from: address,
                to: window.ico.crowdsaleAddress,
                value: val,
                gas: window.ico.gasLimit,
                gasPrice: window.ico.gasPrice * Math.pow(10, 9),
                data: data,
                nonce: '0x00'
            };
            window.ico.web3.eth.getTransactionCount(address, function(err, res) {
                if (err) {
                    console.log(err);
                    console.log("Network error. Check your infuraApiKey settings.");
                    return;
                }
                console.log("Current address nonce value: ", res);
                nonce = parseInt(res);
                transactionObject.nonce = "0x" + nonce.toString(16);
                console.log(transactionObject);
                window.ico.web3metamask.eth.sendTransaction(transactionObject, function (err, transactionHash) {
                    if (err) {
                        console.log(err);
                        alert(window.ico.str_tx_rejected);
                        return;
                    }
                    console.log(transactionHash);
                    alert(window.ico.str_tx_success + transactionHash);
                });
            });

        });
    });
}

function changeEtherAmount() {
	var v = jQuery("#etherInput").val();
	if ("" === v) {
		v = "0";
	}
	var val = parseFloat(v);
	var input = jQuery('.ethereum-ico-quantity #etherInput');
	var min = parseFloat(input.attr('min'));
	if (val < min) {
		val = min;
		input.val(val.toFixed(2));
	}
//    jQuery("#rateeth").text(input.val());
//    jQuery("#rateToken").text((tokenRate * val).toFixed(2));
    var rateData = JSON.parse(window.ico.rateData);

	if (rateData) {
        var coins = JSON.parse(window.ico.coins);
        var rateDataFiat = JSON.parse(window.ico.rateDataFiat);
		for (var i = 0; i < coins.length; i++) {
			var coin = coins[i];
            var factor = ETHEREUM_ICO_calc_factor (window.ico.base_currency, coin);
            var value = val * factor;
            var txtvalue = value.toFixed(1);
			if (window.ico.tokenName === coin) {
				jQuery(".ethereum-ico-coin-rate-" + coin.toLowerCase()).each(function(){
                    jQuery(this).text(txtvalue);
                });
			}
			else if ("BTC" === coin) {
                var txtvalue = value.toFixed(5);
				jQuery(".ethereum-ico-coin-rate-" + coin.toLowerCase()).each(function(){
                    jQuery(this).text(txtvalue);
                });
			}
			else if ("ETH" === coin) {
                var txtvalue = value.toFixed(5);
				jQuery(".ethereum-ico-coin-rate-" + coin.toLowerCase()).each(function(){
                    jQuery(this).text(txtvalue);
                });
			}
			else if ("USD" === coin) {
                var txtvalue = value.toFixed(2);
				jQuery(".ethereum-ico-coin-rate-" + coin.toLowerCase()).each(function(){
                    jQuery(this).text(txtvalue);
                });
				//4jQuery("#rate" + coin.toLowerCase()).text(value.toFixed(2));
			} else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[coin]) {
                var txtvalue = value.toFixed(2);
				jQuery(".ethereum-ico-coin-rate-" + coin.toLowerCase()).each(function(){
                    jQuery(this).text(txtvalue);
                });
			}
		}
        var coin = window.ico.base_currency;
        var value = val;
        if (window.ico.tokenName === coin) {
            var txtvalue = value.toFixed(1);
            jQuery(".ethereum-ico-coin-rate-" + coin.toLowerCase()).each(function(){
                jQuery(this).text(txtvalue);
            });
        }
        else if ("BTC" === coin) {
            var txtvalue = value.toFixed(5);
            jQuery(".ethereum-ico-coin-rate-" + coin.toLowerCase()).each(function(){
                jQuery(this).text(txtvalue);
            });
        }
        else if ("ETH" === coin) {
            var txtvalue = value.toFixed(5);
            jQuery(".ethereum-ico-coin-rate-" + coin.toLowerCase()).each(function(){
                jQuery(this).text(txtvalue);
            });
        }
        else if ("USD" === coin) {
            var txtvalue = value.toFixed(2);
            jQuery(".ethereum-ico-coin-rate-" + coin.toLowerCase()).each(function(){
                jQuery(this).text(txtvalue);
            });
            //jQuery("#rate" + coin.toLowerCase()).text(value.toFixed(2));
        } else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[coin]) {
            var txtvalue = value.toFixed(2);
            jQuery(".ethereum-ico-coin-rate-" + coin.toLowerCase()).each(function(){
                jQuery(this).text(txtvalue);
            });
        }
	}
}

function openDownloadMetamaskWindow(e) {
    e.preventDefault();
    var metamaskWindow = window.open("https://metamask.io/", '_blank'
        , 'location=yes,height=' + window.outerHeight + 
            ',width=' + window.outerWidth + 
            ',scrollbars=yes,status=yes');
    metamaskWindow.focus();
}

// wrap user accounts source for non-metamask case
function getUserAccounts(callback) {
	// this function is used if no Metamask is defined
	var _fn = function(callback) {
        if (jQuery('#ethereum-ico-balance-account').val() !== '') {
            var accounts = [jQuery('#ethereum-ico-balance-account').val()];
            callback.call(null, null, accounts);
            return;
        }
        callback.call(null, window.ico.str_download_metamask, []);
	};
	var _eth = null;
	if ('undefined' !== typeof window.ico['web3metamask']) {
		_fn = window.ico.web3metamask.eth.getAccounts;
		_eth = window.ico.web3metamask.eth;
	}
	_fn.call(_eth, function(err, accounts) {
		callback.call(null, err, accounts);
	});
}

function get_erc20_contract(tokenAddress) {
    var abi = [{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"name":"supply","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_owner","type":"address"}],"name":"balanceOf","outputs":[{"name":"balance","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"name":"_owner","type":"address"},{"name":"_spender","type":"address"}],"name":"allowance","outputs":[{"name":"remaining","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_owner","type":"address"},{"indexed":true,"name":"_spender","type":"address"},{"indexed":false,"name":"_value","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"_from","type":"address"},{"indexed":true,"name":"_to","type":"address"},{"indexed":false,"name":"_value","type":"uint256"}],"name":"Transfer","type":"event"},{"constant":false,"inputs":[{"name":"_spender","type":"address"},{"name":"_value","type":"uint256"}],"name":"approve","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_to","type":"address"},{"name":"_value","type":"uint256"}],"name":"transfer","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"name":"_from","type":"address"},{"name":"_to","type":"address"},{"name":"_value","type":"uint256"}],"name":"transferFrom","outputs":[{"name":"success","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"}];
	return window.ico.web3.eth.contract(abi).at(tokenAddress);
}

function get_token_balance_by_account(tokenAddress, account, callback) {
	var contract = get_erc20_contract(tokenAddress);
	if (!contract) {
		callback.call(null, window.ico.str_contract_get_failure, null);
		return;
	}
	contract.balanceOf(account, callback);
}

function get_token_balance(tokenAddress, cb) {
    getUserAccounts(function(err, accounts) {

        if (err) {
            console.log(err); 
            cb.call(null, err, null);
            return;
        }

        if (0 === accounts.length) {
            console.log("Metamask account not found"); 
            if (jQuery('#ethereum-ico-balance-account').val() !== '') {
                accounts = [jQuery('#ethereum-ico-balance-account').val()];
            } else {
                cb.call(null, window.ico.str_unlock_metamask_account, null);
                return;
            }
        }

        get_token_balance_by_account(tokenAddress, accounts[0], function(err, balance) {
            if (err) {
                console.log(err); 
                cb.call(null, window.ico.str_account_balance_failure, null);
                return;
            }
            console.log("Token balance: ", balance.toNumber());
            var tokenValue = balance.toNumber() / parseFloat(window.ico.decimals);
            cb.call(null, null, tokenValue);
        });
    });
}

function init_token_balance() {
    get_token_balance(window.ico.tokenAddress, function(err, balance){
        if (err) {
            console.log(err); 
            if (err === window.ico.str_unlock_metamask_account) {
                jQuery('.ethereum-ico-balance-account-wrapper').removeClass('hidden');
                jQuery('.ethereum-ico-balance-account-wrapper').removeAttr('hidden');
            }
            else if (err === window.ico.str_download_metamask) {
                jQuery('.ethereum-ico-balance-account-wrapper').removeClass('hidden');
                jQuery('.ethereum-ico-balance-account-wrapper').removeAttr('hidden');
            }
            return;
        }
        jQuery('.ethereum-ico-balance-value').text(balance);
    });
}

function ethereumIcoBalanceAccountChange() {
    init_token_balance();
}

function ETHEREUM_ICO_calc_factor (currency_old, currency_new) {
    if (currency_old === currency_new) {
        console.log("currency_old === currency_new: ", currency_old);
        return 1;
    }
    var rateData = JSON.parse(window.ico.rateData);
    var rateDataFiat = JSON.parse(window.ico.rateDataFiat);
    var tokenName = window.ico.tokenName;
    if ("ETH" === currency_old) {
        if (tokenName === currency_new) {
            return parseFloat(window.ico.tokenRate);
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
            alert(window.ico.str_currency_unknown_error);
            return 1;
        }
    } else if (tokenName === currency_old) {
        var tokenRate = window.ico.tokenRate;
        if ("ETH" === currency_new) {
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
            alert(window.ico.str_currency_unknown_error);
            return 1;
        }
    } else if ("BTC" === currency_old) {
        if (tokenName === currency_new) {
            return parseFloat(window.ico.tokenRate);
        }
        else if ("ETH" === currency_new) {
            return 1 / parseFloat(rateData.ethbtc);
        }
        else if ("USD" === currency_new) {
           return parseFloat(rateData.ethusd) / parseFloat(rateData.ethbtc);
        }
        else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[currency_new]) {
            return parseFloat(rateData.ethusd) * rateDataFiat[currency_new] / parseFloat(rateData.ethbtc);
        }
        else {
            alert(window.ico.str_currency_unknown_error);
            return 1;
        }
    } else if ("USD" === currency_old) {
        if (tokenName === currency_new) {
            return parseFloat(window.ico.tokenRate);
        }
        else if ("BTC" === currency_new) {
            return parseFloat(rateData.ethbtc) / parseFloat(rateData.ethusd);
        }
        else if ("ETH" === currency_new) {
           return 1 / parseFloat(rateData.ethusd);
        }
        else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[currency_new]) {
            return rateDataFiat[currency_new] / parseFloat(rateData.ethusd);
        }
        else {
            alert(window.ico.str_currency_unknown_error);
            return 1;
        }
    } else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[currency_old]) {
        if (tokenName === currency_new) {
            return parseFloat(window.ico.tokenRate);
        }
        else if ("BTC" === currency_new) {
            return parseFloat(rateData.ethbtc) / (parseFloat(rateData.ethusd) * rateDataFiat[currency_old]);
        }
        else if ("ETH" === currency_new) {
           return 1 / (parseFloat(rateData.ethusd) * rateDataFiat[currency_old]);
        }
        else if ("USD" === currency_new) {
           return 1 / (parseFloat(rateData.ethusd) * rateDataFiat[currency_old]);
        }
        else if (rateDataFiat && 'undefined' !== typeof rateDataFiat[currency_new]) {
            return rateDataFiat[currency_new] / (parseFloat(rateData.ethusd) * rateDataFiat[currency_old]);
        }
        else {
            alert(window.ico.str_currency_unknown_error);
            return 1;
        }
    } else {
        alert(window.ico.str_currency_unknown_error);
        return 1;
    }
}

function ICO_init() {
	if ("undefined" !== typeof window.ico && window.ico.initialized === true) {
        return;
    }
	if ("undefined" !== typeof window.ico.web3Endpoint) {
		jQuery('.ethereum-ico-quantity').each(function () {
			var spinner = jQuery(this),
					input = spinner.find('input[type="number"]'),
					btnUp = spinner.find('.quantity-up'),
					btnDown = spinner.find('.quantity-down'),
					min = parseFloat(input.attr('min'));
			step = parseFloat(input.attr('step'));

			btnUp.click(function () {
				var strVal = input.val();
				if ("" === strVal) {
					strVal = "0";
				}
				var oldValue = parseFloat(strVal);
				var newVal = oldValue + step;
				spinner.find("input").val(newVal.toFixed(2));
				spinner.find("input").trigger("change");
			});

			btnDown.click(function () {
				var strVal = input.val();
				if ("" === strVal) {
					strVal = "0";
				}
				var oldValue = parseFloat(strVal);
				if (oldValue < min) {
					var newVal = min;
				} else {
					var newVal = oldValue - step;
				}
				if (newVal < 0) {
					newVal = 0;
				}
				spinner.find("input").val(newVal.toFixed(2));
				spinner.find("input").trigger("change");
			});

		});

		if (typeof window !== 'undefined' && typeof window.web3 !== 'undefined') {
            var injectedProvider = window.web3.currentProvider;
            window.ico.web3metamask = new Web3(injectedProvider)
			jQuery("#buyTokensButton").click(doBuyTokens);
		} else {
            jQuery("#buyTokensButton").click(openDownloadMetamaskWindow);
			jQuery("#buyTokensButton").text(window.ico.str_download_metamask);
		}
        if ("undefined" !== typeof window.ico.web3Endpoint) {
            window.ico.web3 = new Web3(new Web3.providers.HttpProvider(window.ico.web3Endpoint));
        }

		jQuery("#etherInput").change(changeEtherAmount);
		jQuery("#etherInput").blur(changeEtherAmount);
        
        if (jQuery('.ethereum-ico-balance-value').text() !== '') {
            init_token_balance();
            jQuery("#ethereum-ico-balance-account").change(ethereumIcoBalanceAccountChange);
        }

	}
    jQuery("#ethereum-ico-purchases-account-chk").change(changePurchasesAccountCheckbox);
    jQuery('#ethereum-ico-purchases-account').change(changePurchasesAccount);
    jQuery("#ethereum-ico-referral-account").change(changeReferralAccount);
    load_and_render_transactions(window.ico.crowdsaleAddress);
    jQuery(".ico-copy-button").click(ico_copyAddress);
    ico_updateReferreeAddress();
    ETHEREUM_ICO_updateProgressValues();
    ETHEREUM_ICO_updateProgressValue();
    ETHEREUM_ICO_updateProgressPercent();
    window.ico.initialized = true;
}

jQuery(document).ready(ICO_init);
// proper init if loaded by ajax
jQuery(document).ajaxComplete(function( event, xhr, settings ) {
    // check if the loaded content contains our shortcodes
    if (!xhr ||
        'undefined' === typeof xhr.responseText || 
        (
            xhr.responseText.indexOf('ethereum-ico-shortcode') === -1 &&
            xhr.responseText.indexOf('ethereum-ico-input-shortcode') === -1 &&
            xhr.responseText.indexOf('ethereum-ico-currency-list-shortcode') === -1 &&
            xhr.responseText.indexOf('ethereum-ico-buy-button-shortcode') === -1 &&
            xhr.responseText.indexOf('ethereum-ico-limit-shortcode') === -1 &&
            xhr.responseText.indexOf('ethereum-ico-input-currency-shortcode') === -1 &&
            xhr.responseText.indexOf('ethereum-ico-progress-shortcode') === -1 &&
            xhr.responseText.indexOf('ethereum-ico-progress-value-shortcode') === -1 &&
            xhr.responseText.indexOf('ethereum-ico-balance-shortcode') === -1 &&
            xhr.responseText.indexOf('ethereum-ico-purchases-shortcode') === -1
        )
    ) {
        return;
    }
    ICO_init();
});

function ico_calc_referral_link(account) {
    var args = parse_query_string(location.search);
    if ('undefined' !== typeof(args['icoreferral'])) {
        delete args['icoreferral'];
    } else if ('undefined' !== typeof(args['?icoreferral'])) {
        delete args['?icoreferral'];
    }
    args['icoreferral'] = account;
    var strargs = '';
    for (var i in args) {
        var key = i;
        if (key.startsWith('?')) {
            key = key.substr(1);
        }
        strargs += ('&' + key + '=' + args[i]);
    }
    return window.location.origin + window.location.pathname + '?' + strargs.substr(1);
}

function changeReferralAccount() {
    var account = jQuery("#ethereum-ico-referral-account").val();
    var referralLink = ico_calc_referral_link(account);
    jQuery("#ethereum-ico-referral-link").val(referralLink);
}

function changePurchasesAccount() {
    changePurchasesAccountCheckbox();
}

function changePurchasesAccountCheckbox() {
    if (jQuery("#ethereum-ico-purchases-account-chk").prop("checked")) {
        // 1. проверить, есть ли аккаунт. если нет, показать поле ввода адреса
        // 2. если есть, загрузить транзакции для адреса
        getUserAccounts(function(err, accounts) {

            if (err) {
                console.log(err); 
                if (err === window.ico.str_unlock_metamask_account) {
                    jQuery('.ethereum-ico-purchases-account-wrapper').removeClass('hidden');
                    jQuery('.ethereum-ico-purchases-account-wrapper').removeAttr('hidden');
                    if (jQuery('#ethereum-ico-purchases-account').val() !== '') {
                        accounts = [jQuery('#ethereum-ico-purchases-account').val()];
                    }
                }
                else if (err === window.ico.str_download_metamask) {
                    jQuery('.ethereum-ico-purchases-account-wrapper').removeClass('hidden');
                    jQuery('.ethereum-ico-purchases-account-wrapper').removeAttr('hidden');
                    if (jQuery('#ethereum-ico-purchases-account').val() !== '') {
                        accounts = [jQuery('#ethereum-ico-purchases-account').val()];
                    }
                }
                return;
            }

            if (0 === accounts.length) {
                console.log(window.ico.str_unlock_metamask_account); 
                jQuery('.ethereum-ico-purchases-account-wrapper').removeClass('hidden');
                jQuery('.ethereum-ico-purchases-account-wrapper').removeAttr('hidden');
                if (jQuery('#ethereum-ico-purchases-account').val() !== '') {
                    accounts = [jQuery('#ethereum-ico-purchases-account').val()];
                }
            }

            if (accounts.length > 0) {
                load_and_render_transactions(window.ico.crowdsaleAddress, accounts[0]);
            }

        });
    } else {
        jQuery('.ethereum-ico-purchases-account-wrapper').addClass('hidden');
        jQuery('.ethereum-ico-purchases-account-wrapper').attr('hidden', 'hidden');
        load_and_render_transactions(window.ico.crowdsaleAddress);
    }
}

function ETHEREUM_ICO_calc_display_value( val ) {
    if (val < 1) {
        return [0.01 * ETHEREUM_ICO_round (100 * val, 2, "PHP_ROUND_HALF_UP"), ''];
    }
    if (val < 1000) {
        return [ETHEREUM_ICO_round (val, 1, "PHP_ROUND_HALF_UP"), ''];
    }
    if (val < 1000000) {
        return [ETHEREUM_ICO_round (0.001 * val, 1, "PHP_ROUND_HALF_UP"), window.ico.str_kilo_label];
    }
    return [ETHEREUM_ICO_round (0.000001 * val, 1, "PHP_ROUND_HALF_UP"), window.ico.str_mega_label];
}

// Replace ETH with USD if needed
function ETHEREUM_ICO_updateProgressValues() {
    var total_value_eth = jQuery(".ethereum-ico-progress-total-received-eth").text();
    var factor = ETHEREUM_ICO_calc_factor ("ETH", window.ico.base_currency);
    var total_value_base = parseFloat(total_value_eth) * factor;
    var display_val = ETHEREUM_ICO_calc_display_value( total_value_base );
    var total_value_display = ETHEREUM_ICO_sprintf(window.ico.str_currency_display_format
        , window.ico.base_symbol
        , display_val[0]
        , display_val[1]);
    var total_received_percent = (100 * total_value_base / parseFloat(window.ico.hardcap)).toFixed(2);
    var total_received_percent_display = ETHEREUM_ICO_sprintf(window.ico.str_percent_complete_format
        , total_received_percent);
    jQuery(".ethereum-ico-progress-total-display").text(total_value_display);
    jQuery(".ethereum-ico-progress-progress-bar").attr("aria-valuenow", total_value_display);
    jQuery(".ethereum-ico-progress-progress-bar").attr("title", total_value_display);
    jQuery(".ethereum-ico-progress-progress-bar").css("width", total_received_percent + "%");
    jQuery(".ethereum-ico-progress-percent-complete").text(total_received_percent_display);
}

// Replace ETH with USD if needed
function ETHEREUM_ICO_updateProgressPercent() {
    var total_value_eth = jQuery(".ethereum-ico-progress-percent-total-received-eth").text();
    var factor = ETHEREUM_ICO_calc_factor ("ETH", window.ico.base_currency);
    var total_value_base = parseFloat(total_value_eth) * factor;
    var total_received_percent = (100 * total_value_base / parseFloat(window.ico.hardcap)).toFixed(2);
    jQuery(".ethereum-ico-progress-percent-display").text(total_received_percent);
}

// Replace ETH with USD if needed
function ETHEREUM_ICO_updateProgressValue() {
    var total_value_eth = jQuery(".ethereum-ico-progress-value-total-received-eth").text();
    var factor = ETHEREUM_ICO_calc_factor ("ETH", window.ico.base_currency);
    var total_value_base = parseFloat(total_value_eth) * factor;
    var display_val = ETHEREUM_ICO_calc_display_value( total_value_base );
    var total_value_display = ETHEREUM_ICO_sprintf(window.ico.str_currency_display_format
        , window.ico.base_symbol
        , display_val[0]
        , display_val[1]);
    jQuery(".ethereum-ico-progress-value-total-display").text(total_value_display);
}

// if referree address is not set, try to obtain it from MetaMask
function ico_updateReferreeAddress() {
    if ('' !== jQuery("#ethereum-ico-referral-account").val()) {
        return;
    }
    // 1. проверить, есть ли аккаунт. если нет, показать поле ввода адреса
    // 2. если есть, загрузить транзакции для адреса
    getUserAccounts(function(err, accounts) {
        if (err) {
            console.log(err); 
            if (err === window.ico.str_unlock_metamask_account) {
                jQuery('.ethereum-ico-referral-account-wrapper').removeClass('hidden');
                jQuery('.ethereum-ico-referral-account-wrapper').removeAttr('hidden');
            }
            else if (err === window.ico.str_download_metamask) {
                jQuery('.ethereum-ico-referral-account-wrapper').removeClass('hidden');
                jQuery('.ethereum-ico-referral-account-wrapper').removeAttr('hidden');
            }
            return;
        }

        if (0 === accounts.length) {
            console.log("Metamask account not found"); 
            jQuery('.ethereum-ico-referral-account-wrapper').removeClass('hidden');
            jQuery('.ethereum-ico-referral-account-wrapper').removeAttr('hidden');
            return;
        }
        
        jQuery("#ethereum-ico-referral-account").val(accounts[0]);
        setTimeout(changeReferralAccount, 1);

    });
}

function render_transactions(transactions, token_transactions, toAddress, fromAddress) {
    // clear tbody
    jQuery( ".ethereum-ico-purchases-table tbody" ).html("");

    var blockchain_network = '';
    if ('mainnet' !== window.ico.blockchain_network) {
        blockchain_network = window.ico.blockchain_network + '.';
    }
    var count = 0;
    var factor = ETHEREUM_ICO_calc_factor ("ETH", window.ico.base_currency);
    var token_in_wei = parseFloat(window.ico.decimals);
    for (var i = 0; i < transactions.length; i++) {
        var t = transactions[i];
        if (t.value === '0') {
            continue;
        }
        if ('undefined' !== typeof t['isError'] && t.isError === '1') {
            continue;
        }
        if ('undefined' !== typeof toAddress && toAddress.toLowerCase() !== t.to.toLowerCase()) {
            continue;
        }
        if ('undefined' !== typeof fromAddress && fromAddress.toLowerCase() !== t.from.toLowerCase()) {
            continue;
        }
        var days = (new Date - new Date(parseInt(t.timeStamp) * 1000)) / (24 * 3600 * 1000);
        var dateString = '';
        if (days >= 1) {
            dateString = Math.floor(days) + window.ico.str_table_days_label;
        } else {
            var hours = 24 * days;
            if (hours >= 1) {
                dateString = Math.floor(hours) + window.ico.str_table_hours_label;
            } else {
                var minutes = 60 * hours;
                if (minutes >= 1) {
                    dateString = Math.floor(minutes) + window.ico.str_table_minutes_label;
                } else {
                    dateString = window.ico.str_table_recently_label;
                }
            }
        }
        var thash = t.hash.substr(0, 8);
        var valueEth = parseFloat(t.value) / 1000000000000000000;
        var valueToken = 0;
        if ('undefined' === typeof token_transactions[t.hash]) {
            valueToken = valueEth * parseFloat(window.ico.tokenRate);
        } else {
            valueToken = parseFloat(token_transactions[t.hash].value) / token_in_wei;
        }
        var valueBase = valueEth * factor;
        var display_val = ETHEREUM_ICO_calc_display_value( valueBase );
        var value_display = ETHEREUM_ICO_sprintf(window.ico.str_currency_display_format
            , window.ico.base_symbol
            , display_val[0].toFixed("ETH" === window.ico.base_currency ? 5 : 2)
            , display_val[1]);
        var tr = '<tr>';
        tr += '<th scope="row">' + (count + 1) + '</th>';
        tr += '<td>' + value_display + '</td>';
        tr += '<td>' + valueToken + '</td>';
        tr += '<td>' + dateString + '</td>';
        tr += '<td><a target="_blank" href="https://' + blockchain_network + 'etherscan.io/tx/' + t.hash + '">' + thash + '</td>';
        tr += '</tr>';
        jQuery( tr ).appendTo( ".ethereum-ico-purchases-table tbody" );
        count++;
        if (count >= 10) {
            break;
        }
    }
}

function load_transactions(address, callback) {
    if ('' === address) {
        callback.call(null, window.ico.str_empty_address_error, null);
        return;
    }
    var blockchain_network = '';
    if ('mainnet' !== window.ico.blockchain_network) {
        blockchain_network= '-' + window.ico.blockchain_network;
    }
    // https://stackoverflow.com/a/42538992/4256005
    jQuery.ajax({
        headers:{  
            "Accept":"application/json",//depends on your api
            "Content-type":"application/x-www-form-urlencoded"//depends on your api
        },
        url: "https://api" + blockchain_network + ".etherscan.io/api?module=account&action=txlist&address=" + address + "&startblock=0&endblock=999999999&sort=desc",
        success:function(r) {
            if (r.status !== "1") {
                console.log(r.message);
                callback.call(null, r.message, null);
                return;
            }
            var trxns = r.result;
            callback.call(null, null, trxns);
        }
    });
}

function load_and_render_transactions(to, from) {
    load_transactions(to, function(err, transactions) {
        if (err) {
            console.log(err);
            return;
        }

        load_token_transactions(to, function(err, token_transactions) {
            if (err) {
                console.log(err);
                token_transactions = [];
            }
            
            var token_transactions2 = {};
            for(var i = 0; i < token_transactions.length; i++) {
                var tx = token_transactions[i];
                token_transactions2[tx.hash] = tx;
            }

            if (jQuery('.ethereum-ico-purchases-table').text() !== '') {
                render_transactions(transactions, token_transactions2, to, from);
            }
        });
    });
}

function load_token_transactions(address, callback) {
    if ('' === address) {
        callback.call(null, window.ico.str_empty_address_error, null);
        return;
    }
    var blockchain_network = '';
    if ('mainnet' !== window.ico.blockchain_network) {
        blockchain_network= '-' + window.ico.blockchain_network;
    }
    // https://stackoverflow.com/a/42538992/4256005
    jQuery.ajax({
        headers:{  
            "Accept":"application/json",//depends on your api
            "Content-type":"application/x-www-form-urlencoded"//depends on your api
        },
        url: "https://api" + blockchain_network + ".etherscan.io/api?module=account&action=tokentx&address=" + address + "&startblock=0&endblock=999999999&sort=desc",
        success:function(r) {
            if (r.status !== "1") {
                console.log(r.message);
                callback.call(null, r.message, null);
                return;
            }
            var trxns = r.result;
            callback.call(null, null, trxns);
        }
    });
}
