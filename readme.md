# Laravel Stepper

Permet de créer un outil de visualisation de l'avancement par étapes de ce type :

![laravel-stepper.png](https://bitbucket.org/repo/bd8dx5/images/2350313328-laravel-stepper.png)

* [Installation](#markdown-header-installation)
* [Utilisation](#markdown-header-utilisation)
* [Personnaliser le template](#markdown-header-personnaliser-le-template)
* [Personnaliser les classes](#markdown-header-personnaliser-les-classes)
* [changelog](changelog.md) :arrow_upper_right:


## Installation

Inclure le package avec Composer :

```
composer require axn/laravel-stepper
```

Ajouter le ServiceProvider au tableau de providers dans config/app.php

```
'Axn\LaravelStepper\ServiceProvider',
```

## Utilisation

Comme le stepper va être utilisé sur plusieurs pages, et qu'il peut y avoir plusieurs stepper par application, nous allons le définir dans une classe dédiée :

```php
use Axn\LaravelStepper\Stepper;

class BasicStepper extends Stepper
{
    public function register()
    {
        $this->addStep('step 1');
        $this->addStep('step 2');
        $this->addStep('step 3');
        $this->addStep('step 4');
        $this->addStep('step 5');
    }
}
```

Ce qui est important de noter au sujet de cette classe :
  - elle étends la classe abstraite ``Axn\LaravelStepper\Stepper``
  - elle implémente la méthode ``register()``
  - dans cette méthode nous définissons les différentes étapes

Ensuite, nous pouvons injecter cette classe dans les méthodes de controlleur :

```php
    //...

    public function index(BasicStepper $stepper)
    {
        $stepper->setCurrentStepName('step 3');

        return $this->view('example-view', [
            'stepper' => $stepper->render()
        ]);
    }

    //...
```

La classe étant injectée par le conteneur IoC, elle est instanciée et la méthode ``register()`` est automatiquement invoquée. Ensuite nous indiquons au stepper qu'elle est l'étape courante ``$stepper->setCurrentStepName('step 3')``. Enfin, nous passons à la vue le rendu du stepper dans laquelle nous pourrons simplement mettre ``{!! $stepper !!}``.


## Personnaliser le template

### Changer de template

Ce package fournis plusieurs templates, par défaut c'est le template... "default" qui est utilisé. Pour utiliser le template "arrows" il suffit d'ajouter à votre classe de stepper le nom du template à utiliser :


```php
use Axn\LaravelStepper\Stepper;

class BasicStepper extends Stepper
{
    protected $view = 'stepper::arrows';
    //...
}
```

Et le tour est joué !

Évidement, de façon très classique, vous pouvez utiliser n'importe quelle vue du système de templates. Par exemple pour utiliser le template ``resources/views/partials/steppers/custom.blade.php`` indiquez dans votre classe :

```php
use Axn\LaravelStepper\Stepper;

class BasicStepper extends Stepper
{
    protected $view = 'partials.steppers.custom';
    //...
}
```

### Personnaliser un template

Les templates fournis sont plus des exemples pour comprendre comment utiliser le paquet que des templates à utiliser en production. Par exemple ils embarquent les styles CSS, ce qui n'est pas la meilleure des pratiques...

Il est donc possible de partir d'un de ces templates fournis par le package et de les modifier. Pour cela il faut publier les templates puis les modifier.

Pour publier les templates lancez la commande suivante :

```
php artisan vendor:publish
```

Après cela vous trouverez les templates dans ``resources/views/vendor/stepper/`` ; il vous sera alors très simple de les modifier selon vos besoins.

### Méthodes dans les templates

Afin de personnaliser votre template, différentes méthodes sont accessibles. Petit tour d'horizon...

#### La classe Stepper

``{{ $stepper->getStep($stepName) }}`` retourne une étape, instance de StepInterface, selon un nom d'étape donné

``{{ $stepper->getCurrentStep($stepName) }}`` retourne l'instance de l'étape courrante

``{{ $stepper->getPrevStep($stepName) }}`` retourne l'instance de l'étape précédente

``{{ $stepper->getNextStep($stepName) }}`` retourne l'instance de l'étape suivante

``{{ $stepper->stepExists($stepName) }}`` indique si une étape donnée par son nom existe

``{{ $stepper->getNumSteps() }}`` indique le nombre d'étapes au total

``{{ $stepper->getCurrentStepName() }}`` indique le nom de l'étape courante

L'objet stepper implémente l'interface Iterator, il est donc possible de boucler facilement sur lui, chaque tour de boucle retournant une instance de StepInterface implémentée par la classe Step.

```php
@foreach ($stepper as $step)
    // ...
@endforeach
```

#### La classe Step (implémentation de StepInterface et plus si affinité)

``{{ $step->getName() }}`` retourne le nom associé à l'étape

``{{ $step->getUrl() }}`` retourne l'URL associée à l'étape

``{{ $step->getRoute() }}`` retourne la route associée à l'étape

``{{ $step->getPosition() }}`` retourne la position de l'étape

``{{ $step->getTitle() }}`` retourne le titre associé à l'étape

``{{ $step->getDescription() }}`` retourne la description associée à l'étape

``{{ $step->isCurrent() }}`` indique si l'étape est l'étape courante

``{{ $step->isPassed() }}`` indique si l'étape est passée

``{{ $step->isFirst() }}`` indique si l'étape est la première étape

``{{ $step->isLast() }}`` indique si l'étape est la dernière étape


## Personnaliser les classes

Comme la classe concrête de notre stepper est une fille de la classe abstraite stepper, il est possible de surcharger les propriétées et méthodes de cette dernière.

Aussi il est possible de surcharger la classe Step assez facilement en implémentant l'interface StepInterface et en renseignant dans la classe stepper le nom de la classe Step à utiliser :

```php
use Axn\LaravelStepper\Stepper;

class BasicStepper extends Stepper
{
    protected $stepClass = 'App\CustomStep';
    //...
}
```
