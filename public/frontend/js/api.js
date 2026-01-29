// Configuration de l'API
// Utiliser l'origine actuelle pour éviter les problèmes CORS
const API_BASE_URL = window.location.origin + '/api';

// Fonction pour récupérer le token
function getToken() {
    return localStorage.getItem('token');
}

// Fonction pour sauvegarder le token
function saveToken(token) {
    localStorage.setItem('token', token);
}

// Fonction pour supprimer le token
function removeToken() {
    localStorage.removeItem('token');
}

// Fonction pour sauvegarder les infos utilisateur
function saveUser(user) {
    localStorage.setItem('user', JSON.stringify(user));
}

// Fonction pour récupérer les infos utilisateur
function getUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
}

// Fonction pour supprimer les infos utilisateur
function removeUser() {
    localStorage.removeItem('user');
}

// Fonction générique pour les appels API
async function apiCall(endpoint, method = 'GET', data = null) {
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    const token = getToken();
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const options = {
        method,
        headers,
    };

    if (data && method !== 'GET') {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, options);
        const result = await response.json();

        if (!response.ok) {
            if (response.status === 401) {
                // Token invalide, rediriger vers login
                removeToken();
                removeUser();
                window.location.href = 'login.html';
            }
            throw result;
        }

        return result;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// API Auth
const authAPI = {
    async register(name, email, password, passwordConfirmation) {
        return await apiCall('/register', 'POST', {
            name,
            email,
            password,
            password_confirmation: passwordConfirmation,
        });
    },

    async login(email, password) {
        return await apiCall('/login', 'POST', { email, password });
    },

    async logout() {
        return await apiCall('/logout', 'POST');
    },

    async getUser() {
        return await apiCall('/user', 'GET');
    },
};

// API Reviews
const reviewsAPI = {
    async getAll(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        return await apiCall(`/reviews${queryString ? '?' + queryString : ''}`, 'GET');
    },

    async getOne(id) {
        return await apiCall(`/reviews/${id}`, 'GET');
    },

    async create(content) {
        return await apiCall('/reviews', 'POST', { content });
    },

    async update(id, content) {
        return await apiCall(`/reviews/${id}`, 'PUT', { content });
    },

    async delete(id) {
        return await apiCall(`/reviews/${id}`, 'DELETE');
    },

    async reanalyze(id) {
        return await apiCall(`/reviews/${id}/reanalyze`, 'POST');
    },

    async analyze(text) {
        return await apiCall('/analyze', 'POST', { text });
    },
};

// API Analyze
const analyzeAPI = {
    async analyze(text) {
        return await apiCall('/analyze', 'POST', { text });
    },
};

// API Dashboard
const dashboardAPI = {
    async getStats() {
        return await apiCall('/dashboard/stats', 'GET');
    },
};

