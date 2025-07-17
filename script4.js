/*Attend que le contenu de la page HTML soit entièrement chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', () => {
    // Appelle la fonction pour récupérer et afficher les données de l'utilisateur
    fetchAndDisplayUserData();
});*/

/**
 * Récupère les données de l'utilisateur (depuis une API) et met à jour le DOM.
 
async function fetchAndDisplayUserData() {
    // Sélectionne l'élément où le nom de l'utilisateur doit être affiché
    const userNameElement = document.getElementById('user-name');*/

    /*try {
        // --- DÉBUT DE LA PARTIE À MODIFIER ---
        // Ceci est une simulation. Dans une application réelle, vous feriez un appel à votre backend ici.
        // Exemple avec l'API fetch :
        // const response = await fetch('/api/user/me'); // Remplacez par l'URL de votre API
        // if (!response.ok) {
        //     throw new Error('Erreur réseau lors de la récupération des données utilisateur');
        // }
        // const userData = await response.json();

        /Simulation de données utilisateur pour l'exemple
        const mockUserData = {
            firstName: "Jean",
            lastName: "Dupont"
        };
        // --- FIN DE LA PARTIE À MODIFIER ---

        // Met à jour le contenu de l'élément avec le nom et prénom de l'utilisateur
        // Remplacez mockUserData par userData si vous utilisez un vrai appel API
        if (mockUserData) {
            userNameElement.textContent = `${mockUserData.firstName} ${mockUserData.lastName}`;
        } else {
             userNameElement.textContent = "Utilisateur"; // Nom par défaut si les données ne sont pas chargées
        }

    } catch (error) {
        console.error("Erreur lors de la récupération des informations de l'utilisateur :", error);
        // Affiche un nom par défaut en cas d'erreur
        userNameElement.textContent = "Visiteur";
    }
}*/
