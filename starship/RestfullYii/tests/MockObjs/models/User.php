<?php
/**
 * This is the model class for table "tbl_user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 *
 * The followings are the available model relations:
 * @property Profile $profile
 * @property Post[] $posts
 */
class User extends \CActiveRecord
{
	public static $configurationType='normal';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'tbl_user';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
//			array('id', 'required'),
			array('username, password, email', 'safe'),
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
					'posts' => array(self::HAS_MANY, 'Post', 'author_id'),
					'profile' => array(self::HAS_ONE, 'Profile', 'user_id'),
				);
			case 'fkarray':
				return array(
					'posts' => array(self::HAS_MANY, 'Post', array('id'=>'author_id')),
					'profile' => array(self::HAS_ONE, 'Profile', array('id'=>'user_id')),
				);
		}
	}
}
