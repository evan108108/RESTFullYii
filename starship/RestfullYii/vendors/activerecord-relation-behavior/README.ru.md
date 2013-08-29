# Поведение ActiveRecord Relation [![Build Status](https://secure.travis-ci.org/yiiext/activerecord-relation-behavior.png)](http://travis-ci.org/yiiext/activerecord-relation-behavior)

Данное поведение создано на основе идей из расширений, упрощающих сохранение связанных данных модели (подробнее
в разделе «Сравнение возможностей»). Оно, главным образом, упрощает работу с отношениями типа MANY_MANY
(многие-ко-многим). Код расширения структурирован, чист и полностью покрыт модульными тестами. Это значит, что
оно может быть использовано в реальных приложениях масштаба предприятия.


## Требования

* Yii версии 1.1.6 или выше.
* Как и сам фреймворк данное расширение совместимо с PHP версии 5.1.0 или выше. Если у вас возникли проблемы
  с поддержкой определённых версий PHP, то мы были бы признательны за ваше
  [сообщение о них](https://github.com/yiiext/activerecord-relation-behavior/issues).
* Поведение работает только с моделями, у которых есть первичный ключ. Если в таблице модели не был определён
  первичный ключ, то вы должны задать его вручную в методе
  [primaryKey()](http://www.yiiframework.com/doc/api/1.1/CActiveRecord#primaryKey%28%29-detail) этой модели.
* _Для модульного тестирования необходим PHP версии 5.3.0 или выше._


## Установка

1. Получить расширение можно следующими способами:
   * [Скачайте](https://github.com/yiiext/activerecord-relation-behavior/tags) последнюю версию и распакуйте файлы
     в директорию `extensions/yiiext/behaviors/activerecord-relation/` (относительно корня вашего приложения).
   * Добавьте репозиторий расширения как подмодуль (`git submodule`) в основном репозитории приложения следующим образом:
     `git submodule add https://github.com/yiiext/activerecord-relation-behavior.git extensions/yiiext/behaviors/activerecord-relation`
2. Для того, чтобы включить данное поведение вам необходимо добавить следующий код в метод `behaviors()` нужной модели:

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


## Да свершится чудо!

Рассмотрим следующие две модели
(они используются [в руководстве Yii](http://www.yiiframework.com/doc/guide/1.1/ru/database.arr#sec-2)):
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

Когда поведение включено, то работа с моделями и связанными данными будет выглядеть следующим образом:
```php
<?php
    $user = new User();
    $user->posts = array(1,2,3);
    $user->save();
    // пользователь теперь является автором постов с первичными ключами 1, 2 и 3

    // пример выше эквивалентен следующему примеру:
    $user = new User();
    $user->posts = Post::model()->findAllByPk(array(1,2,3));
    $user->save();
    // пользователь теперь является автором постов с первичными ключами 1, 2 и 3

    $user->posts = array_merge($user->posts, array(4));
    $user->save();
    // пользователь теперь является автором поста с первичным ключом 4,
    // помимо тех, автором которых он был ранее

    $user->posts = array();
    $user->save();
    // пользователь теперь не имеет авторства ни над одним из постов

    $post = Post::model()->findByPk(2);
    $post->author = User::model()->findByPk(1);
    $post->categories = array(2, Category::model()->findByPk(5));
    $post->save();
    // пост с первичным ключом 2 теперь принадлежит пользователю-автору с первичным ключом 1
    // и принадлежит к категориям с первичными ключами 1 и 5

    // добавление профиля пользователю:
    $user->profile = new Profile();
    $user->profile->save(); // необходимо для того, чтобы быть уверенным в том, что профиль имеет первичный ключ
    $user->save();
```


## Моменты, о которых необходимо помнить

* При использовании данного поведения вы не можете явно присваивать определённые значения самим столбцам внешних ключей.
  Например, если вы присвоите какое-то значение свойству `$model->author_id`, то это не поменяет автора поста,
  потому как данное поведение присвоит ему значение `null`. (Происходит это по той причине, что вы ничего не присвоили
  отношению `author`, с которым связан столбец внешнего ключа `author_id`.) Просто присваивайте нужные значение самим
  отношениям: `$model->author = 1;` или `$model->author = null;`.
* Отношения не будут обновлены после их сохранения. Если вы просто присвоили значения первичных ключи отношению
  до сохранения, то после сохранения модели с этим отношением реальных объектов в нём не будет. Вызывайте
  `$model->reload()` для форсирования перезагрузки связанных данных. Другой способ получения связанных данных
  из отношения: `$model->getRelated('relationName',true)`.
* Данное поведение работает только с отношениями, которые не имеют дополнительных условий, JOINов и группировок
  (`GROUP`). Результат присваивания и сохранения связанных данных у отношений такого рода не всегда однозначно
  понятен и прост.
* Если вы присваиваете запись к отношению BELONGS_TO (пример: `$post->author = $user;`), то в этом случае
  данные отношения `$user->posts` не будут обновлены автоматически (возможно, такой функционал будет добавлен
  в будущем).


## Описание исключений

### You can not save a record that has new related records!

Отношению была присвоена ещё не сохранённая запись (её не существует в базе данных). Это не будет работать, потому как
поведение ActiveRecord Relation для сохранения связанных данных требует наличия у них значения первичного ключа. Перед
присваиванием множества записей к какому-либо отношению необходимо это множество полностью сохранить вызовом метода
`save()` у каждой модели.

### A HAS_MANY/MANY_MANY relation needs to be an array of records or primary keys!

Отношениям HAS_MANY и MANY_MANY можно присваивать только массивы. Присваивание одиночных записей (не массивов) к таким
отношениям невозможно и лишено смысла.

### Related record with primary key "X" does not exist!

Исключение из-за того, что вы попытались присвоить какому-то отношению модели несуществующие в базе значения
первичных ключа.


## Сравнение возможностей

Следующие возможности были заимствованы из других расширений:

- Возможность сохранения данных в отношениях типа MANY_MANY (cadvancedarbehavior, eadvancedarbehavior,
esaverelatedbehavior и advancedrelationsbehavior также умеют это).
- Возможность сохранения данных в отношениях типа BELONGS_TO, HAS_MANY и HAS_ONE (eadvancedarbehavior,
esaverelatedbehavior и advancedrelationsbehavior также умеют это).
- Сохранение произодится с использованием транзакции, при этом есть поддержка внешних транзакций (with-related-behavior,
esaverelatedbehavior и saverbehavior также умеют это).
- Не производит изменений в дополнительных данных связующей таблицы отношения MANY_MANY (cadvancedarbehavior удаляет их).
- Проверяет тип данных, присваиваемый отношению. К отношениям HAS_MANY и MANY_MANY можно присваивать только массивы.
(более чёткая семантика).

Расширения, которые были полезны при разработке данного поведения:
- cadvancedarbehavior        http://www.yiiframework.com/extension/cadvancedarbehavior/
- eadvancedarbehavior        http://www.yiiframework.com/extension/eadvancedarbehavior
- advancedrelationsbehavior  http://www.yiiframework.com/extension/advancedrelationsbehavior
- saverbehavior              http://www.yiiframework.com/extension/saverbehavior
- with-related-behavior      https://github.com/yiiext/with-related-behavior
- CSaveRelationsBehavior     http://code.google.com/p/yii-save-relations-ar-behavior/
- esaverelatedbehavior       http://www.yiiframework.com/extension/esaverelatedbehavior

Были изучены, но ничего полезного позаимствовано не было:
- xrelationbehavior          http://www.yiiframework.com/extension/xrelationbehavior
- save-relations-ar-behavior http://www.yiiframework.com/extension/save-relations-ar-behavior

Большое спасибо авторам всех этих расширений за их идеи и предложения.


## Запуск модульных тестов

Поведение полностью покрыто модульными тестами (класс ECompositeDbCriteria покрыт не полностью потому как составные
первичные ключи пока не поддерживаются). Для запуска модульных тестов вам нужен установленный
[PHPUnit](https://github.com/sebastianbergmann/phpunit#readme). Тестовые классы расширения требуют PHP версии 5.3
или выше.

1. Убедитесь в том, что дистрибутив Yii доступен по пути `./yii/framework`. Вы можете добится этого следующими способами:
   - Склонировав Git репозиторий при помощи команды `git clone https://github.com/yiisoft/yii.git yii`.
   - Создав символическую ссылку на уже существующую директорию с Yii `ln -s ../../path/to/yii yii`.
2. Выполните команду `phpunit EActiveRecordRelationBehaviorTest.php`. Если вы хотите получить информацию о покрытии
   кода в виде HTML файлов, то команда будет выглядеть следующим образом:
   `phpunit --coverage-html tmp/coverage EActiveRecordRelationBehaviorTest.php`


## Часто задаваемые вопросы

### Пересохраняются ли неизменённые связанные данные в MANY_MANY отношении?

Для проверки того, были ли загружены или установлены явно связанные данные в поведении используется метод
`CActiveRecord::hasRelated()`. Сохранение произойдёт в случае, если эти связанные данные существуют в отношении.
Поведение не может однозначно определить были ли изменены данные в отношении явно или нет.

Пересохранение не означает, что все соответствующие записи в связующей таблице удаляются, а потом добавляются заново.
Удаление данных в связующей таблице происходит только тогда, когда они отсутствуют в отношении, но существуют
в базе данных. Если вы ничего не меняли, то и в базе данных ничего не изменится.

### Существует ли возможность сохранения записей только в связующей таблице без повторного сохранения связываемых моделей?

Сейчас нет, но эта возможность будет реализована в будущем;
[тикет #16](https://github.com/yiiext/activerecord-relation-behavior/issues/16).

### Каким образом я могу удалить одну определённую связующую строчку в MANY_MANY отношении? Нужно ли мне для этого загружать все связанные модели?

На данный момент вы должны загрузить все связанные модели и переназначить массив с отношениями. API для этого будет
реализовано в будущем; [тикет #16](https://github.com/yiiext/activerecord-relation-behavior/issues/16).
