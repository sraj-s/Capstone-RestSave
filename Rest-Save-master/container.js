// Dependable
var dependable = require('dependable');
// Creating Container 
var container = dependable.container();
var path = require('path');

var simpleDependencies = [
    ['_', 'lodash'],
    ['passport', 'passport'],
    ['path', 'path'],
	['async', 'async'],
	['formidable', 'formidable'],
    // Users
	['User', './models/user-model'],
	['Post', './models/post-model']
];

simpleDependencies.forEach(function(dependency){
    container.register(dependency[0], function(){
        return require(dependency[1]);
    });
});

container.load(path.join(__dirname, "/helpers"));
container.load(path.join(__dirname, "/controllers"));

container.register('container', function(){
    return container;
});

module.exports = container;