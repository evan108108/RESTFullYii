<?php
/**
 * This is the model class for table "tbl_Binary".
 *
 * @property int $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Post[] $posts
 */
class Binary extends \CActiveRecord
{
	public $broken = false;
	public static $configurationType='normal';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Binary the static model class
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
		return 'tbl_binary';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, id', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return [];
	}
}

