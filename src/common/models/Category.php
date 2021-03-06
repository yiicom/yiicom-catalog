<?php

namespace yiicom\catalog\common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use creocoder\nestedsets\NestedSetsBehavior;
use yiicom\common\interfaces\ModelStatus;
use yiicom\common\interfaces\ModelList;
use yiicom\common\interfaces\ModelRelations;
use yiicom\common\traits\ModelStatusTrait;
use yiicom\common\traits\ModelListTrait;
use yiicom\common\traits\ModelRelationsTrait;
use yiicom\content\common\interfaces\ModelPageUrl;
use yiicom\content\common\traits\ModelPageUrlTrait;
use yiicom\content\common\models\PageUrl;
use yiicom\content\common\behaviors\PageUrlBehavior;
use yiicom\files\common\behaviors\FilesBehavior;
use yiicom\files\common\models\File;

/**
 * @property integer $id
 * @property integer $left
 * @property integer $right
 * @property integer $level
 * @property integer $parentId
 * @property string $name
 * @property string $title
 * @property string $teaser
 * @property string $body
 * @property boolean $isShowPrice
 * @property integer $status
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @property Category $parent
 *
 * @method ActiveQuery parents() NestedSetsBehavior
 * @method ActiveQuery children() NestedSetsBehavior
 */
class Category extends ActiveRecord implements ModelStatus, ModelList, ModelRelations, ModelPageUrl
{
    use ModelStatusTrait, ModelListTrait, ModelRelationsTrait, ModelPageUrlTrait;

    /**
     * @return string
     */
	public static function tableName()
	{
		return '{{%catalog_category}}';
	}

    /**
     * @return CategoryQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * @return array
     */
	public function rules()
	{
		return [
		    ['parentId', 'integer'],

			['name', 'filter', 'filter' => 'trim'],
			['name', 'required'],
			['name', 'string', 'max' => 255],

			['title', 'filter', 'filter' => 'trim'],
			['title', 'string', 'max' => 255],

            ['teaser', 'safe'],

            ['body', 'safe'],

            ['isShowPrice', 'boolean'],
            ['isShowPrice', 'default', 'value' => true],

            ['status', 'in', 'range' => $this->statusesOptions()],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],

            [['createdAt', 'updatedAt'], 'safe'],
		];
	}

	public function attributeLabels()
	{
		return [
            'id' => Yii::t('yiicom', 'ID'),
		    'parentId' => 'Родительская категория',
			'name' => 'Имя категории',
            'title' => Yii::t('yiicom', 'Page title H1'),
            'teaser' => Yii::t('yiicom', 'Teaser'),
            'body' => Yii::t('yiicom', 'Content'),
            'isShowPrice' => 'Показывать цены',
            'status' => Yii::t('yiicom', 'Status'),
            'createdAt' => Yii::t('yiicom', 'Created At'),
            'updatedAt' => Yii::t('yiicom', 'Updated At'),
		];
	}

	public function behaviors()
	{
		return array_merge(parent::behaviors(),  [
			'NestedSetsBehavior' => [
				'class' => NestedSetsBehavior::class,
				'leftAttribute' => 'left',
				'rightAttribute' => 'right',
				'depthAttribute' => 'level',
			],
            'Timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
            ],
            'PageUrlBehavior' => [
                'class' => PageUrlBehavior::class,
            ],
            'FilesBehavior' => [
                'class' => FilesBehavior::class,
            ]
		]);
	}

    /**
     * @inheritDoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return string
     */
    public function route()
    {
        return 'catalog/category/view';
    }

    /**
     * @return array
     */
    public function relations()
    {
        return [
            'PageUrl' => [
                'class' => PageUrl::class,
                'attribute' => 'url',
            ],
            'Files' => [
                'class' => File::class,
                'attribute' => 'files',
                'multiple' => true,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id',
            'parentId',
            'name',
            'title',
            'teaser',
            'body',
            'status',
            'url',
            'files'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::class, ['id' => 'parentId']);
    }

    /**
     * @param boolean $withRoot
     * @return Category
     */
//	public function getParent($withRoot = false)
//	{
//	    $withRoot = false;
//	    /* @var $query ActiveQuery */
//		$query = $this->parents(1);
//
//
//
//        if ($withRoot === false) {
//            $query->andWhere('level > 0');
//        }
//        echo '<pre>'; print_r($query->createCommand()->rawSql);echo '</pre>';
////        echo '<pre>'; print_r($query->createCommand()->rawSql);echo '</pre>';
//
//        return $query->one();
//	}
//




    /**
     * @return A
     */
//	public function getParents()
//	{
//	    exit('123123');
//		return $this->parents()->andWhere('level > 0')->all();
//	}

//	public function getProducts()
//	{
//		return $this->hasMany(Product::className(), ['id' => 'productId'])
//			->viaTable('product_category', ['categoryId' => 'id']);
//	}
//
//    public function getProductCategory()
//	{
//		return $this->hasOne(ProductCategory::className(), ['categoryId' => 'id']);
//	}

	/**
     * Return current and all children category ids
     * @return array
     */
//    public function getChildIds(bool $withCurrent = false) {
//        $children = $this->children()->all();
//
//        if (! $children) {
//            return [];
//        }
//
//        $ids[$this->id] = $this->id;
//
//        foreach ($this->childrens as $child) {
//            $ids[$child->id] = $child->id;
//        }
//
//        return $ids;
//    }
//
//
//
//	public function getChildrens()
//	{
//		return $this->children()->all();
//	}
//
//
//	public function getMainChildrens()
//	{
//		return $this->children()->andWhere('level = 1')->all();
//	}
//
//
//	/**
//	 * Возвращает все вложенные категории основной родительской категории
//	 * @return array
//	 */
//	public function getMainChilds()
//	{
//		if($this->parent) {
//			return $this->parent->childrens;
//		} else {
//			return $this->childrens;
//		}
//	}
//
//

//
//
//	public function getLeaves()
//	{
//		return $this->find()->leaves()->all();
//	}
//
//	/**
//	 * Возвращает количество товаров в категорие и ее потомках
//	 * @return integer
//	 */
//	public function getProductsCount()
//	{
//		$ids = $this->childrensIds;
//		array_unshift($ids, $this->id);
//
//		return Product::find()
////                ->addSelect()
//			->joinWith(['categories'])
//			->where(['category.id' => $ids])
//			->groupBy('product.id')
//			->count();
//	}
//

//    public static function getAllArray()
//	{
//		return ArrayHelper::map(Category::find()->all(), 'id', 'name');
//
//	}


}