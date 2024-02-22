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
INSERT INTO theme (idatelier,libelle) VALUES (1,'Diagnostic et identification des critères du club');
INSERT INTO theme (idatelier,libelle) VALUES (1,'Analyse systémique de l’environnement et méthodologie de mise en œuvre du projet');
INSERT INTO theme (idatelier,libelle) VALUES (1,'Actions solidaires et innovantes');
INSERT INTO theme (idatelier,libelle) VALUES (1,'Financements');
INSERT INTO theme (idatelier,libelle) VALUES (1,'Outils et documentation');
INSERT INTO theme (idatelier,libelle) VALUES (1,'Valoriser et communiquer sur le projet');

-- Thèmes liés à l'atelier 2
INSERT INTO theme (idatelier,libelle) VALUES (2,'Création – Obligations légales');
INSERT INTO theme (idatelier,libelle) VALUES (2,'Gestion du personnel, de la structure et des conflits');
INSERT INTO theme (idatelier,libelle) VALUES (2,'Relations internes, externes et avec le Comité départemental, la Ligue et la Fédération');
INSERT INTO theme (idatelier,libelle) VALUES (2,'Conventions');
INSERT INTO theme (idatelier,libelle) VALUES (2,'Partenariats');

-- Thèmes liés à l'atelier 3
INSERT INTO theme (idatelier,libelle) VALUES (3,'Logiciel FFE de gestion des compétitions (présentation et formation)');
INSERT INTO theme (idatelier,libelle) VALUES (3,'Présentation du document « L’arbitrage en images »');
INSERT INTO theme (idatelier,libelle) VALUES (3,'Plaquette & guide projet du club');
INSERT INTO theme (idatelier,libelle) VALUES (3,'Labelisation du club');
INSERT INTO theme (idatelier,libelle) VALUES (3,'Aménagement des équipements');
INSERT INTO theme (idatelier,libelle) VALUES (3,'Assurances');

-- Thèmes liés à l'atelier 4
INSERT INTO theme (idatelier,libelle) VALUES (4,'Observations et analyses sur l’encadrement actuel');
INSERT INTO theme (idatelier,libelle) VALUES (4,'Propositions de nouveaux schémas d’organisation');
INSERT INTO theme (idatelier,libelle) VALUES (4,'Profils types et pratiques innovantes');
INSERT INTO theme (idatelier,libelle) VALUES (4,'Critères et seuils nécessaires à la pérennité de l’emploi');
INSERT INTO theme (idatelier,libelle) VALUES (4,'Exercice du métier d’enseignant (avantages et inconvénients)');

-- Thèmes liés à l'atelier 5
INSERT INTO theme (idatelier,libelle) VALUES (5,'Présentation');
INSERT INTO theme (idatelier,libelle) VALUES (5,'Fonctionnement');
INSERT INTO theme (idatelier,libelle) VALUES (5,'Objectifs');
INSERT INTO theme (idatelier,libelle) VALUES (5,'Nouveaux diplômes');
INSERT INTO theme (idatelier,libelle) VALUES (5,'Financements');

-- Thèmes liés à l'atelier 6
INSERT INTO theme (idatelier,libelle) VALUES (6,'Les enjeux climatiques, énergétiques et économiques');
INSERT INTO theme (idatelier,libelle) VALUES (6,'Sport et développement durable');
INSERT INTO theme (idatelier,libelle) VALUES (6,'Démarche fédérale');
INSERT INTO theme (idatelier,libelle) VALUES (6,'Échange');
select * from theme;

# Ateliers
INSERT INTO atelier (id, libelle) VALUES (1, 'Le club et son projet');
INSERT INTO atelier (id, libelle) VALUES (2, 'Le fonctionnement du club');
INSERT INTO atelier (id, libelle) VALUES (3, 'Les outils à disposition et remis aux clubs');
INSERT INTO atelier (id, libelle) VALUES (4, 'Observatoire des métiers de l’escrime');
INSERT INTO atelier (id, libelle) VALUES (5, 'I.F.F.E');
INSERT INTO atelier (id, libelle) VALUES (6, 'Développement durable');

UPDATE atelier set nb_places_maxi = 3 ;
SELECT * FROM atelier;
ALTER table theme add constraint FK_ATELIER_THEME foreign key (idatelier) references atelier(id);

select * from atelier;
select * from hotel;
select * from theme;

