# 📚 Bibliothèque Numérique - Documentation du Projet

## 🎯 Vue d'ensemble
Plateforme complète de gestion de bibliothèque numérique avec:
- Catalogue de livres avec images et PDFs
- Lecteur PDF intégré avec zoom et pagination
- Système de favoris et historique
- Admin panel pour CRUD des livres
- Interface responsive (mobile-friendly)

## 📁 Structure du Projet

```
revisionphp/
├── sql/
│   └── SETUP_COMPLET.sql          ← 🔧 FICHIER À EXÉCUTER DANS phpMyAdmin
│
├── admin/                          ← Backend (créer, éditer, supprimer)
│   ├── connexion.php               (Connexion base de données)
│   ├── create.php                  (Créer un livre)
│   ├── edit.php                    (Modifier un livre)
│   ├── delete.php                  (Supprimer un livre)
│   ├── get_image.php               (Servir les images BLOB)
│   └── get_pdf.php                 (Servir les PDFs BLOB)
│
├── includes/                       ← Fichiers d'inclusion réutilisables
│   └── header.php                  (En-tête commun)
│
├── pages/                          ← Pages statiques et principales
│   ├── faq.php                     (Questions fréquentes)
│   ├── apropos.php                 (À propos)
│   └── conditions.php              (Conditions d'utilisation)
│
├── css/
│   └── style.css                   (Styles principaux - responsive)
│
├── images/                         ← Images statiques
│
├── assets/                         ← Ressources (peut inclure JS, fonts)
│
├── 📄 Fichiers racine (proxies pour compatibilité)
│   ├── index.php                   (Accueil)
│   ├── liste.php                   (Catalogue)
│   ├── detail.php                  (Détail d'un livre)
│   ├── result.php                  (Résultats de recherche)
│   ├── create.php                  (Proxy vers admin/create.php)
│   ├── edit.php                    (Proxy vers admin/edit.php)
│   ├── delete.php                  (Proxy vers admin/delete.php)
│   ├── get_image.php               (Proxy vers admin/get_image.php)
│   ├── get_pdf.php                 (Proxy vers admin/get_pdf.php)
│   ├── connexion.php               (Proxy vers admin/connexion.php)
│   ├── liste_lecture.php           (API - Gestion de la lecture)
│   ├── faq.php                     (FAQ)
│   ├── apropos.php                 (À propos)
│   ├── conditions.php              (Conditions)
│   └── admin_toggle.php            (Mode admin)
│
└── 📄 Documentation
    └── README.md                   (Ce fichier)
```

## 🚀 Installation et Configuration

### Étape 1: Créer la Base de Données
1. Ouvrez **phpMyAdmin** (http://localhost/phpmyadmin)
2. Allez dans l'onglet **SQL**
3. Ouvrez le fichier `/sql/SETUP_COMPLET.sql`
4. Copiez tout le contenu
5. Collez dans phpMyAdmin
6. Cliquez sur **Exécuter**
7. ✅ Base de données créée!

### Étape 2: Configurer la Connexion
Le fichier `/admin/connexion.php` contient les paramètres de connexion:
```php
$con = new mysqli("127.0.0.1", "root", "", "bibliotheques_db");
```
Modifiez si nécessaire selon votre configuration MySQL.

### Étape 3: Vérifier l'Installation
- Accédez à http://localhost:3000
- Vous devriez voir la page d'accueil
- Cliquez sur "🔑 Mode admin" pour accéder au panel d'administration

## 🗄️ Schéma de Base de Données

### Table: `livres`
| Colonne | Type | Description |
|---------|------|-------------|
| `id` | INT | Clé primaire (AUTO_INCREMENT) |
| `titre` | VARCHAR(100) | Titre du livre |
| `auteur` | VARCHAR(100) | Auteur du livre |
| `description` | TEXT | Description complète |
| `maison_edition` | VARCHAR(100) | Éditeur |
| `nombre_exemplaire` | INT | Quantité disponible |
| `image_data` | LONGBLOB | Image du livre (stockée en base) |
| `image_type` | VARCHAR(50) | Type MIME de l'image |
| `pdf_data` | LONGBLOB | Fichier PDF (stocké en base) |
| `pdf_type` | VARCHAR(50) | Type MIME du PDF |
| `date_creation` | TIMESTAMP | Date d'ajout |

### Table: `lecteurs`
| Colonne | Type | Description |
|---------|------|-------------|
| `id_lecteur` | INT | Clé primaire |
| `nom_lecteur` | VARCHAR(100) | Nom complet |
| `email` | VARCHAR(100) | Email (unique) |
| `date_inscription` | TIMESTAMP | Date d'inscription |

### Table: `liste_lecture`
Stocke les livres dans la liste de lecture du lecteur.
Relation: `livres` ← `liste_lecture` → `lecteurs`

### Table: `favoris`
Stocke les livres favoris. Contrainte unique sur (`id_livre`, `id_lecteur`).

### Table: `historique`
Enregistre chaque consultation de livre.

## 📋 Fonctionnalités

### Pour tous les utilisateurs
- ✅ Consulter le catalogue de livres
- ✅ Voir les détails d'un livre
- ✅ Lire le PDF en ligne (avec zoom, pagination)
- ✅ Ajouter des livres aux favoris
- ✅ Consulter l'historique de lecture
- ✅ Chercher des livres par titre/auteur
- ✅ Consulter FAQ, À propos, Conditions

### Pour les admins
- ✅ Créer un livre (avec image 20MB max et PDF 100MB max)
- ✅ Éditer un livre existant
- ✅ Supprimer un livre
- ✅ Gérer les uploads (BLOB dans la base de données)

## 🔒 Sécurité

- ✅ Prepared statements pour toutes les requêtes SQL
- ✅ Stockage des fichiers en BLOB (pas d'accès direct au serveur)
- ✅ Validation des types de fichiers
- ✅ Limites de taille (images 20MB, PDFs 100MB)
- ✅ Session-based admin mode

## ⚙️ Configuration Serveur Recommandée

```ini
# php.ini
upload_max_filesize = 120M
post_max_size = 120M
max_execution_time = 300

# my.cnf ou my.ini
max_allowed_packet = 256M
```

## 🎨 Style et Design

- Framework: CSS pur (responsive)
- Breakpoints: Mobile (< 600px), Tablet (600-1024px), Desktop (> 1024px)
- Couleurs primaires: Or (#f59e0b), Bleu (#3498db)
- Animations: Transitions fluides, slideIn/fadeIn au chargement

## 🔗 Endpoints API

### GET
- `/get_image.php?id=X` - Servir l'image du livre X
- `/get_pdf.php?id=X` - Servir le PDF du livre X

### POST
- `/liste_lecture.php` - Ajouter/Supprimer de la liste de lecture
  ```json
  { "action": "add|remove", "bookId": X, "lecteurId": Y }
  ```

## 🐛 Dépannage

**Problème**: Les images/PDFs ne s'affichent pas
- Solution: Vérifiez que `max_allowed_packet` est ≥ 256M

**Problème**: "Connection refused"
- Solution: Vérifiez que MySQL est démarré et les paramètres de connexion

**Problème**: Les fichiers uploadés ne sont pas sauvegardés
- Solution: Vérifiez `upload_max_filesize` et `post_max_size` en php.ini

## 📞 Support

Pour plus d'informations, consultez:
- FAQ: `/faq.php`
- À Propos: `/apropos.php`
- Conditions: `/conditions.php`

---

**Version**: 1.0  
**Dernière mise à jour**: 3 Janvier 2026  
**Statut**: ✅ Production Ready
