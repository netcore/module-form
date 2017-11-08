## Module for creating dynamic forms
This module was made for easy management of custom forms such as Contact form, Subscribe form etc.

## Pre-installation

This package is part of Netcore CMS ecosystem and is only functional in a project that has following packages
installed:

1. https://github.com/netcore/netcore
2. https://github.com/netcore/module-admin
3. https://github.com/netcore/module-translate

### Installation

 - Require this package using composer
```
    composer require netcore/module-form
```

 - Publish assets/configuration/migrations
```
    php artisan module:publish Form
    php artisan module:publish-config Form
    php artisan module:publish-migration Form
    php artisan migrate
```
 
### Configuration

 - Configuration file is available at config/netcore/module-form.php

### Usage

- Render form
```php
    app('forms')->replace('[form=(ID/Key)]');
    // or you can use helper method 
    form()->render('ID/Key');
```

- Custom template
```php
    If you want to render form with different template,
    you can create new file in /views/templates/forms (path can be changed in configuration),
    for example - contact-us.blade.php, where "contact-us" can be form key or the template name set when creating/editing form
```

- Extend form submit functionality
```php
    // For example, if you want to send email to Administrator and/or User, who submitted the "Contact Us" form
    use Modules\Form\Repositories\FormsRepository;
    use Nwidart\Modules\Facades\Module;
    
    $module = Module::find('form');
    if ($module AND $module->enabled()) {
        FormsRepository::addNewEvent('contact-us', function ($data) {
            Mail::to('admin@example.com')->queue(new NotifyAboutContactMessage($data));
        });
    }
    // $data variable is array, which consists of submitted data, for example
    $data = [
        'name'      => 'John',
        'email'     => 'example@example.com',
        'message'   => 'Lorem ipsum.'
    ];
```
