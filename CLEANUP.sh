#!/bin/bash

# ========================================
# Script de Nettoyage et Restructuration
# Bibliothèque Numérique - 2026
# ========================================

echo "🧹 Nettoyage du projet..."

# 1. Supprimer les fichiers SQL obsolètes (garder seulement SETUP_COMPLET.sql)
echo "❌ Suppression des fichiers SQL obsolètes..."
rm -f /opt/lampp/htdocs/revisionphp/bibliotheques_db.sql
rm -f /opt/lampp/htdocs/revisionphp/db_setup.sql
rm -f /opt/lampp/htdocs/revisionphp/lecteurs.sql
rm -f /opt/lampp/htdocs/revisionphp/liste_lecture.sql
rm -f /opt/lampp/htdocs/revisionphp/alter_pdf_columns.sql

# 2. Supprimer les fichiers PHP redondants
echo "❌ Suppression des fichiers PHP inutilisés..."
rm -f /opt/lampp/htdocs/revisionphp/favorites.php
rm -f /opt/lampp/htdocs/revisionphp/history.php
rm -f /opt/lampp/htdocs/revisionphp/wishlist.php

# 3. Supprimer les fichiers textes inutilisés
echo "❌ Suppression des fichiers textes..."
rm -f /opt/lampp/htdocs/revisionphp/GUIDE_IMAGES_BASE_DE_DONNEES.txt

echo ""
echo "✅ Nettoyage terminé!"
echo ""
echo "📁 Structure organisée:"
echo "   ├── sql/SETUP_COMPLET.sql (Base de données unique)"
echo "   ├── admin/ (Backend)"
echo "   ├── css/ (Styles)"
echo "   ├── includes/ (Fichiers réutilisables)"
echo "   ├── pages/ (Pages statiques)"
echo "   ├── assets/ (Ressources)"
echo "   ├── config.php (Configuration centralisée)"
echo "   ├── README.md (Documentation)"
echo "   └── Fichiers PHP racine (proxies)"
echo ""
echo "📚 Documentation:"
echo "   ✅ README.md - Guide complet du projet"
echo "   ✅ sql/SETUP_COMPLET.sql - Base de données (à exécuter dans phpMyAdmin)"
echo ""
echo "🎉 Projet prêt pour la production!"
