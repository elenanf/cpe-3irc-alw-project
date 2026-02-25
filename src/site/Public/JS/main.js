/**
 * main.js - Squelette pour démarrer
 * Ce script est à intégrer dans votre page dashboard.php
 */

document.addEventListener("DOMContentLoaded", () => {
    // Rappel : URL vers le webservice
    const API_URL = "http://localhost:50181/";

    // TODO: Récupérer le nom du joueur dynamiquement (ex: data-attribute sur le body)
    const username = "bean"; // À remplacer

    const engine = new FermeEngine();

    // Chargement de l'état
    engine.onLoadState(() => {
        console.log(`Rafraîchissement de l'état global du jeu`);

        fetch(API_URL + "api/players/" + username + "/inventory")
            .then((response) => response.json())
            .then((data) => {

                console.log(Object.entries(data))
                inventory = document.getElementById("inventory");
                Object.entries(data).forEach((stock) => {
                    product = document.getElementById("product-" + stock[0])
                    if (product) {
                        product.getElementsByClassName("stock")[0].textContent = stock[1]
                    }
                })
            });

        fetch(API_URL + "api/players/" + username + "/buildings")
            .then((response) => response.json())
            .then((data) => {
                engine.renderBuildings(data)
            });

    });

    // Au clic sur "Récolter"
    engine.onHarvest((buildingId) => {
        console.log(`Récolte demandée sur ${buildingId}`);
        // TODO: coder l'appel API pour récolter/produire une ressource depuis un bâtiment donné
        // IMPORTANT: Retournez la promesse du fetch avec le mot clé 'return'
        // ex: return fetch(...);
    });

    // Au clic sur "Améliorer"
    engine.onUpgrade((buildingId) => {
        console.log(`Amélioration demandée sur ${buildingId}`);
        // TODO: Coder l'appel API pour augmenter le niveau d'un bâtiment donné
        // IMPORTANT: Retournez la promesse du fetch avec le mot clé 'return'
        // ex: return fetch(...);
    });

    // Démarrage
    engine.init(); // Ceci appellera votre onLoadState une première fois
});
