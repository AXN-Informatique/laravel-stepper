# Laravel Stepper

Permet de créer un outil de visualisation de l'avancement par étapes de ce type :

![laravel-stepper.png](https://bitbucket.org/repo/bd8dx5/images/2350313328-laravel-stepper.png)

## Installation

Requérir ce paquet dans votre composer.json :

```
    "require" : {
        "axn/laravel-stepper" : "~1.0"
    }
```

Ajouter le dépôt privé à votre composer.json :

```
    "repositories" : [{
            "type" : "vcs",
            "url" : "git@bitbucket.org:axn/laravel-stepper.git"
        }
    ]
```

Vous aurez besoin d'une clé SSH pour exécuter la commande suivante :

```
composer update
```

Après la mise à jour composer, ajouter le ServiceProvider au tableau de providers dans config/app.php

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
        $this->addStep('step 1', '/step-1');
        $this->addStep('step 2', '/step-2');
        $this->addStep('step 3', '/step-3');
        $this->addStep('step 4', '/step-4');
        $this->addStep('step 5', '/step-5');
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

Ce package fournis plusieurs templates, par défaut c'est le template "default" qui est utilisé. Pour utiliser le template "arrows" il suffit d'ajouter à votre classe de stepper le nom du template à utiliser :


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

Il est possible de partir d'un des templates fourni par le package et de le modifier. Pour cela il faut publier les templates puis les modifier.

Pour publier les templates lancez la commande suivante :

```
php artisan vendor:publish
```

Après cela vous trouverez les templates dans ``resources/views/vendor/stepper/`` ; il vous sera alors très simple de les modifier.
