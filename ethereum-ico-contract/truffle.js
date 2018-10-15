
var HDWalletProvider = require("truffle-hdwallet-provider");
const MNEMONIC = "wait pave peace shy street skirt prosper future cat toddler grow live";
module.exports = {
  networks: {
    development: {
      host: 'localhost',
      port: 8545,
      network_id: '*' // Match any network id
    },
    ropsten: {
      provider: new HDWalletProvider(MNEMONIC, "https://ropsten.infura.io/wefbowufweufbweu"), // The actual api key infura gave you
      network_id: '3',
	  gas:4712388,
	  gasPrice: 100000000000 
    }
  }
}

//f46e521893c18cd626998cdb0a3379485789dab297563c683926440b9f913370