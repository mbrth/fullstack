# 📊 Plateforme d'Analyse d'Avis Clients

Une application complète permettant aux entreprises d'analyser automatiquement les avis de leurs clients avec l'intelligence artificielle.

## 🚀 Technologies Utilisées

### Backend
- **Laravel 12** - Framework PHP
- **Laravel Sanctum** - Authentification API
- **SQLite** - Base de données
- **Service IA personnalisé** - Analyse de sentiment, calcul de score et détection de thèmes

### Frontend
- **HTML5/CSS3/JavaScript** - Interface utilisateur
- **Fetch API** - Communication avec l'API REST

## ✨ Fonctionnalités

### Authentification
- ✅ Inscription d'utilisateur
- ✅ Connexion/Déconnexion
- ✅ Gestion des rôles (admin/user)
- ✅ Authentification par token (Sanctum)

### Gestion des Avis
- ✅ Création d'avis avec analyse IA automatique
- ✅ Liste des avis avec pagination
- ✅ Filtres par sentiment
- ✅ Tri par date ou score
- ✅ Modification d'avis (propriétaire ou admin)
- ✅ Suppression d'avis (propriétaire ou admin)

### Analyse IA (SentimentAnalysisService)
- ✅ **Analyse de sentiment** (positif, neutre, négatif)
- ✅ **Calcul de score** (0-100) basé sur :
  - Ratio mots positifs/négatifs
  - Longueur du texte
  - Ponctuation
  - Mots intensificateurs
- ✅ **Détection de thèmes** :
  - Livraison (delivery)
  - Prix (price)
  - Qualité (quality)
  - Service client (service)
  - Produit (product)

### Tableau de Bord
- ✅ Statistiques globales (total avis, score moyen)
- ✅ Distribution des sentiments
- ✅ Top 5 des thèmes les plus mentionnés
- ✅ Derniers avis publiés

## 📁 Structure du Projet

```
project_final/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # Authentification
│   │   │   ├── ReviewController.php     # Gestion des avis
│   │   │   ├── AnalyzeController.php    # Analyse IA
│   │   │   └── DashboardController.php  # Statistiques
│   │   └── Requests/
│   │       ├── LoginRequest.php
│   │       ├── RegisterRequest.php
│   │       ├── ReviewRequest.php
│   │       └── AnalyzeRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   └── Review.php
│   └── Services/
│       └── SentimentAnalysisService.php # Service IA (rule-based)
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   └── create_reviews_table.php
│   ├── factories/
│   │   └── ReviewFactory.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── public/
│   └── frontend/
│       ├── css/
│       │   └── style.css
│       ├── js/
│       │   ├── api.js                   # Appels API
│       │   ├── auth.js                  # Fonctions d'authentification
│       │   ├── reviews.js               # Gestion des avis
│       │   └── dashboard.js             # Tableau de bord
│       ├── index.html                   # Login/Register avec bascule
│       ├── dashboard.html
│       ├── reviews.html
│       └── add-review.html              # Création d'avis
├── routes/
│   ├── api.php                          # Routes API
│   └── web.php
└── config/
    ├── cors.php                         # Configuration CORS
    └── sanctum.php
```

## 🛠️ Installation

### Prérequis
- PHP >= 8.2
- Composer
- SQLite (ou MySQL/PostgreSQL)

### Étapes d'installation

1. **Cloner le projet**
```bash
cd D:\Web\project_final
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Créer la base de données**
```bash
# La base de données SQLite est déjà créée
# Pour recréer : touch database/database.sqlite
```

5. **Exécuter les migrations et seeders**
```bash
php artisan migrate:fresh --seed
```

6. **Démarrer le serveur**
```bash
php artisan serve
```

Le backend sera accessible sur : `http://localhost:8000`

## 🌐 Accès au Frontend

Ouvrez votre navigateur et accédez à :
```
http://localhost:8000/frontend/index.html
```
ou avec Live Server :
```
http://127.0.0.1:5500/public/frontend/index.html
```

### Comptes de test

**Utilisateur normal :**
- Email : `test@example.com`
- Mot de passe : `password`

**Administrateur :**
- Email : `admin@example.com`
- Mot de passe : `password`

## 📡 API Endpoints

### Authentification

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/api/register` | Inscription |
| POST | `/api/login` | Connexion |
| POST | `/api/logout` | Déconnexion |
| GET | `/api/user` | Utilisateur connecté |

### Avis

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/reviews` | Liste des avis |
| POST | `/api/reviews` | Créer un avis |
| GET | `/api/reviews/{id}` | Détail d'un avis |
| PUT | `/api/reviews/{id}` | Modifier un avis |
| DELETE | `/api/reviews/{id}` | Supprimer un avis |
| POST | `/api/reviews/{id}/reanalyze` | Ré-analyser un avis |

### Analyse

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/api/analyze` | Analyser un texte |

### Tableau de bord

| Méthode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/dashboard/stats` | Statistiques globales |

## 🧪 Tests avec les données générées

Le seeder a créé :
- 1 administrateur
- 1 utilisateur de test
- 5 utilisateurs supplémentaires
- 60 avis au total (répartis entre les utilisateurs)

## 🎨 Personnalisation

### Modifier les mots-clés de l'IA

Éditez le fichier `app/Services/AIAnalysisService.php` pour personnaliser :
- Les mots positifs et négatifs
- Les mots intensificateurs
- Les thèmes et leurs mots-clés associés

### Modifier le design

Éditez le fichier `public/frontend/css/style.css` pour personnaliser l'apparence.

## 📝 Utilisation

1. **Connexion** : Utilisez un compte existant ou créez-en un nouveau
2. **Créer un avis** : Cliquez sur "Créer un avis" et écrivez votre texte
3. **Analyse automatique** : L'IA analyse automatiquement le sentiment, calcule le score et détecte les thèmes
4. **Consulter les statistiques** : Accédez au tableau de bord pour voir les statistiques globales
5. **Filtrer et trier** : Utilisez les filtres pour trouver des avis spécifiques

## 🔒 Sécurité

- ✅ Mots de passe hashés avec bcrypt
- ✅ Authentification par token (Sanctum)
- ✅ Validation des données (Form Requests)
- ✅ Protection CORS configurée
- ✅ Middleware d'authentification
- ✅ Autorisation pour modification/suppression

## 🚀 Améliorations Futures Possibles

- [ ] Intégration d'une API IA externe (OpenAI, HuggingFace)
- [ ] Graphiques interactifs (Chart.js)
- [ ] Export des données (CSV, PDF)
- [ ] Notifications par email
- [ ] Multi-langues
- [ ] Mode sombre
- [ ] Tests automatisés (PHPUnit, Pest)

## 📄 Licence

Ce projet est développé à des fins éducatives.

## 👨‍💻 Support

Pour toute question ou problème, veuillez créer une issue sur le dépôt du projet.

---

**Développé avec ❤️ en utilisant Laravel 12 et JavaScript**

