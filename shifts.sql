CREATE TABLE shifts (
    id INT AUTO_INCREMENT PRIMARY PRIMARY,
    name VARCHAR(50) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
ALTER TABLE shifts ADD COLUMN `group` VARCHAR(50) AFTER `name`;


ALTER TABLE users
ADD COLUMN shift_id BIGINT UNSIGNED DEFAULT NULL;

ALTER TABLE users
ADD CONSTRAINT fk_shift
FOREIGN KEY (shift_id) REFERENCES shifts(id)
ON DELETE SET NULL;


CREATE TABLE attendances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    shift_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('Présent', 'Absent') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (shift_id) REFERENCES shifts(id)
);

INSERT INTO shifts (name, start_time, end_time) VALUES
('Matin', '08:00:00', '12:00:00'),
('Après-midi', '14:00:00', '18:00:00'),
('Nuit', '22:00:00', '06:00:00'),
('Normal', '08:00:00', '18:00:00');

CREATE TABLE attendances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    shift_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('Présent', 'Absent') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (shift_id) REFERENCES shifts(id)
);
ALTER TABLE `attendances`
ADD COLUMN `hour` TIME AFTER,  --new
ADD COLUMN `reason` VARCHAR(255),  --new
MODIFY COLUMN `status` ENUM('Présent', 'Absent', 'Late') NOT NULL;  --new
ADD COLUMN matricule VARCHAR(50),  -- Adding new column 'matricule'
ADD CONSTRAINT FK_matricule FOREIGN KEY (matricule) REFERENCES users(matricule); 



CREATE TABLE `equipes` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_equipe VARCHAR(100) NOT NULL,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Step 1: Add the equipe_id column
ALTER TABLE users
ADD COLUMN equipe_id INT;

-- Step 2: Add the foreign key constraint
ALTER TABLE users
ADD CONSTRAINT fk_users_equipes
FOREIGN KEY (equipe_id) REFERENCES equipes(id);



-- SERVER 
-- Step 1: Create the `equipes` Table
CREATE TABLE `equipes` (
    id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom_equipe VARCHAR(100) NOT NULL,
    user_id BIGINT(20) UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Step 2: Add the `equipe_id` Column to `users`
ALTER TABLE users
ADD COLUMN equipe_id BIGINT(20) UNSIGNED;

-- Step 3: Add the Foreign Key Constraint with `ON UPDATE CASCADE`
ALTER TABLE users
ADD CONSTRAINT fk_users_equipes
FOREIGN KEY (equipe_id) REFERENCES equipes(id)
ON UPDATE CASCADE;

-- SERVER END

-- set directeur 
UPDATE users SET directeur = '835' WHERE projet = 'ACIERIE';
UPDATE users SET directeur = '914' WHERE projet = 'LAMINOIR';