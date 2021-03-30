let glob = require('glob');

configs = [
    './platform/*/webpack.mix.js',
    './platform/**/*/webpack.mix.js',
];

configs.forEach(config => glob.sync(config).forEach(item => require(item)));
