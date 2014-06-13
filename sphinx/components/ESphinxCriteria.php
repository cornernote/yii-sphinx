<?php

/**
 * ESphinxCriteria
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2014 Mr PHP
 * @link https://github.com/cornernote/yii-sphinx
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-yii-sphinx/master/LICENSE
 *
 * @package yii-sphinx
 * @property CPagination $paginator
 */
class ESphinxCriteria extends CComponent
{
    /**
     * @var string $select
     */
    public $select;

    /**
     * @var array
     */
    public $filters = array();

    /**
     * @var
     */
    public $query;

    /**
     * @var
     */
    public $groupby;

    /**
     * @var array
     */
    public $orders = array();

    /**
     * @var
     */
    public $from;

    /**
     * @var array
     */
    public $fieldWeights = array();

    /**
     * @var CPagination
     */
    private $_paginator;

    /**
     * @return CPagination
     */
    public function getPaginator()
    {
        if (!$this->_paginator) {
            $this->_paginator = new CPagination();
        }
        return $this->_paginator;
    }

    /**
     * @param CPagination $paginator
     */
    public function setPaginator($paginator)
    {
        $this->_paginator = $paginator;
    }

}