<?php
/**
 * EActiveRecordRelationBehavior unit tests
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @link http://yiiext.github.com/extensions/activerecord-relation-behavior/index.html
 * @copyright Copyright &copy; 2012 Carsten Brandt
 * @license https://github.com/yiiext/activerecord-relation-behavior/blob/master/LICENSE#L1
 *
 * How to run this test
 * --------------------
 *
 * 1. make sure yii framework is available under ./yii/framework
 *    you can do this by
 *    - cloning the yii git repo with `git clone https://github.com/yiisoft/yii.git yii`
 *    - or linking existing yii directory here with `ln -s ../../path/to/yii yii`
 *
 * 2. make sure you have phpunit installed and available in PATH (http://www.phpunit.de/manual/3.6/en/installation.html)
 *
 * 3. run `phpunit --colors EActiveRecordRelationBehaviorTest.php` or if you want coverage information in html,
 *    run `phpunit --coverage-html tmp/coverage --colors EActiveRecordRelationBehaviorTest.php`
 */

namespace yiiext\behaviors\activeRecordRelation\tests;
define('TEST_NAMESPACE', 'yiiext\behaviors\activeRecordRelation\tests');

if (!defined('YII_PATH')) {
	$yii = dirname(__FILE__).'/yii/framework/yiit.php';
	require_once($yii);
}

require_once(dirname(__FILE__).'/EActiveRecordRelationBehavior.php');

/**
 * unit test class for EActiveRecordRelationBehavior
 *
 * these things are not covered and should be kept in mind while developing:
 *
 * - make sure it works with any custom db connection
 *   therefor only use $this->owner->dbConnection
 * - make sure it works with any possible configuration that works for yii
 *
 * these things should be added in test
 * @todo make sure it works with and without table prefix
 * @todo make sure it works with and without defined primary keys
 * @todo make sure 'through' relations are not touched until they are supported
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package yiiext.behaviors.activeRecordRelation.tests
 */
class EActiveRecordRelationBehaviorTest extends \CTestCase
{
	public $dbFile;
	/** @var EActiveRecordRelationBehaviorTestMigration */
	protected $migration;

	/**
	 * set up environment with yii application and migrate up db
	 */
	public function setUp()
	{
		$basePath=dirname(__FILE__).'/tmp';
		if (!file_exists($basePath))
			mkdir($basePath, 0777, true);
		if (!file_exists($basePath.'/runtime'))
			mkdir($basePath.'/runtime', 0777, true);

		// create webapp
		if (\Yii::app()===null) {
			\Yii::createWebApplication(array(
			    'basePath'=>$basePath,
			));
		}
		\CActiveRecord::$db=null;

		if (!isset($_ENV['DB']) || $_ENV['DB'] == 'sqlite')
		{
			if (!$this->dbFile)
				$this->dbFile = $basePath.'/test.'.uniqid(time()).'.db';
			\Yii::app()->setComponent('db', new \CDbConnection('sqlite:'.$this->dbFile));
		}
		elseif ($_ENV['DB'] == 'mysql')
			\Yii::app()->setComponent('db', new \CDbConnection('mysql:dbname=test;host=localhost', 'root'));
		elseif ($_ENV['DB'] == 'pgsql')
			\Yii::app()->setComponent('db', new \CDbConnection('pqsql:dbname=test;host=localhost', 'postgres'));
		else
			throw new \Exception('Unknown db. Only sqlite, mysql and pgsql are valid.');

		// create db
		$this->migration = new EActiveRecordRelationBehaviorTestMigration();
		$this->migration->dbConnection = \Yii::app()->db;
		$this->migration->up();

	}

	/**
	 * migrate down db when test succeeds
	 */
	public function tearDown()
	{
		if (!$this->hasFailed() && $this->migration && $this->dbFile) {
			$this->migration->down();
			unlink($this->dbFile);
		}
	}

	/**
	 * dataprovider that lists possible configuration options for foreign keys, so
	 * we make sure to have our models configured in many ways and it still works
	 * @return array (configType, transactional)
	 */
	public function fkConfigurationProvider()
	{
		$configOptions = array(
			'normal', // all fks configured as string
//			'fkarray', // will be supported when support for composite pks is added
//			'fkcomma', // will be used when composite pks are supported
		);

		// @todo mix them!
		$configs = array();
		foreach($configOptions as $option) {
			$configs[] = array(array('Profile'=>$option, 'User'=>$option, 'Post'=>$option, 'Category'=>$option), true);
			$configs[] = array(array('Profile'=>$option, 'User'=>$option, 'Post'=>$option, 'Category'=>$option), false);
		}
		return $configs;
	}

