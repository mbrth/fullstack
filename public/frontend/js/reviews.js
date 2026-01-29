// Ce fichier contient les fonctions utilitaires pour la page des reviews
// Les fonctions principales sont déjà dans reviews.html

// Fonction pour tronquer le texte
function truncateText(text, maxLength = 200) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}
