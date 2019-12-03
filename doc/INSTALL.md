Installation instructions
=========================

Requirements
------------

* eZ Platform 1.0+

Installation steps
------------------

### Use Composer

Run the following from your website root folder to install PrimeTranslationsBundle:

```bash
$ composer require primedigital/translations-bundle
```

### Activate the bundle

Activate required bundles in `app/AppKernel.php` file by adding them to the `$bundles` array in `registerBundles` method:

```php
public function registerBundles()
{
    ...
    $bundles[] = new Lexik\Bundle\TranslationBundle\LexikTranslationBundle();
    $bundles[] = new Prime\Bundle\TranslationsBundle\PrimeTranslationsBundle();

    return $bundles;
}
```

### Include routing configuration

In your main routing configuration file probably `routing.yml` add:

```yaml
prime_translations:
    resource: '@PrimeTranslationsBundle/Resources/config/routing.yml'
```

### Set set the default configuration:


```yaml
lexik_translation:
    fallback_locale: [en]         # (required) default locale(s) to use
    managed_locales: [en, fr]         # (required) locales that the bundle has to manage

    base_layout: "PrimeTranslationsBundle::pagelayout.html.twig"
    grid_input_type: text       # text|textarea
    grid_toggle_similar: false
    storage:
        type: orm                  # orm | mongodb | propel
```


### Install Lexik tables

```bash
$ php bin/console doctrine:schema:update --force
```


### Import your project translations into database

```bash
$ php bin/console lexik:translations:import AppBundle
``` 

### Install assets

Clear the eZ Publish caches with the following command:

```bash
$ php bin/console assets:install --symlink
```

### Clear the caches

Clear the eZ Publish caches with the following command:

```bash
$ php bin/console cache:clear
```

For more detailed configuration, please check [documentation](DOC.md).
