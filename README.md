yii-sphinx
==========

Yii extension for Sphinx Search

forked from http://www.yiiframework.com/extension/dgsphinxsearch/


```
return array(
    'aliases' => array(
        'sphinx' => realpath(VENDOR_PATH . '/cornernote/yii-sphinx/sphinx'),
    ),
    'components' => array(
        'sphinx' => array(
            'class' => 'sphinx.components.ESphinxSearch',
            'server' => '127.0.0.1',
            'port' => 9312,
            'maxQueryTime' => 3000,
            'enableProfiling'=>0,
            'enableResultTrace'=>0,
        ),
       'dbSphinx' => array(
            'class' => 'sphinx.components.ESphinxDbConnection',
            'connectionString' => 'mysql:host=127.0.0.1;port=9306',
            'emulatePrepare' => true,
            'charset' => 'utf8',
            'schemaCachingDuration' => 3600,
            'enableProfiling' => YII_DEBUG,
            'enableParamLogging' => YII_DEBUG,
        ),
     ),
);
```