let mix = require('laravel-mix');

const moduleDir = __dirname;
const resPath = moduleDir + '/Resources/assets';
const compileTo = moduleDir + '/Assets';

mix.setPublicPath('.')
mix.js(resPath + '/js/forms_index.js', compileTo + '/admin/js/forms_index.js');
mix.js(resPath + '/js/forms_form.js', compileTo + '/admin/js/forms_form.js');
	
mix.styles([
    resPath + '/css/form.css'
], compileTo + '/admin/css/form.css');
