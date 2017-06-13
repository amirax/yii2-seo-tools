# Amirax SEO Tools for Yii 2

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require amirax/yii2-seo-tools "*"
```

or add

```
"amirax/yii2-seo-tools": "*"
```

to the require section of your `composer.json` file.

After installation extension run migration:

```
./yii migrate --migrationPath="@vendor/amirax/yii2-seo-tools/src/migrations"
```

## Usage

### SEO Meta
In components configuration add the following
```php
'components' => [
    'seo' => [
        'class' => 'Amirax\SeoTools\Meta'
    ]
    ...
]
```

And add SEO extension to bootstrap
```php
'bootstrap' => ['log', 'seo']
```

Extension will automatically load the correct row from the database using the currently
running and params.You can optionally override data by specifying them in a parameter array
```php
Yii::$app->seo->title = 'Page title';
Yii::$app->seo->metakeys = 'seo,yii2,extension';
Yii::$app->seo->metadesc = 'Page meta description';
Yii::$app->seo->tags['og:type'] = 'article';
```

You can set the templates for tags. For example:
```php
Yii::$app->seo->setVar('USER_NAME', 'Amirax');
Yii::$app->seo->tags['og:title'] = 'Hello %USER_NAME%';
```

Default variables:
* %HOME_URL%       - Homepage url
* %CANONICAL_URL%  - Canonical URL for current page
* %LOCALE%         - Site locale

### SEO Redirect
For enabling SEO Redirect add to configuration file 
```php
'errorHandler' => [
    'class' => 'Amirax\SeoTools\Redirect',
],
```