CREATE TABLE shifts (
    id INT AUTO_INCREMENT PRIMARY PRIMARY,
    name VARCHAR(50) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
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