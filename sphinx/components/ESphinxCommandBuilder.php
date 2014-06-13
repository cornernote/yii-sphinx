<?php

/**
 * ESphinxCommandBuilder
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2014 Mr PHP
 * @link https://github.com/cornernote/yii-sphinx
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-yii-sphinx/master/LICENSE
 *
 * @package yii-sphinx
 */
class ESphinxCommandBuilder extends CDbCommandBuilder
{

    /**
     * Creates a SELECT command for a single table.
     * @param mixed $table the table schema ({@link CDbTableSchema}) or the table name (string).
     * @param CDbCriteria $criteria the query criteria
     * @param string $alias the alias name of the primary table. Defaults to 't'.
     * @return CDbCommand query command.
     */
    public function createFindCommand($table, $criteria, $alias = 't')
    {
        $this->ensureTable($table);
        $select = is_array($criteria->select) ? implode(', ', $criteria->select) : $criteria->select;
        $sql = ($criteria->distinct ? 'SELECT DISTINCT' : 'SELECT') . " {$select} FROM {$table->rawName}";
        $sql = $this->applyJoin($sql, $criteria->join);
        $sql = $this->applyCondition($sql, $criteria->condition);
        $sql = $this->applyGroup($sql, $criteria->group);
        $sql = $this->applyHaving($sql, $criteria->having);
        $sql = $this->applyOrder($sql, $criteria->order);
        $sql = $this->applyLimit($sql, $criteria->limit, $criteria->offset);
        $command = $this->dbConnection->createCommand($sql);
        $this->bindValues($command, $criteria->params);
        return $command;
    }

    /**
     * Generates the expression for selecting rows of specified primary key values.
     * @param mixed $table the table schema ({@link CDbTableSchema}) or the table name (string).
     * @param mixed $columnName the column name(s). It can be either a string indicating a single column
     * or an array of column names. If the latter, it stands for a composite key.
     * @param array $values list of key values to be selected within
     * @param string $prefix column prefix (ended with dot). If null, it will be the table name
     * @return string the expression for selection
     */
    public function createInCondition($table, $columnName, $values, $prefix = null)
    {
        parent::createInCondition($table, $columnName, $values);
    }

    /**
     * Creates a REPLACE command.
     * @param CDbTableSchema $table the table schema ({@link CDbTableSchema}) or the table name (string).
     * @param array $data data to be inserted (column name=>column value). If a key is not a valid column name, the corresponding value will be ignored.
     * @return CDbCommand insert command
     */
    public function createReplaceCommand($table, $data)
    {
        $this->ensureTable($table);
        $fields = array();
        $values = array();
        $placeholders = array();
        $i = 0;
        foreach ($data as $name => $value) {
            if (($column = $table->getColumn($name)) !== null && ($value !== null || $column->allowNull)) {
                $fields[] = $column->rawName;
                if ($value instanceof CDbExpression) {
                    $placeholders[] = $value->expression;
                    foreach ($value->params as $n => $v)
                        $values[$n] = $v;
                }
                else {
                    $placeholders[] = self::PARAM_PREFIX . $i;
                    $values[self::PARAM_PREFIX . $i] = $column->typecast($value);
                    $i++;
                }
            }
        }
        if ($fields === array()) {
            $pks = is_array($table->primaryKey) ? $table->primaryKey : array($table->primaryKey);
            foreach ($pks as $pk) {
                $fields[] = $table->getColumn($pk)->rawName;
                $placeholders[] = 'NULL';
            }
        }
        $sql = "REPLACE INTO {$table->rawName} (" . implode(', ', $fields) . ') VALUES (' . implode(', ', $placeholders) . ')';
        $command = $this->dbConnection->createCommand($sql);

        foreach ($values as $name => $value)
            $command->bindValue($name, $value);

        return $command;
    }

    /**
     * Creates an INSERT command.
     * @param mixed $table the table schema ({@link CDbTableSchema}) or the table name (string).
     * @param array $data data to be inserted (column name=>column value). If a key is not a valid column name, the corresponding value will be ignored.
     * @return CDbCommand insert command
     */
    public function createInsertCommand($table, $data)
    {
        return $this->createReplaceCommand($table, $data);
    }

    /**
     * Creates an UPDATE command.
     * @param CDbTableSchema|string $table the table schema ({@link CDbTableSchema}) or the table name (string).
     * @param array $data list of columns to be updated (name=>value)
     * @param CDbCriteria $criteria the query criteria
     * @throws CDbException
     * @return CDbCommand update command.
     */
    public function createUpdateCommand($table, $data, $criteria)
    {
        return $this->createReplaceCommand($table, $data);
    }

}