	protected function setConfig($config)
	{
		Profile::$configurationType = $config['Profile'];
		User::$configurationType = $config['User'];
		Post::$configurationType = $config['Post'];
		Category::$configurationType = $config['Category'];
	}

	protected function startTransaction($t)
	{
		$this->assertNull(\Yii::app()->db->currentTransaction, 'there shouldn\'t be a transaction at start');
		if ($t) {
			\Yii::app()->db->beginTransaction();
			$this->assertNotNull(\Yii::app()->db->currentTransaction, 'there should be a transaction after beginTransaction');
		}
	}

	protected function endTransaction($t)
	{
		if ($t) {
			$this->assertNotNull(\Yii::app()->db->currentTransaction, 'there should be a transaction, we created one at start');
			\Yii::app()->db->currentTransaction->commit();
			$this->assertNull(\Yii::app()->db->currentTransaction, 'there shouldn\'t be a transaction after commit');
		} else {
			$this->assertNull(\Yii::app()->db->currentTransaction, 'there shouldn\'t be a transaction anymore, behavior should have committed or rolledBack');
		}
	}

	/**
	 * test creation of AR and assigning a relation with BELONGS_TO
	 *
	 * @dataProvider fkConfigurationProvider
	 */
	public function testBelongsTo($config, $transactional)
	{
		$this->setConfig($config);
		$this->startTransaction($transactional);

		$john = $this->getJohn();
		$p = new Post();
		$p->title = 'hi testing!';
		$p->author = $john;

		// saving with a related record that is new should fail
		$exception=false;
		try {
			$p->save();
		} catch (\CDbException $e) {
			$exception=true;
		}
		$this->assertTrue($exception, 'Expected CDbException on saving with a non saved record.');

		$this->assertSaveSuccess($p->author); // saved john
		$this->assertSaveSuccess($p);

		$this->assertEquals($p->author_id, $john->id);
		$p->refresh();
		$this->assertEquals($p->author_id, $john->id);
		$this->assertNotNull($p->author);
		$this->assertEquals($p->author->id, $john->id);

		$jane = $this->getJane(10, true);
		$p = new Post();
		$p->title = 'hi testing2!';
		$p->author = $jane->id;

		$this->assertSaveSuccess($p);

		$this->assertEquals($p->author_id, $jane->id);
		$p->refresh();
		$this->assertEquals($p->author_id, $jane->id);
		$this->assertNotNull($p->author);
		$this->assertEquals($p->author->id, $jane->id);

		$this->endTransaction($transactional);
	}

	/**
	 * test creation of AR and assigning a relation with HAS_ONE
	 * this also tests the HAS_ONE opposite BELONGS_TO
	 *
	 * @dataProvider fkConfigurationProvider
	 */
	public function testHasOne($config, $transactional)
	{
		$this->setConfig($config);
		$this->startTransaction($transactional);

		// check if the normal thing works
		$john = $this->getJohn();
		$this->assertSaveSuccess($john);

		// create a jane to make sure her relation does not change
		$jane = $this->getJane();
		$this->assertSaveSuccess($jane);

		$this->assertNull($john->profile);
		$this->assertEquals(array(), $john->posts);
		$this->assertEquals($this->getJohn(1)->attributes, $john->attributes);

		$john->refresh();

		$this->assertNull($john->profile);
		$this->assertEquals(array(), $john->posts);
		$this->assertEquals($this->getJohn(1)->attributes, $john->attributes);

		$john->profile = new Profile();
		$this->assertNotNull($john->profile);
		$this->assertEquals(array(), $john->posts);
		$this->assertEquals($this->getJohn(1)->attributes, $john->attributes);

		$john->profile->website = 'http://www.example.com/';
		$this->assertEquals('http://www.example.com/', $john->profile->website);

		// saving with a related record that is new should fail
		$exception=false;
		try {
			$john->save();
		} catch (\CDbException $e) {
			$exception=true;
		}
		$this->assertTrue($exception, 'Expected CDbException on saving with a non saved record.');

		$this->assertSaveFailure($john->profile, array('owner_id')); // owner_id is required and not set by EActiveRecordRelationBehavior
		$john->profile->owner = $john;
		$this->assertSaveSuccess($john->profile);

		// after profile is saved, john should be saved
		$this->assertSaveSuccess($john);
		$this->assertNotNull($john->profile);

		/** @var Profile $profile */
		$this->assertNotNull($profile=Profile::model()->findByPk($john->profile->owner_id));
		/** @var User $user */
		$this->assertNotNull($user=User::model()->findByPk($john->id));

		$this->assertEquals($profile, $user->profile);
		$this->assertEquals($user, $profile->owner);

		$this->assertNull($jane->profile);
		$jane->refresh();
		$this->assertNull($jane->profile);

		// this can not work since pk of profile can not be null
		// @todo should be supported
		/*$p = $john->profile;
		$this->assertNotNull($john->profile);
		$john->profile = null;
		$this->assertNull($john->profile);
		$this->assertSaveSuccess($john);
		$this->assertNull($john->profile);
		$john->refresh();
		$this->assertNull($john->profile);
		$p->refresh();*/

		$this->endTransaction($transactional);
	}

