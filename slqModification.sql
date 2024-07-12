--absence
ALTER TABLE `demandes`
MODIFY COLUMN `status` ENUM('En Attend', 'Validé', 'Refusé') DEFAULT 'En Attend';

CREATE TABLE absences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    demande_id BIGINT(20) UNSIGNED NOT NULL,
    date_demandé DATE NOT NULL,
    motif VARCHAR(255) NOT NULL,
    piece_jointe VARCHAR(255),
    to_responsable_id INT,
    approved_at TIMESTAMP,
    absence_type ENUM('Medicale', 'Personnel', 'Formation', 'Autre') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (demande_id) REFERENCES demandes(id)
);