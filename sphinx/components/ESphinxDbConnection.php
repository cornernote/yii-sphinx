<?php

/**
 * ESphinxDbConnection
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2014 Mr PHP
 * @link https://github.com/cornernote/yii-sphinx
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-yii-sphinx/master/LICENSE
 *
 * @package yii-sphinx
 */
class ESphinxDbConnection extends CDbConnection
{

    /**
     * @var array mapping between PDO driver and schema class name.
     * A schema class can be specified using path alias.
     */
    public $driverMap = array(
        'mysqli' => 'sphinx.components.ESphinxSchema',
        'mysql' => 'sphinx.components.ESphinxSchema',
    );

}
