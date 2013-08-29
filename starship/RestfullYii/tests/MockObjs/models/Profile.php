<?php
/**
 * This is the model class for table "tbl_profile".
 *
 * @property int $owner_id
 * @property string $photo
 * @property string $website
 *
 * The followings are the available model relations:
 * @property User $owner
 */
class Profile extends \CActiveRecord
{
	public static $configurationType='normal';
	public $disableOwnerRule=false;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Profile the static model class
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
		return 'tbl_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$rules = array();
		if (!$this->disableOwnerRule) {
			$rules[] = array('user_id', 'required');
		}
		$rules[] = array('photo, website', 'safe');
		return $rules;
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
					'owner' => array(self::BELONGS_TO, 'User', 'user_id'),
				);
			case 'fkarray':
				return array(
					'owner' => array(self::BELONGS_TO, 'User', array('user_id'=>'id')),
				);
		}
	}
}
