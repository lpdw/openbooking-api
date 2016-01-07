# OpenBooking API

![alt text][logo]

[logo]: https://github.com/lpdw/openbooking-api/LOGO.png "Logo"


API affiliated with the OpenBooking WordPress plugin (https://github.com/lpdw/openbooking)

This API is an open source alternative solution for any event management, specificly for booking management.

## Authors

LAOUITI Elias Cédric

FLORILE Maxime

CAYET Matthieu

POULAIN Enguerran

## Installation
Open a terminal in the API's repository, and then reach to installation and launch installation:
> \> cd installation
>
> \> php index.php

Then follow the insructions..
> What is your database host ?
>
> \> DBhost

> What is your database name ?
>
> \> DBname

> What is your database user ?
>
> \> MySQL user

> What is your database password ?
>
> \> MySQL user password

//TODO: Ligne fichier cron


## API functioning

For the moment, the API fits into a WordPress environment, with the foregoing WordPress plugin. It allow to create and manage events, with a booking system, a waiting list, and an emailing process. Event creator also can manage participants list, writing comments and report presence for example..

###Emailing


You will have to fill EmailType table in OpenBooking database to create/manage your own email templates, using the following tags, which are associated to event and participant data tables:

- {{eventLocation}}
- {{eventDate}}
- {{eventName}}
- {{recipientFirstName}}
- {{recipientLastName}}

These tags will be replaced by associated datas in the sent mails.
You will have the default templates, which are:

- remind1day: 1 day before event reminder
- remind7day: 7 days before event reminder
- waiting_list: Getting in waiting list notification
- participant_registration: Registration confirmation
- participant_annulation: Annulation confirmation
- event_annulation: Event annulation notification
- participant\_waiting\_list\_place_available: Getting out of waiting list notification


###Specification

Employed technologies

- PHP 5.6
- MySQL 5.5
- [PHP Mailer 5.2](https://github.com/PHPMailer/PHPMailer/ "PHPMailer")


### Error handling

An error handling system has been applied, working with error codes, to make the API debugging easier for developers.

Here is the exceptions list, with associated codes and descriptions:

- NullDatasException | -2 : The parameters datas are invalid or empty
- LoginException | -3 : The login process failed (bad credentials)
- UnknowErrorException | -4 : For unknown errors
- ValidDatasException | -5 : The datas format is invalid (Example: invalid email address)
- SQLErrorException | -6 : To catch Sql errors
- AccessDeniedException | -7 : To manage users account limits and privileges (Example: Banned user can't register to an event)
- UnknowEmailTemplateException | -8 : The email template parameter in unknown (Create or correct it in base)
- DataAlreadyExistInDatabaseException | -9 : To prevent against duplicate entry error in database
- EventIsCanceledException | -10 : To avoid users registration for cancelled events

***
***

# API OpenBooking
API associée au plugin WordPress OpenBookig (https://github.com/lpdw/openbooking)

Cette API est destinée à offrir une solution open source alternative pour la gestion d'événement, et en particulier pour la gestion des réservations.

## Auteurs

LAOUITI Elias Cédric

FLORILE Maxime

CAYET Matthieu

POULAIN Enguerran

## Installation

Ouvrir un terminal dans le répertoire de l'API, se rendre dans installation, et démarrer:
> \> cd installation
>
> \> php index.php

Ensuite, suivre les instructions..
> What is your database host ?
>
> \> DBhost

> What is your database name ?
>
> \> DBname

> What is your database user ?
>
> \> MySQL user

> What is your database password ?
>
> \> MySQL user password

//TODO: Ligne fichier cron

## Fonctionnement de l'API

Pour le moment, l'API s'inscrit dans un environnement WordPress à l'aide du plugin suscité. Elle permet la création et la gestion de divers événements, avec un système de réservation, de liste d'attente, et de mailing. Le créateur d'événement peut également gérer ses particpants, leur assigner des commentaires, un statut ou bien reporter leur présence.

###Emailing

Vous devrez compléter la table EmailType dans la base de données pour créer vos propres modèles d'emails, en utilisant les tags suivants, qui sont associées aux données des tables participant et event:

- {{eventLocation}}
- {{eventDate}}
- {{eventName}}
- {{recipientFirstName}}
- {{recipientLastName}}

Ces tags seront remplacés par les données associées dans les emails envoyés.
Vous disposerez des modèles par défaut, à savoir:

- remind1day: Rappel 1 jour avant l'événement
- remind7day: Rappel 7 jours avant l'événement
- waiting_list: Notification de mise en liste d'attente
- participant_registration: Confirmation de réservation
- participant_annulation: Confirmation d'annulation
- event_annulation: Notification d'annulation de l'événement
- participant\_waiting\_list\_place_available: Notification de sortie de la liste d'attente après qu'une place ce soit liberée

###Spécification

Technologies utilisées

- PHP 5.6
- MySQL 5.5
- [PHP Mailer 5.2](https://github.com/PHPMailer/PHPMailer/ "PHPMailer")


### La gestion des erreurs

Un système de code d'erreurs a été mis en place afin de faciliter le debug pour les développeurs.
Voici la liste des exceptions et de leurs codes et descriptions:

- NullDatasException | -2 : Les données passées en paramètres sont null ou inexistantes
- LoginException | -3 : Erreur d'authentification (mauvais identifiants)
- UnknowErrorException | -4 : Pour les erreurs inconnues
- ValidDatasException | -5 : Pour les erreurs de format de données (Exemple: format d'adresse email invalide)
- SQLErrorException | -6 : Pour les erreures Sql
- AccessDeniedException | -7 : Pour gérer les droits d'accès des différents utilisateurs (Exemple: Un utilisateur banni ne peut plus s'inscrire à un événement)
- UnknowEmailTemplateException | -8 : Modèle d'email inconnu (Le créer ou le corriger dans la base)
- DataAlreadyExistInDatabaseException | -9 : Pour gérer les erreures en cas d'insertions identiques dans la base de données
- EventIsCanceledException | -10 : Pour éviter que des réservations surviennent pour événement annulé par exemple