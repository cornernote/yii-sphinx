<?php

/**
 * ESphinxSchema
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2014 Mr PHP
 * @link https://github.com/cornernote/yii-sphinx
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-yii-sphinx/master/LICENSE
 *
 * @package yii-sphinx
 */
class ESphinxSchema extends CMysqlSchema
{

    /**
     * Creates a command builder for the database.
     * This method may be overridden by child classes to create a DBMS-specific command builder.
     * @return CDbCommandBuilder command builder instance
     */
    protected function createCommandBuilder()
    {
        Yii::import('sphinx.components.ESphinxCommandBuilder');
        return new ESphinxCommandBuilder($this);
    }

    /**
     * Collects the table column metadata.
     * @param CMysqlTableSchema $table the table metadata
     * @return boolean whether the table exists in the database
     */
    protected function findColumns($table)
    {
        $sql = 'DESCRIBE ' . $table->rawName;
        try {
            $columns = $this->getDbConnection()->createCommand($sql)->queryAll();
        } catch (Exception $e) {
            return false;
        }
        foreach ($columns as $column) {
            $c = $this->createColumn($column);
            $table->columns[$c->name] = $c;
            if ($c->isPrimaryKey) {
                if ($table->primaryKey === null)
                    $table->primaryKey = $c->name;
                elseif (is_string($table->primaryKey))
                    $table->primaryKey = array($table->primaryKey, $c->name);
                else
                    $table->primaryKey[] = $c->name;
                if ($c->autoIncrement)
                    $table->sequenceName = '';
            }
        }
        return true;
    }

    /**
     * Creates a table column.
     * @param array $column column metadata
     * @return CDbColumnSchema normalized column metadata
     */
    protected function createColumn($column)
    {
        $c = new CMysqlColumnSchema;
        $c->name = $column['Field'];
        $c->rawName = $this->quoteColumnName($c->name);
        $c->isPrimaryKey = $column['Field'] === 'id';
        $c->init($column['Type'], '');
        return $c;
    }

    /**
     * Collects the foreign key column details for the given table.
     * @param CMysqlTableSchema $table the table metadata
     */
    protected function findConstraints($table)
    {
    }

}
