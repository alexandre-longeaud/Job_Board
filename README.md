# Présentation du projet
Site ou l'on trouve des annonces d'offre d'emploi.

# Fonctionnalités 

- Consulter
- Ajouter
- Modifier 
- Supprimer une offre

Egalement un sytème de pagination (sans bundle), mis en plage pour consulter l'intégralité des offres.

# Front-end

- Tailwind (via CDN)

# Back-end

- Framework Symfony (PHP)
- Base de données MySQL

# Notions abordées

- Création des entités et d'une base de données avec le `Maker` de Symfony
- Gestions des formulaires avec le `Builder`, et notion de CSRF pour la sécurité (injection SQL)
-  CRUD sur l'entité Offre `Create`, `Read`, `Update`, `Delete`
- Mise en place des fixtures avec l'utilisation de `Faker` pour générer des données cohérente.
- Gestion de la vue avec `Twig`