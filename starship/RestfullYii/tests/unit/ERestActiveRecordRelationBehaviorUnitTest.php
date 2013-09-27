<?php
Yii::import('RestfullYii.ARBehaviors.ERestActiveRecordRelationBehavior');

/**
 * ERestActiveRecordRelationBehaviorUnitTest
 *
 * Tests ERestActiveRecordRelationBehavior
 *
 * @category   PHP
 * @package    Starship
 * @subpackage Restfullyii/Tests
 * @copyright  Copyright (c) 2013 Evan Frohlich (https://github.com/evan108108)
 * @license    https://github.com/evan108108   OSS
 * @version    Release: 1.2.0
 */
class ERestActiveRecordRelationBehaviorUnitTest extends ERestTestCase
{
	public $ERestActiveRecordRelationBehavior;

	public function setUp()
	{
		parent::setUp();
		$this->ERestActiveRecordRelationBehavior = new ERestActiveRecordRelationBehavior();
	}
	/**
	 * events
	 *
	 * test ERestActiveRecordRelationBehavior->events()
	 */ 
	public function testEvents()
	{
		$this->assertArraysEqual(
			$this->ERestActiveRecordRelationBehavior->events(),
			[
				'onBeforeSave'=>'beforeSave',
				'onAfterSave'=>'afterSave',
			]
		);
	}

	/**
	 * beforeSave
	 *
	 * test ERestActiveRecordRelationBehavior->beforeSave()
	 * This will be tested implicitly many times
	 * test here is only for existence
	 */ 
	public function beforeSave()
	{
		$this->assertTrue(method_exists($this->ERestActiveRecordRelationBehavior, 'beforeSave'));
	}

	/**
	 * testPreSaveHelper
	 *
	 * tests ERestActiveRecordRelationBehavior->preSaveHelper()
	 */
	public function testPreSaveHelper()
	{
		$user = User::model()->findByPk(1);
		$user_data = $this->getUserData();
		$user->posts = $user_data['posts'];
		$user->profile = $user_data['profile'];
		$this->ERestActiveRecordRelationBehavior->preSaveHelper($user);
		
		$this->assertTrue(is_object($user->profile));
		$this->assertInstanceOf('Profile', $user->profile);
		$this->assertArraysEqual($user_data['profile'], $user->profile->attributes);

		foreach($user->posts as $post) {
			$this->assertInstanceOf('Post', $post);
		}
		$this->assertEquals($user->posts[0]->attributes, $user_data['posts'][0]);

		$this->assertTrue(is_numeric($user->posts[1]->id));
		$this->assertTrue($user->posts[1]->id > $user->posts[0]->id);
		$this->assertEquals($user->posts[1]->author_id, 1);
		$this->assertEquals($user->posts[1]->content, "NEW CONTENT");
		$this->assertEquals($user->posts[1]->create_time, "2013-08-07 10:09:42");
		$this->assertEquals($user->posts[1]->title, "NEW TITLE");		

		$post = Post::model()->findByPk(1);
		$post_data = $this->getPostData();
		$post->attributes = $post_data;
		$post->author = $post_data['author'];
		$post->author_id = null;
		$post->categories = $post_data['categories'];
		$this->ERestActiveRecordRelationBehavior->preSaveHelper($post);
		$this->assertEquals(1, $post->author_id);
		$this->assertInstanceOf('User', $post->author);
		$this->assertArraysEqual($post->author->attributes, $post_data['author']);
		foreach($post->categories as $cat) {
			$this->assertInstanceOf('Category', $cat);
		}
		$this->assertArraysEqual($post->categories[0]->attributes, $post_data['categories'][0]);
		$this->assertArraysEqual($post->categories[1]->attributes, $post_data['categories'][1]);
	}
	
	/**
	 * getRelationType
	 *
	 * tests ERestActiveRecordRelationBehavior->getRelationType()
	 */ 
	public function testGetRelationType()
	{
		$user = User::model()->with('posts')->findByPk(1);
		$result = $this->invokePrivateMethod($this->ERestActiveRecordRelationBehavior, 'getRelationType', [$user, 'posts']);
		$this->assertEquals('CHasManyRelation', $result);

		$post = Post::model()->with('author')->findByPk(1);
		$result = $this->invokePrivateMethod($this->ERestActiveRecordRelationBehavior, 'getRelationType', [$post, 'author']);
		$this->assertEquals('CBelongsToRelation', $result);

		$result = $this->invokePrivateMethod($this->ERestActiveRecordRelationBehavior, 'getRelationType', [$post, 'RELATION_THAT_DOES_NOT_EXIST']);
		$this->assertEquals(false, $result);
	}

	protected function getCategoryData()
	{
		return [
			"id" => 1,
			"name" => "cat1",
			"posts" => [
				[
					"id" => 1,
					"title" => "title1",
					"content" => "content1",
					"create_time" => "2013-08-07 10:09:41",
					"author_id" => 1,
				]
			]
		];
	}

	protected function getPostData()
	{
		return [
			"id" => "1",
			"title" => "title1",
			"content" => "content1",
			"create_time" => "2013-08-07 10:09:41",
			"author_id" => 1,
			"categories" => [
				[
					"id" => 1,
					"name" => "cat1",
				],
				[
					"id" => 2,
					"name" => "cat2"
				],

			],
			"author" => [
				"id" => 1,
				"username" => "username1",
				"password" => "password1",
				"email" => "email@email1.com",
			],
		];
	}

	protected function getUserData()
	{
		return [
			"email" => "email@email1.com",
			"id" => 1,
			"password" => "password1",
			"posts" => [
					[	
							"author_id" => 1,
							"content" => "content1",
							"create_time" => "2013-08-07 10:09:41",
							"id" => 1,
							"title" => "title1"
					], [
							"content" => "NEW CONTENT",
							"create_time" => "2013-08-07 10:09:42",
							"title" => "NEW TITLE"
					]
			],
			"profile" => [
					"id" => 1,
					"photo" => "1",
					"user_id" => '1',
					"website" => "mysite1.com"
			],
			"username" => "username1"
		];
	}

}
