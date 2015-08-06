# Laravel Stepper

Permet de créer un outil de visualisation de l'avancement par étapes.

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

After updating composer, add the ServiceProvider to the providers array in config/app.php
Après la mise à jour composer, ajouter le ServiceProvider au tableau de providers dans config/app.php

```
'Axn\LaravelStepper\ServiceProvider',
```

