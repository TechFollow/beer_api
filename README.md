# Beer Api 

Beer api est une application de notation de bières.

## Description

Cette application permet à des utilisateurs de saisir une note pour une bière lorsqu'il la déguste.

Cette api permet aussi de trouver des bières en fonction de leur degré d'alcool ou d'amertume.

Les specifications se trouvent dans le dossier `doc/spec`.

**Pré-requis**
> Docker
> Docker compose
> Make

Pour lancer l'initialisation de la stack, faire un `make setup`. Les conteneurs seront construits et demarrés.
Une fois lancé, `make vendor` provisionnera les vendors.

Pour avoir plus d'informations sur les differentes commandes disponibles avec make, executez `make`.
