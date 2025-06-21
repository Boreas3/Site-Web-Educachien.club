-- Script de création de la base de données pour le site Educachien
-- À exécuter dans votre serveur MySQL local

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS educachien_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Utilisation de la base de données
USE educachien_db;

-- Création de la table des activités
CREATE TABLE IF NOT EXISTS activites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    lieu VARCHAR(255) DEFAULT 'au club',
    paf VARCHAR(255),
    modalites TEXT,
    type_activite ENUM('balade', 'formation', 'evenement', 'travaux', 'autre') DEFAULT 'autre',
    public_cible ENUM('tous', 'chiots', 'adultes', 'membres') DEFAULT 'tous',
    actif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion de quelques exemples d'activités basées sur le contenu actuel
INSERT INTO activites (titre, description, date_debut, date_fin, lieu, paf, modalites, type_activite, public_cible) VALUES
('Visite de Saint Nicolas et Père Noël + goûter', 'Infos supplémentaires très bientôt', '2025-12-14 14:00:00', '2025-12-14 16:00:00', 'au club', 'À définir', 'Infos à venir', 'evenement', 'tous'),
('Balade éducative > 8 mois', 'Une balade pour apprendre à vos chiens à évoluer en meute tout en étant attachés. Pour faire des rencontres: cyclistes, voitures, tracteurs, joggeurs, etc... Et mettre en pratique les apprentissages du club +/- 4km en urbain et campagne', '2025-11-15 10:00:00', '2025-11-15 14:00:00', 'départ au club', '5€/personne membre, 7€/personne non membre - ☕️ une boisson "soft" vous sera offerte au retour', 'Obligation pour tout détenteur de chien d\'avoir un sac à dos contenant une longe de 10m (PAS DE LAISSE ENROULEUR), de l\'eau pour le chien, une petite trousse de secours permettant de faire un pansement simple. Amis bienvenus sur présentation du carnet véto du chien (toux du chenil en plus des vaccins habituels). Chien non membre du club : munissez vous du carnet vétérinaire ! Pas besoin de s\'inscrire à l\'avance. Paiement au départ de la balade', 'balade', 'adultes'),
('Initiation au secourisme canin', 'Formation pour apprendre les gestes de premiers secours pour chiens', '2025-11-09 10:00:00', '2025-11-09 18:00:00', 'au club', 'À définir', 'Formation complète sur une journée', 'formation', 'tous'),
('Balade éducative - Chiots', 'Une balade pour apprendre à vos chiens à évoluer en meute tout en étant attachés. Pour faire des rencontres: cyclistes, voitures, tracteurs, joggeurs, etc... Et mettre en pratique les apprentissages du club +/- 4km en urbain et campagne', '2025-11-08 10:00:00', '2025-11-08 14:00:00', 'départ au club', '5€/personne membre, 7€/personne non membre - ☕️ une boisson "soft" vous sera offerte au retour', 'Obligation pour tout détenteur de chien d\'avoir un sac à dos contenant une longe de 10m (PAS DE LAISSE ENROULEUR), de l\'eau pour le chien, une petite trousse de secours permettant de faire un pansement simple. Amis bienvenus sur présentation du carnet véto du chien (toux du chenil en plus des vaccins habituels). Chien non membre du club : munissez vous du carnet vétérinaire ! Pas besoin de s\'inscrire à l\'avance. Paiement au départ de la balade', 'balade', 'chiots'),
('Balade contée Halloween + souper', 'Balade contée, concours de déguisements, chasse au trésor… À 20h souper soupe potiron-lardons et/ou soupe à l\'oignon fromage croutons', '2025-10-31 18:00:00', '2025-10-31 22:00:00', 'au club', 'souper & balade : 8€/pers, balade seule : 3€/chien', 'Réservation et paiement avant le 22 octobre 19h, dans les locaux du club.', 'evenement', 'tous'),
('Test de sociabilité (TS)', 'Test de sociabilité + grand BBQ annuel du Club', '2025-07-27 09:00:00', '2025-07-27 17:00:00', 'au club', 'TS: 12€/chien, Assiette BBQ 2 viandes + crudités + pain et pdt : 15€', 'Le test est accessible aux chiens de 9 mois accomplis. Inscriptions auprès de Dany. Réservations et paiements repas pour le 19/7 !', 'evenement', 'adultes'),
('Week-end grands travaux', 'Remise en ordre des terrains, débroussaillage, tonte des haies, arrachages mauvaises herbes, tension des barbelés, nettoyage des modules, nettoyage sous carpot, grand nettoyage cafétariat et annexes (fenêtres, murs, radiateurs, ...), ...', '2025-07-12 10:00:00', '2025-07-13 18:00:00', 'au club', 'Gratuit', 'Même 1h de votre temps nous sera utile. Si vous avez taille haie, débroussailleuse essence, grattoirs, râteaux, ... merci de les prendre, plus on a de matériel, plus vite on avance. Bienvenue à tous, on compte sur votre esprit club 😉', 'travaux', 'membres');

-- Création d'un utilisateur pour la base de données (à adapter selon vos besoins)
-- CREATE USER 'educachien_user'@'localhost' IDENTIFIED BY 'votre_mot_de_passe_securise';
-- GRANT ALL PRIVILEGES ON educachien_db.* TO 'educachien_user'@'localhost';
-- FLUSH PRIVILEGES; 