# ActiveRecord Relation Behavior [![Build Status](https://secure.travis-ci.org/yiiext/activerecord-relation-behavior.png)](http://travis-ci.org/yiiext/activerecord-relation-behavior)

This extension is inspired by all the yii extensions that aim to improve saving of related records.
It allows you to assign related records especially for MANY_MANY relations more easily.
It puts together the awesomeness of all the extensions mentioned below (see headline "Feature comparison").
It comes with 100% test coverage and well structured and clean code so it can safely be used in enterprise production environment.


## Requirements

* Yii 1.1.6 or above
* As Yii Framework this behavior is compatible with all PHP versions above 5.1.0.
  I try to be at least as backwards compatible as Yii is, which is PHP 5.1.0,
  if there are any problems with php versions, please [report it](https://github.com/yiiext/activerecord-relation-behavior/issues)!
* Behavior will only work for ActiveRecord classes that have primary key defined.
  Make sure to override [primaryKey()](http://www.yiiframework.com/doc/api/1.1/CActiveRecord#primaryKey%28%29-detail) method when your
  table does not define a primary key.
* _You need php 5.3.x or higher to run the unit tests._


## How to install

1. Get the source in one of the following ways:
   * [Download](https://github.com/yiiext/activerecord-relation-behavior/tags) the latest version and place the files under
     `extensions/yiiext/behaviors/activerecord-relation/` under your application root directory.
   * Add this repository as a git submodule to your repository by calling
     `git submodule add https://github.com/yiiext/activerecord-relation-behavior.git extensions/yiiext/behaviors/activerecord-relation`
2. Add it to the models you want to use it with, by adding it to the `behaviors()` method.

~~~php
<?php
public function behaviors()
{
    return array(
        'activerecord-relation'=>array(
            'class'=>'ext.yiiext.behaviors.activerecord-relation.EActiveRecordRelationBehavior',
        ),
    );
}
~~~


## Let the magic begin...

We have two ActiveRecord classes (the ones from [Yii definitive guide](http://www.yiiframework.com/doc/guide/1.1/en/database.arr#declaring-relationship)):
```php
<?php
class Post extends CActiveRecord
{
    // ...
    public function relations()
    {
        return array(
            'author'     => array(self::BELONGS_TO, 'User',     'author_id'),
            'categories' => array(self::MANY_MANY,  'Category', 'tbl_post_category(post_id, category_id)'),
        );
    }
}

class User extends CActiveRecord
{
    // ...
    public function relations()
    {
        return array(
            'posts'   => array(self::HAS_MANY, 'Post',    'author_id'),
            'profile' => array(self::HAS_ONE,  'Profile', 'owner_id'),
        );
    }
}
```

Somewhere in our application code we can do:
```php
<?php
    $user = new User();
    $user->posts = array(1,2,3);
    $user->save();
    // user is now author of posts 1,2,3

    // this is equivalent to the last example:
    $user = new User();
    $user->posts = Post::model()->findAllByPk(array(1,2,3));
    $user->save();
    // user is now author of posts 1,2,3

    $user->posts = array_merge($user->posts, array(4));
    $user->save();
    // user is now also author of post 4

    $user->posts = array();
    $user->save();
    // user is not related to any post anymore

    $post = Post::model()->findByPk(2);
    $post->author = User::model()->findByPk(1);
    $post->categories = array(2, Category::model()->findByPk(5));
    $post->save();
    // post 2 has now author 1 and belongs to categories 1 and 5

    // adding a profile to a user:
    $user->profile = new Profile();
    $user->profile->save(); // need this to ensure profile got a primary key
    $user->save();
```


## Some things you should care about...

* once you use this behavior you can not set relations by setting the foreign key attributes anymore.
  For example if you set `$model->author_id` it will have no effect since ARRelationBehavior will overwrite it
  with null if there is no related record or set it to related records primary key.
  Instead simply assign the value to the relation itself: `$model->author = 1;` / `$model->author = null;`
* relations will not be refreshed after saving, so if you only set primary keys there are no objects yet.
  Call `$model->reload()` to force reloading of related records. Or load related records with forcing reload:
  `$model->getRelated('relationName',true)`.
* This behavior will only work for relations that do not have additional conditions, joins, groups
  or the like defined since the expected result after setting and saving them is not always clear.
* if you assigned a record to a BELONGS_TO relation, for example `$post->author = $user;`,
  `$user->posts` will not be updated automatically (might add this as a feature later).


## Exceptions explained

### "You can not save a record that has new related records!"

You have assigned a record to a relation which has not been saved (it is not in the database yet).
Since ActiveRecord Relation Behavior needs its primary key to save it to a relation table, this will not work.
You have to call `->save()` on all new records before saving the related record.

### "A HAS_MANY/MANY_MANY relation needs to be an array of records or primary keys!"

You can only assign arrays to HAS_MANY and MANY_MANY relations, assigning a single record to a ..._MANY relation is not possible.

### "Related record with primary key "X" does not exist!"

You tried to assign primary key value _X_ to a relation, but _X_ does not exist in your database.


## Feature comparison

Inspired by and put together the awesomeness of the following yii extensions:

- can save MANY_MANY relations like cadvancedarbehavior, eadvancedarbehavior, esaverelatedbehavior and advancedrelationsbehavior
- cares about relations when records get deleted like eadvancedarbehavior (not yet implemented, see github [issue #7](https://github.com/yiiext/activerecord-relation-behavior/issues/7))
- can save BELONGS_TO, HAS_MANY, HAS_ONE like eadvancedarbehavior, esaverelatedbehavior and advancedrelationsbehavior
- saves with transaction and can handle external transactions like with-related-behavior, esaverelatedbehavior and saverbehavior
- does not touch additional data in MANY_MANY table (cadvancedarbehavior deleted it)
- validates for array on HAS_MANY and MANY_MANY relation to have more clear semantic

these are the extensions mentioned above
- cadvancedarbehavior        http://www.yiiframework.com/extension/cadvancedarbehavior/
- eadvancedarbehavior        http://www.yiiframework.com/extension/eadvancedarbehavior
- advancedrelationsbehavior  http://www.yiiframework.com/extension/advancedrelationsbehavior
- saverbehavior              http://www.yiiframework.com/extension/saverbehavior
- with-related-behavior      https://github.com/yiiext/with-related-behavior
- CSaveRelationsBehavior     http://code.google.com/p/yii-save-relations-ar-behavior/
- esaverelatedbehavior       http://www.yiiframework.com/extension/esaverelatedbehavior

reviewed but did not take something out:
- xrelationbehavior          http://www.yiiframework.com/extension/xrelationbehavior
- save-relations-ar-behavior http://www.yiiframework.com/extension/save-relations-ar-behavior

Many thanks to the authors of these extensions for inspiration and ideas.


## Run the unit test

This behavior is covered by unit tests with 100% code coverage (ECompositeDbCriteria is currently not covered since composite pks are not fully supported yet).
To run the unit tests you need [phpunit](https://github.com/sebastianbergmann/phpunit#readme) installed
and the test class requires php 5.3 or above.

1. make sure yii framework is available under ./yii/framework
   you can do this by
   - cloning the yii git repo with `git clone https://github.com/yiisoft/yii.git yii`
   - or linking existing yii directory here with `ln -s ../../path/to/yii yii`
2. run `phpunit EActiveRecordRelationBehaviorTest.php` or if you want coverage information in html,
   run `phpunit --coverage-html tmp/coverage EActiveRecordRelationBehaviorTest.php`

## FAQ

### When using a MANY_MANY relation, not changing it in any way and doing save() does it re-save relations or not?

It uses `CActiveRecord::hasRelated()` to check if a relation has been
loaded or set and will only save if this is the case.
It will re-save if you loaded and did not change, since it is not able
to detect this.
But re-saving does not mean entries in MANY_MANY table get deleted and
re-inserted. It will only run a delete query, that does not match any rows if you
did not touch records, so no row in db will be touched.

### is it possible to save only related links (n-m table records) without re-saving model?

Currently not, will add this feature in the future: [issue #16](https://github.com/yiiext/activerecord-relation-behavior/issues/16).

### how can I delete a particular id from many-many relation? do I need to load all related records for this?

Currently you have to load all and re-assign the array. Will add an api for this; [issue #16](https://github.com/yiiext/activerecord-relation-behavior/issues/16).
