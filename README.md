# 📚 Système de Gestion des Emplois du Temps

> **Application Laravel 11** pour la gestion complète des emplois du temps dans les établissements d'enseignement

## 🎯 Vue d'Ensemble

Ce système permet de gérer efficacement les emplois du temps avec :
- **Gestion multi-niveaux** : Départements → Spécialités → Classes
- **Association professeurs-matières** par classe et trimestre
- **Horaires multiples** pour chaque cours
- **Validation avancée** des conflits d'horaires
- **Interface moderne** avec Select2 et Bootstrap 5
- **Localisation française** complète

## 🚀 Installation Rapide

### Prérequis
- PHP 8.1+
- Composer
- MySQL 5.7+
- Node.js & NPM

### Étapes d'Installation
```bash
# 1. Cloner le projet
git clone [votre-repo]
cd gestion-des-emplois

# 2. Installer les dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Base de données
php artisan migrate --seed

# 5. Assets
npm run build

# 6. Lancer le serveur
php artisan serve
```

## 📋 Configuration Obligatoire

⚠️ **IMPORTANT** : Respecter cet ordre pour éviter les erreurs !

### Phase 1 : Structure Académique
1. **Années Scolaires** → Activer l'année courante
2. **Départements** → Créer les départements
3. **Niveaux de Formation** → L1, L2, L3, M1, M2...
4. **Spécialités** → Associer aux départements et niveaux

### Phase 2 : Organisation
5. **Classes** → Créer par spécialité et niveau
6. **Trimestres** → T1, T2, T3 pour l'année active
7. **Professeurs** → Ajouter le personnel enseignant
8. **Matières** → Créer par spécialité

### Phase 3 : Associations
9. **Jours** → Lundi à Samedi
10. **Horaires** → Créneaux horaires (8h-9h, 9h-10h...)
11. **Salles de Classe** → Espaces disponibles
12. **Professeurs-Matières** → Associations par classe/trimestre

### Phase 4 : Emplois du Temps
13. **Création Emplois** → Planification finale

## 🎯 Fonctionnalités Principales

### ✅ Gestion des Emplois du Temps
- **Création assistée** avec validation automatique
- **Horaires multiples** par cours
- **Prévention des conflits** (professeur, salle, classe)
- **Interface intuitive** avec sélection dynamique

### ✅ Validation Avancée
- **Professeur unique** : Pas de double réservation
- **Salle disponible** : Pas de conflit de local
- **Cohérence pédagogique** : Respect des associations professeur-matière

### ✅ Interface Moderne
- **Select2** pour les sélections multiples
- **AJAX** pour le chargement dynamique
- **Bootstrap 5** responsive
- **Messages d'erreur** en français

## 📚 Documentation Complète

### Fichiers Inclus
1. **`README.md`** → Ce guide de démarrage
2. **`GUIDE_CONFIGURATION.md`** → Guide détaillé de configuration
3. **`DOCUMENTATION_TECHNIQUE.md`** → Détails techniques approfondis

## 🤝 Support et Contribution

### Problèmes Courants
1. **Professeurs non chargés** → Vérifier les associations professeur-matière
2. **Erreur de routes** → Nettoyer le cache des routes
3. **Select2 non fonctionnel** → Vérifier l'inclusion des scripts

---

**🎓 Système de Gestion des Emplois du Temps** - *Solution complète pour l'enseignement supérieur*

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
