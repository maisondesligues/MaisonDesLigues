use mdl;

#Hotels
Insert into hotel (id, pnom, adresse1, cp, ville, tel, mail)
Values (1, "ibis Styles Lille Centre Gare Beffroi", "172 Rue Pierre Mauroy", "59000", "Lille", "0320300054", "H1384@accor.com");

Insert into hotel (id, pnom, adresse1, cp, ville, tel, mail)
Values (2, "Ibis Budget Lille Gares Vieux-Lille", "10, Rue De Courtrai", "59000", "Lille", "0892683078", "H5208@accor.com");

#Catégories Chambres
Insert into categorie_chambre (id, libelle_categorie)
Values (1, "Single");

Insert into categorie_chambre (id, libelle_categorie)
Values (2, "Double");

#Proposer
Insert into proposer (hotel_id, categorie_id ,tarif_nuite)
Values (1, 1, 95);

Insert into proposer (hotel_id, categorie_id ,tarif_nuite)
Values (2, 2, 105);

Insert into proposer (hotel_id, categorie_id ,tarif_nuite)
Values (2, 1, 70);

Insert into proposer (hotel_id, categorie_id ,tarif_nuite)
Values (2, 2, 80);

#Thèmes
-- Thèmes liés à l'atelier 1
INSERT INTO theme (libelle) VALUES ('Diagnostic et identification des critères du club');
INSERT INTO theme (libelle) VALUES ('Analyse systémique de l’environnement et méthodologie de mise en œuvre du projet');
INSERT INTO theme (libelle) VALUES ('Actions solidaires et innovantes');
INSERT INTO theme (libelle) VALUES ('Financements');
INSERT INTO theme (libelle) VALUES ('Outils et documentation');
INSERT INTO theme (libelle) VALUES ('Valoriser et communiquer sur le projet');

-- Thèmes liés à l'atelier 2
INSERT INTO theme (libelle) VALUES ('Création – Obligations légales');
INSERT INTO theme (libelle) VALUES ('Gestion du personnel, de la structure et des conflits');
INSERT INTO theme (libelle) VALUES ('Relations internes, externes et avec le Comité départemental, la Ligue et la Fédération');
INSERT INTO theme (libelle) VALUES ('Conventions');
INSERT INTO theme (libelle) VALUES ('Partenariats');

-- Thèmes liés à l'atelier 3
INSERT INTO theme (libelle) VALUES ('Logiciel FFE de gestion des compétitions (présentation et formation)');
INSERT INTO theme (libelle) VALUES ('Présentation du document « L’arbitrage en images »');
INSERT INTO theme (libelle) VALUES ('Plaquette & guide projet du club');
INSERT INTO theme (libelle) VALUES ('Labelisation du club');
INSERT INTO theme (libelle) VALUES ('Aménagement des équipements');
INSERT INTO theme (libelle) VALUES ('Assurances');

-- Thèmes liés à l'atelier 4
INSERT INTO theme (libelle) VALUES ('Observations et analyses sur l’encadrement actuel');
INSERT INTO theme (libelle) VALUES ('Propositions de nouveaux schémas d’organisation');
INSERT INTO theme (libelle) VALUES ('Profils types et pratiques innovantes');
INSERT INTO theme (libelle) VALUES ('Critères et seuils nécessaires à la pérennité de l’emploi');
INSERT INTO theme (libelle) VALUES ('Exercice du métier d’enseignant (avantages et inconvénients)');

-- Thèmes liés à l'atelier 5
INSERT INTO theme (libelle) VALUES ('Présentation');
INSERT INTO theme (libelle) VALUES ('Fonctionnement');
INSERT INTO theme (libelle) VALUES ('Objectifs');
INSERT INTO theme (libelle) VALUES ('Nouveaux diplômes');

-- Thèmes liés à l'atelier 6
INSERT INTO theme (libelle) VALUES ('Les enjeux climatiques, énergétiques et économiques');
INSERT INTO theme (libelle) VALUES ('Sport et développement durable');
INSERT INTO theme (libelle) VALUES ('Démarche fédérale');
INSERT INTO theme (libelle) VALUES ('Échange');
select * from theme;

# Ateliers
INSERT INTO atelier (libelle) VALUES ('Le club et son projet');
INSERT INTO atelier (libelle) VALUES ('Le fonctionnement du club');
INSERT INTO atelier (libelle) VALUES ('Les outils à disposition et remis aux clubs');
INSERT INTO atelier (libelle) VALUES ('Observatoire des métiers de l’escrime');
INSERT INTO atelier (libelle) VALUES ('I.F.F.E');
INSERT INTO atelier (libelle) VALUES ('Développement durable');

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
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (5, 4);

INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (6, 27);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (6, 28);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (6, 29);
INSERT INTO atelier_theme (atelier_id, theme_id) VALUES (6, 30);

select * from atelier;
select * from hotel;
select * from theme;
select * from proposer;


insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(1,'2024-09-08 11:00:00', '2024-09-08 12:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(1,'2024-09-08 14:00:00', '2024-09-08 15:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(1,'2024-09-08 16:00:00', '2024-09-08 17:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(1,'2024-09-09 09:00:00', '2024-09-09 10:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(1,'2024-09-09 11:00:00', '2024-09-09 12:30:00');

insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(2,'2024-09-08 11:00:00', '2024-09-08 12:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(2,'2024-09-08 14:00:00', '2024-09-08 15:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(2,'2024-09-08 16:00:00', '2024-09-08 17:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(2,'2024-09-09 09:00:00', '2024-09-09 10:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(2,'2024-09-09 11:00:00', '2024-09-09 12:30:00');

insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(3,'2024-09-08 11:00:00', '2024-09-08 12:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(3,'2024-09-08 14:00:00', '2024-09-08 15:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(3,'2024-09-08 16:00:00', '2024-09-08 17:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(3,'2024-09-09 09:00:00', '2024-09-09 10:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(3,'2024-09-09 11:00:00', '2024-09-09 12:30:00');

insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(4,'2024-09-08 11:00:00', '2024-09-08 12:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(4,'2024-09-08 14:00:00', '2024-09-08 15:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(4,'2024-09-08 16:00:00', '2024-09-08 17:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(4,'2024-09-09 09:00:00', '2024-09-09 10:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(4,'2024-09-09 11:00:00', '2024-09-09 12:30:00');

insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(5,'2024-09-08 11:00:00', '2024-09-08 12:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(5,'2024-09-08 14:00:00', '2024-09-08 15:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(5,'2024-09-08 16:00:00', '2024-09-08 17:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(5,'2024-09-09 09:00:00', '2024-09-09 10:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(5,'2024-09-09 11:00:00', '2024-09-09 12:30:00');

insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(6,'2024-09-08 11:00:00', '2024-09-08 12:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(6,'2024-09-08 14:00:00', '2024-09-08 15:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(6,'2024-09-08 16:00:00', '2024-09-08 17:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(6,'2024-09-09 09:00:00', '2024-09-09 10:30:00');
insert into vacation (atelier_id,dateheure_debut, dateheure_fin) values(6,'2024-09-09 11:00:00', '2024-09-09 12:30:00');