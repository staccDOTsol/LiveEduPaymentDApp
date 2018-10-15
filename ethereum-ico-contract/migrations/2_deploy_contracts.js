var Token = artifacts.require("./Token.sol");
var Sale = artifacts.require("./Sale.sol");

module.exports = function(deployer) {

  deployer.deploy(Sale, "0x8A65b81AA5b3244007Ef8649338E1A41115FCE3c").then(function() {
		return deployer.deploy(Token, Sale.address);
	});
	
};