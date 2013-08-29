<?php
/**
 * This is the model class for table "tbl_category".
 *
 * @property int $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Post[] $posts
 */
class Category extends \CActiveRecord
{
	public $broken = false;
	public static $configurationType='normal';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Category the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_category';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
//			array('id', 'required'),
			array('name', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		if (!$this->broken) {
			switch(static::$configurationType)
			{
				default:
					return array(
						'posts' => array(self::MANY_MANY, 'Post', 'tbl_post_category(category_id, post_id)'),
					);
			}
		}
		return array(
			'posts' => array(self::MANY_MANY, 'Post', 'tbl_post_category'),
		);
	}
}
