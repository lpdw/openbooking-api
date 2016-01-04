# OpenBooking API
API pour le plugin openbooking (https://github.com/lpdw/openbooking)

## Auteurs 

LAOUITI Elias Cédric

FLORILE Maxime

CAYET Matthieu

POULAIN Enguerran

## Installation 
TODO

## Fonctionnement de l'api

###Spécification

Technos utilisés

- PHP 5.6
- MySQL 5.5
- [PHP Mailer 5.2](https://github.com/PHPMailer/PHPMailer/ "PHPMailer")


### Les erreurs

Un système de gestion des erreurs à été développé. Le fonctionnement : 
Lorsque un methode de l'api est appellée, un json est retourné, se json contient entre 2 ou 3 infos en fonction du type de requete. 

#####Les requetes POST
Si une methode de l'api est de type POST (création d'objet), un json de ce type est retourné dans le cas ou aucune exception n'est levée et qu'aucune erreur apparait : 

`{"code":0,"message":"Ok"}`

Le code d'erreur 0 signifie que tout est ok, si le code est différent de 0, il faut regarder le message pour plus d'infos. 

Si le code est -1, il s'agit d'une erreur 'Custom'. 

#####Les requetes GET
Si une methode de l'api est de type GET (récupération d'objet), un json de ce type est retourné dans le cas ou aucune exception n'est levée et qu'aucune erreur apparait : 

`{"code":0,"message":"Ok", "datas":"mes données"}`

Le code d'erreur 0 signifie que tout est ok, si le code est différent de 0, il faut regarder le message pour plus d'infos. 

Si le code est -1, il s'agit d'une erreur 'Custom'. 

Dans le cas où, une erreur apparait, "datas" ne sera pas retourné. 

 

