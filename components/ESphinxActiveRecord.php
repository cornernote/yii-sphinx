<?php

/**
 * ESphinxActiveRecord
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2014 Mr PHP
 * @link https://github.com/cornernote/yii-sphinx
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-yii-sphinx/master/LICENSE
 *
 * @package yii-sphinx
 */
class ESphinxActiveRecord extends CActiveRecord
{

    /**
     * @var CDbConnection the default database connection for all active record classes.
     * By default, this is the 'db' application component.
     * @see getDbConnection
     */
    public static $db;

    /**
     * @throws CDbException
     * @return mixed
     */
    public function getDbConnection()
    {
        if (self::$db !== null)
            return self::$db;
        else {
            self::$db = Yii::app()->dbSphinx;
            if (self::$db instanceof CDbConnection) {
                self::$db->setActive(true);
                return self::$db;
            }
            else
                throw new CDbException(Yii::t('app', 'ESphinxActiveRecord requires a "dbSphinx" CDbConnection application component.'));
        }
    }

}
