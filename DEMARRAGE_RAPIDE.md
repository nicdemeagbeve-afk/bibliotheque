# 🚀 GUIDE D'INSTALLATION RAPIDE - Bibliothèque Numérique 2026

## ⏱️ 5 Minutes pour démarrer!

### Étape 1️⃣: Créer la Base de Données (1 minute)

```
1. Ouvrez votre navigateur → http://localhost/phpmyadmin
2. Connectez-vous (user: root, password: vide)
3. Allez dans l'onglet "SQL" en haut
4. Ouvrez le fichier: sql/SETUP_COMPLET.sql
5. Copiez TOUT le contenu
6. Collez dans la zone SQL de phpMyAdmin
7. Cliquez sur "Exécuter" (bouton bleu)
```

**Résultat**: ✅ Base de données `bibliotheques_db` créée avec 5 tables!

---

### Étape 2️⃣: Accéder à l'Application (instant!)

```
Ouvrez: http://localhost/revisionphp
```

**Vous devriez voir**: 
- 📚 Page d'accueil avec carousel
- 📖 Bouton "🔑 Mode admin"
- ❤️ Section favoris
- 📋 Historique

---

### Étape 3️⃣: Accéder au Mode Admin (1 minute)

```
1. Cliquez sur "🔑 Mode admin" en haut à droite
2. Cliquez sur "✅ Confirmer"
3. Vous êtes maintenant ADMIN! 🎉
```

**Vous pouvez maintenant**:
- ➕ Ajouter des livres (avec image et PDF)
- ✏️ Modifier des livres
- 🗑️ Supprimer des livres

---

### Étape 4️⃣: Ajouter un Livre (2 minutes)

```
1. Cliquez sur "➕ Ajouter"
2. Remplissez le formulaire:
   - Titre: "Mon Livre"
   - Auteur: "Votre Nom"
   - Maison d'édition: "Édition"
   - Nombre d'exemplaires: "5"
   - Description: "Description du livre"
3. Sélectionnez une IMAGE (max 20MB)
4. (Optionnel) Sélectionnez un PDF (max 100MB)
5. Cliquez sur "✅ Ajouter le livre"
```

**Résultat**: ✅ Livre ajouté! Visible sur l'accueil!

---

## 🎯 Fonctionnalités Principales

### Pour TOUS les utilisateurs
- 🔍 Chercher des livres
- 📚 Consulter le catalogue
- ❤️ Ajouter aux favoris (localStorage)
- 📖 Lire les PDFs en ligne
- 📊 Voir l'historique

### Pour les ADMINS
- ➕ Créer des livres
- ✏️ Éditer des livres  
- 🗑️ Supprimer des livres
- 📤 Upload images/PDFs

---

## ⚙️ Configuration (Si besoin)

### Connexion à la Base de Données
Fichier: `admin/connexion.php`

Par défaut:
```
Host: 127.0.0.1
User: root
Password: (vide)
Database: bibliotheques_db
```

Si ce n'est pas correct, modifiez ces valeurs.

---

## 🐛 Troubleshooting

| Problème | Solution |
|----------|----------|
| "Connection refused" | Vérifiez que MySQL est démarré |
| Images ne s'affichent pas | Augmentez `max_allowed_packet` à 256M |
| Upload échoue | Vérifiez `upload_max_filesize` = 120M |
| Page 404 | Vérifiez que Apache est démarré |

---

## 📞 Besoin d'Aide?

- 📖 Consultez: `/README.md`
- ❓ FAQ: `/faq.php`
- ℹ️ À propos: `/apropos.php`

---

## ✅ Checklist de Vérification

- [ ] Base de données créée avec 5 tables
- [ ] Accueil charge correctement  
- [ ] Mode admin fonctionne
- [ ] Un livre peut être créé
- [ ] Les images s'affichent
- [ ] Les PDFs peuvent être lus
- [ ] Les favoris fonctionnent

---

**🎉 Félicitations! Votre bibliothèque numérique est prête! 🎉**

