 const path = require('path'); // Helps to find the path to the contract across whatever OS you are using form compile.js to xxx.sol files
    const fs = require('fs'); // Load the FileSystem Module.
    const solc = require('solc');

    const contractPath = path.resolve(__dirname, 'contracts', 'Contracts_Remix.sol'); //Creation of cross SO's path.

    const source = fs.readFileSync(contractPath, 'utf8'); 
    //We compile the source code, of 1 single contract and showed the bytecode and the ABI by console to examine it.
    console.log(solc.compile(source),1); 
    module.exports = solc.compile(source).contracts[':Your_contract_name']; 
//We only call the contract we want to deploy.