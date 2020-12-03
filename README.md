Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require kaliber5/settings-bundle
```

Continue with Step 3.

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require kaliber5/settings-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Kaliber5\SettingsBundle\Kaliber5SettingsBundle::class => ['all' => true],

];
```

### Step 3: Generate Migrations

Then, adjust your database structure to add the new entities.

```console
$ bin/console doctrine:migrations:diff
$ bin/console doctrine:migrations:migrate
```

### Step 4: Usage

If you're already using sonata admin-bundle, the settings admin will be available immediatly to add settings
in your code you can retrieve the settings by using the `SettingsProvider`:
 
 ```php
 // ...
use Kaliber5\SettingsBundle\Provider\SettingsProvider;
// ... in some class
    /**
     * @var SettingsProvider
     */
    private $settingsProvider;
    
    // ...in some method
    $mixed = $this->settingsProvider->getSettingValuesByCode('unique_code_of_setting');
 
 ```