	/**
	 * test creation of AR and assigning a relation with HAS_MANY as pk values
	 * one record is added as object later
	 *
	 * @dataProvider fkConfigurationProvider
	 */
	public function testHasManyPk($config, $transactional)
	{
		$this->setConfig($config);
		$this->startTransaction($transactional);

		$author = $this->getJohn();
		$this->assertSaveSuccess($author);

		$this->assertEquals(array(), $author->posts);
		$posts = $this->getPosts(10);
		for($n=1;$n<10;$n++) {
			$posts[$n] = $posts[$n]->id;
		}
		$author->posts = $posts;
		$this->assertEquals(9, count($author->posts));
		$this->assertSaveSuccess($author);

		$this->assertEquals(9, count($author->posts));
		$author->refresh();
		$this->assertEquals(9, count($author->posts));

		// remove some records
		unset($posts[1]);
		unset($posts[3]);

		$author->posts = $posts;
		$this->assertEquals(7, count($author->posts));
		$this->assertSaveSuccess($author);

		$this->assertEquals(7, count($author->posts));
		$author->refresh();
		$this->assertEquals(7, count($author->posts));

		// remove some, add some records
		unset($posts[4]);
		unset($posts[5]);
		$p = new Post();
		$p->title = 'testtitle';
		$this->assertSaveSuccess($p);
		$posts[] = $p; // this one is mixed

		$author->posts = $posts;
		$this->assertEquals(6, count($author->posts));
		$this->assertSaveSuccess($author);

		$this->assertEquals(6, count($author->posts));
		$author->refresh();
		$this->assertEquals(6, count($author->posts));

		// remove all records
		$author->posts = array();
		$this->assertEquals(0, count($author->posts));
		$this->assertSaveSuccess($author);

		$this->assertEquals(0, count($author->posts));
		$author->refresh();
		$this->assertEquals(0, count($author->posts));

		$this->endTransaction($transactional);
	}

	/**
	 * test creation of AR and assigning a relation with HAS_MANY as objects values
	 * one record is added as pk later
	 *
	 * @dataProvider fkConfigurationProvider
	 */
	public function testHasManyObject($config, $transactional)
	{
		$this->setConfig($config);
		$this->startTransaction($transactional);

		$author = $this->getJohn();
		$this->assertSaveSuccess($author);

		$this->assertEquals(array(), $author->posts);
		$posts = $this->getPosts();
		$author->posts = $posts;
		$this->assertEquals(9, count($author->posts));
		// saving with a related record that is new should fail
		$exception=false;
		try {
			$author->save();
		} catch (\CDbException $e) {
			$exception=true;
		}
		$this->assertTrue($exception, 'Expected CDbException on saving with a non saved record.');
		// saving last record
		$this->assertSaveSuccess(end($posts));
		$this->assertEquals(9, count($author->posts));
		$this->assertSaveSuccess($author);

		$this->assertEquals(9, count($author->posts));
		$author->refresh();
		$this->assertEquals(9, count($author->posts));

		// remove some records
		unset($posts[1]);
		unset($posts[3]);

		$author->posts = $posts;
		$this->assertEquals(7, count($author->posts));
		$this->assertSaveSuccess($author);

		$this->assertEquals(7, count($author->posts));
		$author->refresh();
		$this->assertEquals(7, count($author->posts));

		// remove some, add some records
		unset($posts[4]);
		unset($posts[5]);
		$p = new Post();
		$p->title = 'testtitle';
		$this->assertSaveSuccess($p);
		$posts[] = $p->id; // this one is mixed

		$author->posts = $posts;
		$this->assertEquals(6, count($author->posts));
		$this->assertSaveSuccess($author);

		$this->assertEquals(6, count($author->posts));
		$author->refresh();
		$this->assertEquals(6, count($author->posts));

		// remove all records
		$author->posts = array();
		$this->assertEquals(0, count($author->posts));
		$this->assertSaveSuccess($author);

		$this->assertEquals(0, count($author->posts));
		$author->refresh();
		$this->assertEquals(0, count($author->posts));

		$this->endTransaction($transactional);
	}

