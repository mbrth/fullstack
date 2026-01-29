// Vérifier si l'utilisateur est connecté
function checkAuth() {
    const token = getToken();
    if (!token) {
        window.location.href = 'login.html';
        return false;
    }
    return true;
}

// Déconnexion
async function logout() {
    try {
        await authAPI.logout();
    } catch (error) {
        console.error('Erreur lors de la déconnexion:', error);
    } finally {
        removeToken();
        removeUser();
        window.location.href = 'login.html';
    }
}

// Afficher une alerte
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;

    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);

        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}

// Afficher les erreurs de validation
function displayValidationErrors(errors) {
    if (typeof errors === 'object') {
        const messages = Object.values(errors).flat();
        messages.forEach(msg => showAlert(msg, 'error'));
    } else if (typeof errors === 'string') {
        showAlert(errors, 'error');
    }
}

// Créer la navigation
function createNavbar(activePage = '') {
    const user = getUser();
    const userName = user ? user.name : 'Utilisateur';

    const navbarElement = document.getElementById('navbar');
    if (!navbarElement) return;

    navbarElement.innerHTML = `
        <h1>📊 Analyse d'Avis</h1>
        <div class="nav-links">
            <a href="dashboard.html" ${activePage === 'dashboard' ? 'class="active"' : ''}>Tableau de bord</a>
            <a href="reviews.html" ${activePage === 'reviews' ? 'class="active"' : ''}>Avis</a>
            <a href="add-review.html" ${activePage === 'add-review' ? 'class="active"' : ''}>Ajouter un avis</a>
            <span style="color: #667eea; font-weight: 500;">👤 ${userName}</span>
            <button onclick="logout()">🚪 Déconnexion</button>
        </div>
    `;
}

// Formater une date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Obtenir la classe CSS pour un sentiment
function getSentimentClass(sentiment) {
    const classes = {
        'positive': 'badge-positive',
        'neutral': 'badge-neutral',
        'negative': 'badge-negative'
    };
    return classes[sentiment] || 'badge-neutral';
}

// Obtenir le label pour un sentiment
function getSentimentLabel(sentiment) {
    const labels = {
        'positive': '✓ Positif',
        'neutral': '~ Neutre',
        'negative': '✗ Négatif'
    };
    return labels[sentiment] || sentiment;
}

// Obtenir le label pour un topic
function getTopicLabel(topic) {
    const labels = {
        'delivery': 'Livraison',
        'price': 'Prix',
        'quality': 'Qualité',
        'service': 'Service',
        'speed': 'Rapidité',
        'packaging': 'Emballage',
        'ease_of_use': 'Facilité d\'utilisation'
    };
    return labels[topic] || topic;
}

