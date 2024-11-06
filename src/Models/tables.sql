

CREATE TABLE Materiel (
  id_materiel INT PRIMARY KEY AUTO_INCREMENT,
  type_materiel VARCHAR(50) NOT NULL,
  marque VARCHAR(50) NOT NULL,
  modele VARCHAR(50),
  description_materiel VARCHAR(255),
  etat ENUM('Neuf', 'Bon', 'Usagé', 'Mauvais') NOT NULL,
  garantie ENUM('Oui', 'Non', 'Inconnu') NOT NULL,
  fournisseur VARCHAR(100),
  stock INT NOT NULL
);


-- Table pour les demandes d'emprunt
CREATE TABLE demandes_emprunt (
  id_demande INT PRIMARY KEY AUTO_INCREMENT,
  id_utilisateur INT NOT NULL ,
  id_materiel INT NOT NULL,
  date_emprunt DATE NOT NULL,
  type_demande VARCHAR(255),
  emprunte_par VARCHAR(255),
  fonction VARCHAR(255),
  identification_materiel VARCHAR(255),
  email VARCHAR(255) NOT NULL,
  id_agent VARCHAR(255) NOT NULL,
  restored_by VARCHAR(255),
  restored_date DATE,
  valid TINYINT(1) DEFAULT 0,
  restored_valid TINYINT(1) DEFAULT 0,
  FOREIGN KEY (id_utilisateur) REFERENCES Users(id_user),
  FOREIGN KEY (id_materiel) REFERENCES Materiel(id_materiel)
);

-- Table pour les numéros Isiac
CREATE TABLE num_isiac (
  id INT PRIMARY KEY AUTO_INCREMENT,
  id_demande INT,
  num_isiac VARCHAR(255) NOT NULL,
  FOREIGN KEY (id_demande) REFERENCES demandes_emprunt(id_demande)
);
CREATE TABLE restitutions (
  id_restitution INT PRIMARY KEY AUTO_INCREMENT,
  id_utilisateur INT NOT NULL,
  id_materiel INT NOT NULL,
  date_restitution DATE NOT NULL,
  restitue_par VARCHAR(255),
  fonction VARCHAR(255),
  identification_materiel VARCHAR(255),
  email VARCHAR(255) NOT NULL,
  id_agent VARCHAR(255) NOT NULL,
  valid TINYINT(1) DEFAULT 0,
  FOREIGN KEY (id_utilisateur) REFERENCES Users(id_user),
  FOREIGN KEY (id_materiel) REFERENCES Materiel(id_materiel)
);