	/**
	 * @return array
	 */
	protected function beforeManyMany()
	{
		$untouchedCategory1 = new Category();
		$untouchedCategory1->name = 'untouched1';
		$this->assertEquals(0, count($untouchedCategory1->posts));
		$this->assertSaveSuccess($untouchedCategory1);
		$this->assertEquals(0, count($untouchedCategory1->posts));

		$untouchedCategory2 = new Category();
		$untouchedCategory2->name = 'untouched2';
		$untouchedCategory2->posts = $this->getPosts(10, true);
		$this->assertEquals(9, count($untouchedCategory2->posts));
		$this->assertSaveSuccess($untouchedCategory2);
		$this->assertEquals(9, count($untouchedCategory2->posts));

		return array($untouchedCategory1, $untouchedCategory2);
	}

	/**
	 * @param Category $untouchedCategory1
	 * @param Category $untouchedCategory2
	 */
	protected function afterManyMany($untouchedCategory1, $untouchedCategory2)
	{
		$this->assertEquals('untouched1', $untouchedCategory1->name);
		$this->assertEquals(0, count($untouchedCategory1->posts));
		$untouchedCategory1->refresh();
		$this->assertEquals('untouched1', $untouchedCategory1->name);
		$this->assertEquals(0, count($untouchedCategory1->posts));

		$this->assertEquals('untouched2', $untouchedCategory2->name);
		$this->assertEquals(9, count($untouchedCategory2->posts));
		$untouchedCategory2->refresh();
		$this->assertEquals('untouched2', $untouchedCategory2->name);
		$this->assertEquals(9, count($untouchedCategory2->posts));
	}

	public function manymanyData()
	{
		$data = array(
			array(true, 1), // only pks
			array(true, 2), // mixed
			array(false, 1), // only model objects
		);

		// mix in data from @dataProvider fkConfigurationProvider
		$return = array();
		foreach($this->fkConfigurationProvider() as $row) {
			foreach($data as $dataRow) {
				$return[] = array_merge($dataRow, $row);
			}
		}
		return $return;
	}

	/**
	 * test creation of AR and assigning a relation with MANY_MANY as pk values
	 *
	 * @dataProvider manymanyData
	 */
	public function testManyMany($postsAsPk, $modulo, $config, $transactional)
	{
		$this->setConfig($config);
		$this->startTransaction($transactional);

		if ($postsAsPk) { // first with pk data
			$posts=$this->getPosts(10, true);
			for($n=1;$n<10;$n++) {
				if ($n % $modulo == 0) {
					$posts[$n] = $posts[$n]->id;
				}
			}
		} else {
			$posts = $this->getPosts(10, true); // second with object data
		}

		list($un1, $un2) = $this->beforeManyMany();

		// begin real test
		$category = new Category();
		$category->name = 'my new cat';
		$this->assertEquals(array(), $category->posts);
		$category->save();
		$this->assertEquals(array(), $category->posts);

		$category->save();

		$this->assertEquals(0, count($category->posts));
		$category->posts = $posts;
		$this->assertEquals(9, count($category->posts));
		$category->save();
		$this->assertEquals(9, count($category->posts));

		// remove some records
		unset($posts[1]);
		unset($posts[3]);

		$this->assertEquals(9, count($category->posts));
		$category->posts = $posts;
		$this->assertEquals(7, count($category->posts));
		$category->save();
		$this->assertEquals(7, count($category->posts));

		// remove some, add some records
		unset($posts[4]);
		unset($posts[5]);
		$p = new Post();
		$p->title = 'testtitle';
		$this->assertSaveSuccess($p);
		$posts[] = $p->id; // this one is mixed

		$this->assertEquals(7, count($category->posts));
		$category->posts = $posts;
		$this->assertEquals(6, count($category->posts));
		$category->save();
		$this->assertEquals(6, count($category->posts));


		// @todo test if additional relation data is touched

		// end real test, checking untouched
		$this->afterManyMany($un1, $un2);
		$this->endTransaction($transactional);
	}

