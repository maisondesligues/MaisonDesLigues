use mdl;

#Hotels
Insert into hotel (pnom, adresse1, cp, ville, tel, mail)
Values ("ibis Styles Lille Centre Gare Beffroi", "172 Rue Pierre Mauroy", 59000, "Lille", "0320300054", "H1384@accor.com");

Insert into hotel (pnom, adresse1, cp, ville, tel, mail)
Values ("Ibis Budget Lille Gares Vieux-Lille", "10, Rue De Courtrai", 59000, "Lille", "0892683078", "H5208@accor.com");

#Catégories Chambres
Insert into categorie_chambre (libelle_categorie)
Values ("Single");

Insert into categorie_chambre (libelle_categorie)
Values ("Double");

#Proposer
Insert into proposer (tarif_nuite)
Values (95);

Insert into proposer (tarif_nuite)
Values (105);

Insert into proposer (tarif_nuite)
Values (70);

Insert into proposer (tarif_nuite)
Values (80);

#Thèmes
-- Thèmes liés à l'atelier 1
INSERT INTO theme (id, libelle) VALUES (1,'Diagnostic et identification des critères du club');
INSERT INTO theme (id, libelle) VALUES (2,'Analyse systémique de l’environnement et méthodologie de mise en œuvre du projet');
INSERT INTO theme (id, libelle) VALUES (3,'Actions solidaires et innovantes');
INSERT INTO theme (id, libelle) VALUES (4,'Financements');
INSERT INTO theme (id, libelle) VALUES (5,'Outils et documentation');
INSERT INTO theme (id, libelle) VALUES (6,'Valoriser et communiquer sur le projet');

-- Thèmes liés à l'atelier 2
INSERT INTO theme (id, libelle) VALUES (7,'Création – Obligations légales');
INSERT INTO theme (id, libelle) VALUES (8,'Gestion du personnel, de la structure et des conflits');
INSERT INTO theme (id, libelle) VALUES (9,'Relations internes, externes et avec le Comité départemental, la Ligue et la Fédération');
INSERT INTO theme (id, libelle) VALUES (10,'Conventions');
INSERT INTO theme (id, libelle) VALUES (11,'Partenariats');

-- Thèmes liés à l'atelier 3
INSERT INTO theme (id, libelle) VALUES (12,'Logiciel FFE de gestion des compétitions (présentation et formation)');
INSERT INTO theme (id, libelle) VALUES (13,'Présentation du document « L’arbitrage en images »');
INSERT INTO theme (id, libelle) VALUES (14,'Plaquette & guide projet du club');
INSERT INTO theme (id, libelle) VALUES (15,'Labelisation du club');
INSERT INTO theme (id, libelle) VALUES (16,'Aménagement des équipements');
INSERT INTO theme (id, libelle) VALUES (17,'Assurances');

-- Thèmes liés à l'atelier 4
INSERT INTO theme (id, libelle) VALUES (18,'Observations et analyses sur l’encadrement actuel');
INSERT INTO theme (id, libelle) VALUES (19,'Propositions de nouveaux schémas d’organisation');
INSERT INTO theme (id, libelle) VALUES (20,'Profils types et pratiques innovantes');
INSERT INTO theme (id, libelle) VALUES (21,'Critères et seuils nécessaires à la pérennité de l’emploi');
INSERT INTO theme (id, libelle) VALUES (22,'Exercice du métier d’enseignant (avantages et inconvénients)');

-- Thèmes liés à l'atelier 5
INSERT INTO theme (id, libelle) VALUES (23,'Présentation');
INSERT INTO theme (id, libelle) VALUES (24,'Fonctionnement');
INSERT INTO theme (id, libelle) VALUES (25,'Objectifs');
INSERT INTO theme (id, libelle) VALUES (26,'Nouveaux diplômes');
INSERT INTO theme (id, libelle) VALUES (27,'Financements');

-- Thèmes liés à l'atelier 6
INSERT INTO theme (id,libelle) VALUES (28,'Les enjeux climatiques, énergétiques et économiques');
INSERT INTO theme (id,libelle) VALUES (29,'Sport et développement durable');
INSERT INTO theme (id, libelle) VALUES (30,'Démarche fédérale');
INSERT INTO theme (id, libelle) VALUES (31,'Échange');
select * from theme;

# Ateliers
INSERT INTO atelier (id, libelle) VALUES (1, 'Le club et son projet');
INSERT INTO atelier (id, libelle) VALUES (2, 'Le fonctionnement du club');
INSERT INTO atelier (id, libelle) VALUES (3, 'Les outils à disposition et remis aux clubs');
INSERT INTO atelier (id, libelle) VALUES (4, 'Observatoire des métiers de l’escrime');
INSERT INTO atelier (id, libelle) VALUES (5, 'I.F.F.E');
INSERT INTO atelier (id, libelle) VALUES (6, 'Développement durable');

#ManyToMany ATELIER-THEME
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (1, 1);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (1, 2);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (1, 4);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (1, 5);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (1, 6);

INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (2, 7);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (2, 8);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (2, 9);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (2, 10);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (2, 11);

INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (3, 12);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (3, 13);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (3, 14);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (3, 15);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (3, 16);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (3, 17);

INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (4, 18);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (4, 19);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (4, 20);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (4, 21);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (4, 22);

INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (5, 23);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (5, 24);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (5, 25);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (5, 26);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (5, 27);

INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (6, 28);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (6, 29);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (6, 30);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (6, 31);

select * from atelier;
select * from hotel;
select * from theme;

