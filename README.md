yii-sphinx
==========

Yii extension for Sphinx Search


```
return array(
    'aliases' => array(
        'sphinx' => realpath(VENDOR_PATH . '/cornernote/yii-sphinx/sphinx'),
    ),
    'components' => array(
       'dbSphinx' => array(
            'class' => 'vendor.cornernote.yii-sphinx.sphinx.components.ESphinxDbConnection',
            'connectionString' => 'mysql:host=localhost;port=9306;dbname=sphinx',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'schemaCachingDuration' => 3600,
            'enableProfiling' => YII_DEBUG,
            'enableParamLogging' => YII_DEBUG,
        ),
     ),
);
```