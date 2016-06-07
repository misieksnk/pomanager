misieksnk/pomanager
=============

> PO files manager

Installation
------------

Simply use [Composer](https://getcomposer.org):

```shell
$ composer require misieksnk/pomanager
```

Usage
------------
```php
use MisiekSnk\PoManager\PoManager;
```

Open .po file

```php
$poManager = new PoManager('filename.po');
```

Get all translations array from .po file

```php
$poManager->getTranslationsArray();
// [
    'msgid_1' => 'msgstr 1',
    'msgid_2' => 'msgstr 2',
    ...
]
```

Get translation by msgid

```php
$translation = $poManager->getTranslation('msgid_1'); // 'msgstr 1'
```

Change translation for msgid

```php
$msgid = 'msgid_2';
$msgstr = 'translated msgid 2';
$poManager->setTranslation($msgid, $msgstr);
```

Update .mo file with current .po content

```php
$poManager->updateMo();
```

ToDo
------------

- Batch updates