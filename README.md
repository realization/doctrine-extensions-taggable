# Doctrine2 taggable behavior extension

[![Build Status](https://travis-ci.org/hilobok/doctrine-extensions-taggable.png?branch=master)](https://travis-ci.org/hilobok/doctrine-extensions-taggable)

## Installation
```json
{
    "require": {
        //...
        "anh/doctrine-extensions-taggable":      "dev-master"
    },

    "autoload": {
        "psr-0": {
            "Acme": "src/"
        }
    }
}
```
### Symfony
edit **app/config/config.yml**:

```yaml
doctrine:
    dbal:
# ...

    orm:
# ... 
        mappings:
            taggable:
                type: annotation
                alias: AnhTaggable
                prefix: Anh\Taggable\Entity
                dir: "%kernel.root_dir%/../vendor/anh/doctrine-extensions-taggable/lib/Anh/Taggable/Entity"
```

edit **Acme/DemoBundle/Resources/config/services.yml** to add a service
```yaml
#...
services:
#...
    anh_taggable.manager:
        class: Anh\Taggable\TaggableManager
        arguments:
            - @doctrine.orm.entity_manager
            - Anh\Taggable\Entity\Tag
            - Anh\Taggable\Entity\Tagging
```


## Example
Create taggable entity

```php
<?php

use Anh\Taggable\TaggableInterface;
use Anh\Taggable\AbstractTaggable;

class Article extends AbstractTaggable implements TaggableInterface
{
    // ...

    public function getTaggableType()
    {
        return 'article';
    }
}
```

Using taggable extension

```php
<?php

use Anh\Taggable\TaggableManager;
use Anh\Taggable\TaggableSubscriber;

// create entity manager
// $em = EntityManager::create(...);

// create taggable manager
$taggableManager = new TaggableManager(
    $em, 'Anh\Taggable\Entity\Tag', 'Anh\Taggable\Entity\Tagging'
);

// add event subscriber
$em->getEventManager()->addEventSubscriber(
    new TaggableSubscriber($taggableManager)
);

// create and fill entity
$article = new Article();
// $article->setTitle(...);

// add tag
$tag = $taggableManager->loadOrCreateTag('This is a tag');
$article->addTag($tag);

// or add multiple tags
$tags = $taggableManager->loadOrCreateTags(array('tag1', 'tag2', 'tag3'));
$article->addTags($tags);

// see Anh\Taggable\AbstractTaggable for more

// ...

// getting tagged resources
$repository = $taggableManager->getTaggingRepository();

$tag = $taggableManager->loadOrCreateTag('Some tag')
// returns all resources tagged with tag 'Some tag'
$resources = $repository->getResourcesWithTag($tag);
// returns only artilces with tag 'Some tag'
$articles = $repository->getResourcesWithTypeAndTag('article', $tag);
```
