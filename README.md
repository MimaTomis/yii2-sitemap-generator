# Yii2 Sitemap Generator

[![Build Status](https://travis-ci.org/MimaTomis/yii2-sitemap-generator.svg)](https://travis-ci.org/MimaTomis/yii2-sitemap-generator)

This library is implementation of mima/sitemap-generator for yii2 framework. It delivers a custom component and writer based on yii2 features.
Documentation about sitemap generator library you can find [here](https://github.com/MimaTomis/sitemap-generator#sitemap-generator).

* [Installation](#installation)
* [Usage](#usage)

## Installation

Install with composer from command line:

```
composer require mima/yii2-sitemap-generator
```

Or add dependency to require section in your composer.json:

```json
{
    "require": {
        "mima/yii2-sitemap-generator": "~1.0"
    }
}
```

## Usage

Configure component on runtime:

```php
use SitemapGenerator\Component\SitemapGeneratorComponent;
use SitemapGenerator\Factory\SimpleGeneratorFactory;
use MyNamespace\Extractor\MyDataExtractor;

\Yii::$app->set('sitemapGenerator', [
    'class' => SitemapGeneratorComponent::class,
    'directoryToSaveSitemap' => '@webroot',
    'fileName' => 'sitemap.xml',
    'extractor' => MyDataExtractor::class,
    'generatorFactory' => SimpleGeneratorFactory::class
]);
```

Configure in application config:

```php
return [
    // ...
    'components' => [
        'sitemapGenerator' => [
            'class' => SitemapGenerator\Component\SitemapGeneratorComponent::class,
            'directoryToSaveSitemap' => '@webroot',
            'fileName' => 'sitemap.xml',
            'extractor' => MyNamespace\Extractor\MyDataExtractor::class,
            'generatorFactory' => SitemapGenerator\Factory\SimpleGeneratorFactory::class
        ]
    ]
    //...
];
```

The following are valid settings:

* *directoryToSaveSitemap* - real path or yii alias to directory, where sitemap will be saved
* *fileName* - name of sitemap file
* *extractor* - name of class instance of `SitemapGenerator\Extractor\DataExtractorInterface`
* *generatorFactory* - name of class instance of `SitemapGenerator\Factory\GeneratorFactoryInterface`

For generating sitemap call `generate` method:

```php
// This code generate sitemap and return path to file, containig sitemap
$filePath = \Yii::$app->get('sitemapGenerator')->generate();
```

