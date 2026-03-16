/**
 * main.js - Squelette pour démarrer
 * Ce script est à intégrer dans votre page dashboard.php
 */

document.addEventListener("DOMContentLoaded", () => {
    // Rappel : URL vers le webservice
    const API_URL = "http://localhost:50181/";

    const username = document.body.dataset.login;

    const engine = new FermeEngine();

    // Chargement de l'état
    engine.onLoadState(() => {
        fetch(API_URL + "api/players/" + username + "/inventory")
            .then((response) => response.json())
            .then((data) => {

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
    engine.onHarvest((buildingName) => {
        return fetch(API_URL + "api/players/" + username + "/harvest/" + buildingName, {
            method: "POST"
        })
    });

    // Au clic sur "Améliorer"
    engine.onUpgrade((buildingName) => {
        console.log(`Amélioration demandée sur ${buildingName}`);
        // TODO: Coder l'appel API pour augmenter le niveau d'un bâtiment donné
        // IMPORTANT: Retournez la promesse du fetch avec le mot clé 'return'
        // ex: return fetch(...);

        return fetch(API_URL + "api/players/" + username + "/upgrade/" + buildingName, {
            method: "POST"
        })
    });

    // Démarrage
    engine.init(); // Ceci appellera votre onLoadState une première fois
});
