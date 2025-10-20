Here is a complete, structured summary and developer reference for the First Delivery Group v2.4 API, including all endpoints, request/response examples, and parameter expectations extracted from the official documentation.​

API Overview
Base URL:
https://www.firstdeliverygroup.com/api/v2

Authorization:
All requests require an HTTP header:
Authorization: Bearer {{token}}

Global Rate Limits:

Single create requests: 1 request every 10 seconds

Bulk create: 1 request every 10 seconds

Filter: 2 requests every 10 seconds

Status check: 1 request per second

Cancel orders: up to 100 orders per request

Endpoints Summary
Endpoint	Method	Purpose
/create	POST	Ajouter une commande
/bulk-create	POST	Ajouter plusieurs commandes
/etat	POST	Consulter l’état d’une commande
/filter	POST	Filtrer les commandes
/cancel-orders	POST	Annuler des commandes
/pickup	POST	Créer une demande d’enlèvement
/request-print/{pickupId}	POST	Imprimer une décharge pickup
1. Ajouter une commande
URL:
POST https://www.firstdeliverygroup.com/api/v2/create

Request Body
json
{
  "Client": {
    "nom": "nom client",
    "gouvernerat": "sousse",
    "ville": "medina",
    "adresse": "adresse client",
    "telephone": "00000000",
    "telephone2": ""
  },
  "Produit": {
    "prix": 20,
    "designation": "designation produit",
    "nombreArticle": 1,
    "commentaire": "commentaire",
    "article": "nom produit",
    "nombreEchange": 0
  }
}
Response
json
{
  "status": 201,
  "isError": false,
  "message": "Produit ajouté avec succès",
  "result": {
    "barCode": "683375045049",
    "link": "https://www.firstdeliverygroup.com/api/v2/print?q=eyji"
  }
}
2. Ajouter plusieurs commandes
URL:
POST https://www.firstdeliverygroup.com/api/v2/bulk-create

Max: 100 commandes par requête

Request Body
json
[
  {
    "Client": {
      "nom": "nom du client 1",
      "gouvernerat": "sousse",
      "ville": "medina",
      "adresse": "adresse client 1",
      "telephone": "88999000",
      "telephone2": ""
    },
    "Produit": {
      "prix": 50,
      "designation": "designation produit 1",
      "nombreArticle": 1,
      "commentaire": "",
      "article": "nom produit 1",
      "nombreEchange": 2
    }
  },
  {
    "Client": {
      "nom": "nom client 2",
      "gouvernerat": "sousse",
      "ville": "medina",
      "adresse": "adresse client 2",
      "telephone": "00000000"
    },
    "Produit": {
      "prix": 20,
      "designation": "designation produit 2",
      "nombreArticle": 1,
      "commentaire": "",
      "article": "nom produit 2"
    }
  }
]
Response
json
{
  "status": 201,
  "isError": false,
  "message": "3 produit(s) ajoutés avec succès",
  "result": {
    "link": "https://www.firstdeliverygroup.com/api/v2/bulk-print?q=eyji",
    "barCodes": [
      {"index": 0, "barCode": "111111111111"},
      {"index": 1, "barCode": "222222222222"},
      {"index": 2, "barCode": "333333333333"}
    ]
  }
}
3. Consulter l’état d’une commande
URL:
POST https://www.firstdeliverygroup.com/api/v2/etat

Request Body
json
{
  "barCode": "683377360858"
}
Response
json
{
  "status": 200,
  "isError": false,
  "message": "Etat du produit récupéré avec succès",
  "result": {
    "state": "En attente",
    "barCode": "683377360858"
  }
}
4. Filtrer les commandes
URL:
POST https://www.firstdeliverygroup.com/api/v2/filter

Request Body
json
{
  "barCode": "",
  "createdAtFrom": "2021-01-02",
  "createdAtTo": "2022-01-02",
  "state": 0,
  "pagination": {
    "pageNumber": 1,
    "limit": 10
  }
}
Response
json
{
  "status": 200,
  "isError": false,
  "message": "Commandes récupérées avec succès",
  "result": {
    "CurrentPage": 1,
    "TotalPages": 1,
    "TotalCount": 1,
    "PageSize": 10,
    "Items": [
      {
        "barCode": "xxxxxxxxxxxx",
        "createdAt": "2021-01-02T23:00:00.000Z",
        "pickupAt": "2021-01-04T18:51:38.000Z",
        "deliveredAt": "2021-01-05T18:49:32.000Z",
        "Client": {
          "name": "***",
          "address": "***",
          "city": "***",
          "state": "***",
          "telephone": "********"
        },
        "Product": {
          "designation": "***",
          "price": 20,
          "itemNumber": 1
        },
        "state": "**",
        "exchange": "0"
      }
    ]
  }
}
États possibles
Code	Description
0	En attente
1	En cours
2	Livré
3	Échange
5	Retour expéditeur
6	Supprimé
8	Au magasin
20	À vérifier
30	Retour reçu
31	Retour définitif
100–104	Phases de demande d’enlèvement
201–204	États de retour transporté
5. Annuler des commandes
URL:
POST https://www.firstdeliverygroup.com/api/v2/cancel-orders

Request Body
json
{
  "barCodes": [
    "1111111111",
    "1111111112",
    "1111111113"
  ]
}
Response
json
{
  "status": 200,
  "isError": false,
  "message": "Succès",
  "result": [
    "1111111111",
    "1111111112"
  ]
}
6. Créer une demande d’enlèvement
URL:
POST https://www.firstdeliverygroup.com/api/v2/pickup

Request Body
json
{
  "barCodes": [
    "123456789012",
    "123456789013",
    "123456789014"
  ]
}
Response
json
{
  "status": 201,
  "isError": false,
  "message": "Produit ajouté avec succès",
  "result": {
    "pickup": "683375045049",
    "link": "https://www.firstdeliverygroup.com/api/v2/print-pickup?q=eyji"
  }
}
7. Imprimer décharge Pickup
URL:
POST https://www.firstdeliverygroup.com/api/v2/request-print/{pickupId}

Description
Aucun corps requis.

Response
json
{
  "status": 200,
  "isError": false,
  "message": "Demande d'enlèvement trouvée avec succès",
  "result": {
    "pickup": "683377360858",
    "link": "https://www.firstdeliverygroup.com/api/v2/print-pickup?q=eyji"
  }
}
Version History
Version	Date	Changements
2.0	12 Mai 2023	Base API
2.1	4 Oct 2023	Limitation du prix (≤ 999 DT)
2.2	20 Juin 2024	Ajout pickup + impression décharge
2.3	7 Juillet 2024	Création multiple commandes + impression groupée
2.4	30 Juillet 2024	Annulation multiple commandes ​