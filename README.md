yii-sphinx
==========

Yii extension for Sphinx Search

forked from http://www.yiiframework.com/extension/dgsphinxsearch/


```php
return array(
    'aliases' => array(
        'sphinx' => realpath(VENDOR_PATH . '/cornernote/yii-sphinx/sphinx'),
    ),
    'components' => array(
        'sphinx' => array(
            'class' => 'sphinx.components.ESphinxSearch',
            'server' => '127.0.0.1',
            'port' => 9312,
            'maxQueryTime' => 3600,
            'enableProfiling' => YII_DEBUG,
            'enableResultTrace' => YII_DEBUG,
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


Search by criteria Object:

```php
// create the criteria
$searchCriteria = new ESphinxCriteria();
$searchCriteria->select = 'id';
$searchCriteria->filters = array('status' => 1);
$searchCriteria->query = 'keywords';
$searchCriteria->from = 'product';
$searchCriteria->groupby = 'price';
$searchCriteria->orders = array('name' => 'ASC');
$searchCriteria->paginator->pageSize = 1000;
$searchCriteria->fieldWeights = array(
    'name' => 20,
    'description' => 1,
);

// get the result
$resIterator = Yii::app()->sphinx->search($searchCriteria); // interator result
// or
$resArray = Yii::app()->sphinx->searchRaw($searchCriteria); // array result
```


Search by SQL-like syntax:

```php
$search->select('*')->
    from($indexName)->
    where($expression)->
    filters(array('project_id' => $this->_city->id))->
    groupby($groupby)->
    orderby(array('f_name' => 'ASC'))->
    limit(0, 30);
$resIterator = $search->search(); // interator result
/* OR */
$resArray = $search->searchRaw(); // array result
```


Search by SphinxClient syntax:

```php
$search = Yii::App()->search;
$search->setSelect('*');
$search->setArrayResult(false);
$search->setMatchMode(SPH_MATCH_EXTENDED);
$search->setFieldWeights($fieldWeights)
$resArray = $search->query( $query, $indexName);
```


Combined Method:

```php
$search = Yii::App()->search->
    setArrayResult(false)->
    setMatchMode(SPH_MATCH_EXTENDED);
$resIterator = $search->select('field_1, field_2')->search($searchCriteria);
```


Finding Models:

```php
$ids = array_keys($resArray['matches']);
$criteria = new CDbCriteria;
$criteria->addInCondition('id', $ids);
$criteria->order = 'FIELD(t.id, ' . implode(', ', $ids) . ')'; // order by weight
$products = Product::model()->findAll($criteria);
```


Real-Time Index via ActiveRecord:

```php
class Product extends CActiveRecord
{

    public function tableName()
    {
        return 'product';
    }

    public function afterSave() 
    {
        ProductIndex::model()->updateIndex($this);
    }

    public function afterDelete() 
    {
        ProductIndex::model()->deleteIndex($this);
    }

}


class ProductIndex extends ESphinxActiveRecord
{

    public function tableName()
    {
        return 'product';
    }

    public function truncateIndex()
    {
        $this->dbConnection->createCommand('TRUNCATE RTINDEX ' . ProductIndex::model()->tableName())->execute();
    }

    public function deleteIndex($product)
    {
        $this->dbConnection->createCommand("DELETE FROM " . ProductIndex::model()->tableName() . " WHERE id = " . $product->id)->execute();
    }

    public function updateIndex($product)
    {
        $productIndex = new ProductIndex();
        $productIndex->id = $product->id;
        $productIndex->name = $product->name;
        $productIndex->description = $product->description;
        $productIndex->quantity = $product->quantity;
        $productIndex->status = $product->status;
        $productIndex->save(false);
    }

}
```