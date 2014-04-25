<?php
class ERestTestMigration extends CDbMigration
{
	public function checkIntegrity($check=true)
	{
		$schema = $this->getDbConnection()->getSchema();
    $schema->checkIntegrity($check);
	}

		
	public function up()
	{
		$this->checkIntegrity(false);
		ob_start();
		try {
			$this->createTable('tbl_user', array(
				'id'=>'pk',
				'username'=>'string',
				'password'=>'string',
				'email'=>'string',
			));
		} catch(Exception $e) {
			$this->truncateTable("tbl_user");
		}

		try {
			$this->createTable('tbl_profile', array(
				'id'=>'pk',
				'user_id'=>'INT(11)',
				'photo'=>'binary',
				'website'=>'string',
				'FOREIGN KEY (user_id) REFERENCES tbl_user(id) ON DELETE CASCADE ON UPDATE CASCADE',
			));
		} catch(Exception $e) {
			$this->truncateTable("tbl_profile");
		}

		try {
			$this->createTable('tbl_category', array(
				'id'=>'pk',
				'name'=>'string',
			));
		} catch(Exception $e) {
			$this->truncateTable("tbl_category");
		}

		try {
			$this->createTable('tbl_post', array(
				'id'=>'pk',
				'title'=>'string',
				'content'=>'text',
				'create_time'=>'timestamp',
				'author_id'=>'integer',
				'FOREIGN KEY (author_id) REFERENCES tbl_user(id) ON DELETE CASCADE ON UPDATE CASCADE',
			));
		} catch(Exception $e) {
			$this->truncateTable("tbl_post");
		}

		try {
		$this->createTable('tbl_post_category', array(
			'post_id'=>'integer',
			'category_id'=>'integer',
			'post_order'=>'integer', // added this row to test additional attributes added to a relation
			'PRIMARY KEY(post_id, category_id)',
			'FOREIGN KEY (post_id) REFERENCES tbl_post(id) ON DELETE CASCADE ON UPDATE CASCADE',
			'FOREIGN KEY (category_id) REFERENCES tbl_category(id) ON DELETE CASCADE ON UPDATE CASCADE',
		));
		} catch(Exception $e) {
			$this->truncateTable("tbl_post_category");
		}

		try {
			$this->createTable('tbl_binary', array(
				'id'=>'pk',
				'name'=>'BINARY(16) NOT NULL',
			));
		} catch(Exception $e) {
			$this->truncateTable("tbl_binary");
		}

		$this->execute("
			INSERT INTO tbl_user
			VALUES 
				(NULL, 'username1', 'password1', 'email@email1.com'),
				(NULL, 'username2', 'password2', 'email@email2.com'),
				(NULL, 'username3', 'password3', 'email@email3.com'),
				(NULL, 'username4', 'password4', 'email@email4.com'),
				(NULL, 'username5', 'password5', 'email@email5.com'),
				(NULL, 'username6', 'password6', 'email@email6.com')
		");
		$this->execute("
			INSERT INTO tbl_profile 
			VALUES 
				(NULL, 1, 1, 'mysite1.com'),
				(NULL, 2, 0, 'mysite2.com'),
				(NULL, 3, 1, 'mysite3.com'),
				(NULL, 4, 0, 'mysite4.com'),
				(NULL, 5, 1, 'mysite5.com'),
				(NULL, 6, 0, 'mysite6.com')
		");
		$this->execute("
			INSERT INTO tbl_category
			VALUES 
				(NULL, 'cat1'),
				(NULL, 'cat2'),
				(NULL, 'cat3'),
				(NULL, 'cat4'),
				(NULL, 'cat5'),
				(NULL, 'cat6')
		");
		$this->execute("
			INSERT INTO tbl_post
			VALUES 
				(NULL, 'title1', 'content1', '2013-08-07 10:09:41', 1),
				(NULL, 'title2', 'content2', '2013-08-07 10:09:42', 2),
				(NULL, 'title3', 'content3', '2013-08-07 10:09:43', 3),
				(NULL, 'title4', 'content4', '2013-08-07 10:09:44', 4),
				(NULL, 'title5', 'content5', '2013-08-07 10:09:45', 5),
				(NULL, 'title6', 'content6', '2013-08-07 10:09:46', 6)
		");
		$this->execute("
			INSERT INTO tbl_post_category
			VALUES 
				(1, 1, 1),
				(2, 2, 1),
				(3, 3, 1),
				(4, 4, 1),
				(5, 5, 1),
				(6, 6, 1),
				(1, 2, 2)
		");
		$this->checkIntegrity(true);
		$this->execute("
			INSERT INTO tbl_binary
			VALUES
				(1, UNHEX('DE46C83E5A50CED70E6A525A7BE6D709'))
		");
		ob_end_clean();
	}

	public function down()
	{
		$this->checkIntegrity(false);
		ob_start();
		try {
			$this->dropTable('tbl_post_category');
			$this->dropTable('tbl_category');
			$this->dropTable('tbl_post');
			$this->dropTable('tbl_profile');
			$this->dropTable('tbl_user');
			$this->dropTable('tbl_binary');
		} catch(Exception $e) {
			//Nothing to do...
		}
		ob_end_clean();
		$this->checkIntegrity(true);
	}
}
