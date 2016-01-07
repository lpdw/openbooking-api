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


### La gestion des erreurs

Un système de code d'erreurs à été mis en place afin de faciliter la compréhension des potentiels bugs par les développeurs utilisant l'api. 

Voici la liste des exceptions, ainsi que leur code et leur description. 

- NullDatasException | -2 : Les données passées en paramètres sont null ou inéxistantes. 
- LoginException | -3 : Login Fail
- UnknowErrorException | -4 : Unknow Error
- ValidDatasException | -5 : Valid datas : Format non valid (Exemple : Email non valide)
- SQLErrorException | -6 : Sql Error
- AccessDeniedException | -7 : Access Denied (Ex : Utilisateur ban qui ne peut donc pas s'inscrire à des events)
- UnknowEmailTemplateException | -8 : Unknow Email template
- DataAlreadyExistInDatabaseException | -9 : Duplicate db entry
- EventIsCanceledException | -10 : Event is canceled 


 