	/**
	 * @expectedException CDbException
	 * @dataProvider fkConfigurationProvider
	 */
	public function testManyManyException($config, $transactional)
	{
		$this->setConfig($config);
		$this->startTransaction($transactional);

		$cat = new Category();
		$cat->posts = 1;
		$cat->save();

		$this->endTransaction($transactional);
	}

	/**
	 * @expectedException CDbException
	 * @dataProvider fkConfigurationProvider
	 */
	public function testYiiException1($config, $transactional)
	{
		$this->setConfig($config);
		$this->startTransaction($transactional);

		$cat = new Category();
		$cat->broken = true;
		$cat->posts = array();
		$cat->save();

		$this->endTransaction($transactional);
	}

	/**
	 * @expectedException CDbException
	 * @dataProvider fkConfigurationProvider
	 */
	public function testYiiException2($config, $transactional)
	{
		$this->setConfig($config);
		$this->startTransaction($transactional);

		$cat = new Category();
		$this->migration->dropRelationTable();
		$cat->posts = array();
		$cat->save();

		$this->endTransaction($transactional);
	}

	public function testValidationBeforeSave()
	{
		$model = new Profile();
		$model->disableOwnerRule = true;
		$model->owner = new User();
		$this->assertTrue($model->validate());
	}

	/**
	 * @expectedException CDbException
	 */
	public function testValidationBeforeSaveFail()
	{
		$model = new Profile();
		$model->owner = new User();
		$this->assertTrue($model->validate());
	}

	/**
	 * @param \CActiveRecord $ar
	 */
	public function assertSaveSuccess($ar)
	{
		$this->assertTrue($ar->save(), 'Expected saving of AR '.get_class($ar).' without errors: '.print_r($ar->getErrors(),true));
	}

	/**
	 * @param \CActiveRecord $ar
	 */
	public function assertSaveFailure($ar, $failedAttributes=null)
	{
		$this->assertFalse($ar->save(), 'Expected saving of AR '.get_class($ar).' to fail.');
		if ($failedAttributes!==null)
			$this->assertEquals($failedAttributes, array_keys($ar->getErrors()));
	}

