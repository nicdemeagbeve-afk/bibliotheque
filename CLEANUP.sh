#!/bin/bash

# ========================================
# Script de Nettoyage et Restructuration
# Bibliothèque Numérique - 2026
# ========================================

echo "🧹 Nettoyage du projet..."

# 1. Supprimer les fichiers SQL obsolètes (garder seulement SETUP_COMPLET.sql)
echo "❌ Suppression des fichiers SQL obsolètes..."
rm -f /opt/lampp/htdocs../bibliotheques_db.sql
rm -f /opt/lampp/htdocs../db_setup.sql
rm -f /opt/lampp/htdocs../lecteurs.sql
rm -f /opt/lampp/htdocs../liste_lecture.sql
rm -f /opt/lampp/htdocs../alter_pdf_columns.sql

# 2. Supprimer les fichiers PHP redondants
echo "❌ Suppression des fichiers PHP inutilisés..."
rm -f /opt/lampp/htdocs../favorites.php
rm -f /opt/lampp/htdocs../history.php
rm -f /opt/lampp/htdocs../wishlist.php

# 3. Supprimer les fichiers textes inutilisés
echo "❌ Suppression des fichiers textes..."
rm -f /opt/lampp/htdocs../GUIDE_IMAGES_BASE_DE_DONNEES.txt

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
