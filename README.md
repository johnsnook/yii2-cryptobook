Yii2 Encrypted Book
===================
Ever wanted to write a book where the chapter titles and contents are entirely encrypted?  Probably not, but I did, so I wrote this.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist johnsnook/yii2-cryptobook "*"
```

or add

```
"johnsnook/yii2-cryptobook": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \johnsnook\cryptobook\AutoloadExample::widget(); ?>```
```