	public static function assertEquals($expected, $actual, $message = '', $delta = 0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
	{
		if ($expected instanceof \CActiveRecord) {
			/** @var \CActiveRecord $expected */
			self::assertNotNull($actual, 'Failed asserting that two ActiveRecords are equal. Second is null. '.$message);
			self::assertTrue($expected->equals($actual), 'Failed asserting that two ActiveRecords are equal. '.$message);
		} elseif ($actual instanceof \CActiveRecord) {
			/** @var \CActiveRecord $actual */
			self::assertNotNull($expected, 'Failed asserting that two ActiveRecords are equal. First is null. '.$message);
			self::assertTrue($actual->equals($expected), 'Failed asserting that two ActiveRecords are equal. '.$message);
		} else {
			parent::assertEquals($expected, $actual, $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
		}
	}

	/**
	 * @return User
	 */
	protected function getJohn($id=null, $save=false)
	{
		$john = new User();
		if ($id)
			$john->id = $id;
		$john->username = 'John';
		$john->password = '123456';
		$john->email = 'john@doe.com';
		if ($save)
			$this->assertSaveSuccess($john);
		return $john;
	}

	/**
	 * @return User
	 */
	protected function getJane($id=null, $save=false)
	{
		$jane = new User();
		if ($id)
			$jane->id = $id;
		$jane->username = 'Jane';
		$jane->password = '654321';
		$jane->email = 'jane@doe.com';
		if ($save)
			$this->assertSaveSuccess($jane);
		return $jane;
	}

	protected function getPosts($saveUntil=9, $addAuthor=false)
	{
		$posts = array();
		for($n=1;$n<10;$n++) {
			$p = new Post();
			$p->title = 'title'.$n;
			$p->content = 'content'.$n;
			if ($n < $saveUntil) {
				if ($addAuthor) {
					$p->author = ($n % 2 == 0) ? $this->getJane(null, true) : $this->getJohn(null, true);
				}
				$this->assertSaveSuccess($p);
			}
			$posts[$n] = $p;
		}
		return $posts;
	}

}

class EActiveRecordRelationBehaviorTestMigration extends \CDbMigration
{
	private $relationTableDropped=true;
	public function up()
	{
		ob_start();
		// these are the tables from yii definitive guide
		// http://www.yiiframework.com/doc/guide/1.1/en/database.arr
		$this->createTable('tbl_user', array(
             'id'=>'pk',
             'username'=>'string',
             'password'=>'string',
             'email'=>'string',
		));
		$this->createTable('tbl_profile', array(
             'owner_id'=>'pk',
             'photo'=>'binary',
             'website'=>'string',
		     'FOREIGN KEY (owner_id) REFERENCES tbl_user(id)',
		));
		$this->createTable('tbl_post', array(
             'id'=>'pk',
             'title'=>'string',
             'content'=>'text',
             'create_time'=>'timestamp',
             'author_id'=>'integer',
             'FOREIGN KEY (author_id) REFERENCES tbl_user(id)',
		));
		$this->createTable('tbl_post_category', array(
             'post_id'=>'integer',
             'category_id'=>'integer',
             'post_order'=>'integer', // added this row to test additional attributes added to a relation
		     'PRIMARY KEY(post_id, category_id)',
		     'FOREIGN KEY (post_id) REFERENCES tbl_post(id)',
		     'FOREIGN KEY (category_id) REFERENCES tbl_category(id)',
		));
		$this->relationTableDropped=false;
		$this->createTable('tbl_category', array(
             'id'=>'pk',
             'name'=>'string',
		));
		ob_end_clean();
	}

	public function down()
	{
		ob_start();
		if (!$this->relationTableDropped) {
			$this->dropTable('tbl_post_category');
		}
		$this->dropTable('tbl_category');
		$this->dropTable('tbl_post');
		$this->dropTable('tbl_profile');
		$this->dropTable('tbl_user');
		ob_end_clean();
	}

	public function dropRelationTable()
	{
		ob_start();
		$this->dropTable('tbl_post_category');
		$this->relationTableDropped=true;
		ob_end_clean();
	}
}

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

	/*
	 * @return array the behavior configurations (behavior name=>behavior configuration)
	 */
	public function behaviors()
	{
		return array('activeRecordRelationBehavior'=>'EActiveRecordRelationBehavior');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$rules = array();
		if (!$this->disableOwnerRule) {
			$rules[] = array('owner_id', 'required');
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
					'owner' => array(self::BELONGS_TO, TEST_NAMESPACE.'\User', 'owner_id'),
				);
			case 'fkarray':
				return array(
					'owner' => array(self::BELONGS_TO, TEST_NAMESPACE.'\User', array('owner_id'=>'id')),
				);
		}
	}
}

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

	/*
	 * @return array the behavior configurations (behavior name=>behavior configuration)
	 */
	public function behaviors()
	{
		return array('activeRecordRelationBehavior'=>'EActiveRecordRelationBehavior');
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
					'posts' => array(self::HAS_MANY, TEST_NAMESPACE.'\Post', 'author_id'),
					'profile' => array(self::HAS_ONE, TEST_NAMESPACE.'\Profile', 'owner_id'),
				);
			case 'fkarray':
				return array(
					'posts' => array(self::HAS_MANY, TEST_NAMESPACE.'\Post', array('id'=>'author_id')),
					'profile' => array(self::HAS_ONE, TEST_NAMESPACE.'\Profile', array('id'=>'owner_id')),
				);
		}
	}
}

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

	/*
	 * @return array the behavior configurations (behavior name=>behavior configuration)
	 */
	public function behaviors()
	{
		return array('activeRecordRelationBehavior'=>'EActiveRecordRelationBehavior');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
//			array('id', 'required'),
			array('title, content, create_time, author_id', 'safe'),
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
					'categories' => array(self::MANY_MANY, TEST_NAMESPACE.'\Category', 'tbl_post_category(post_id, category_id)'),
					'author' => array(self::BELONGS_TO, TEST_NAMESPACE.'\User', 'author_id'),
				);
			case 'fkarray':
				return array(
					'categories' => array(self::MANY_MANY, TEST_NAMESPACE.'\Category', 'tbl_post_category(post_id, category_id)'),
					'author' => array(self::BELONGS_TO, TEST_NAMESPACE.'\User', array('author_id'=>'id')),
				);
		}
	}
}

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

	/*
	 * @return array the behavior configurations (behavior name=>behavior configuration)
	 */
	public function behaviors()
	{
		return array('activeRecordRelationBehavior'=>'EActiveRecordRelationBehavior');
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
						'posts' => array(self::MANY_MANY, TEST_NAMESPACE.'\Post', 'tbl_post_category(category_id, post_id)'),
					);
			}
		}
		return array(
			'posts' => array(self::MANY_MANY, TEST_NAMESPACE.'\Post', 'tbl_post_category'),
		);
	}
}
