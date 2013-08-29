<?php
/**
 * This is the model class for table "tbl_post".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $create_time
 * @property int $author_id
 *
 * The followings are the available model relations:
 * @property User $author
 * @property Category[] $categories
 */
class Post extends \CActiveRecord
{
	public static $configurationType='normal';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post the static model class
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
		return 'tbl_post';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('title', 'length', 'max'=>255),
			array('title, content, create_time, author_id, id', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		switch(static::$configurationType)
		{
			default:
				return array(
					'categories' => array(self::MANY_MANY, 'Category', 'tbl_post_category(post_id, category_id)'),
					'author' => array(self::BELONGS_TO, 'User', 'author_id'),
				);
			case 'fkarray':
				return array(
					'categories' => array(self::MANY_MANY, 'Category', 'tbl_post_category(post_id, category_id)'),
					'author' => array(self::BELONGS_TO, 'User', array('author_id'=>'id')),
				);
		}
	}
}
