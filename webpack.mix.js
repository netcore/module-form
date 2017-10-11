let mix = require('laravel-mix');

const moduleDir = __dirname;
const resPath = moduleDir + '/Resources/assets';
const compileTo = moduleDir + '/Assets';

mix.setPublicPath('.')
mix.js(resPath + '/js/form.js', compileTo + '/admin/js/form.js');
	
mix.styles([
    resPath + '/css/form.css'
], compileTo + '/admin/css/form.css');
