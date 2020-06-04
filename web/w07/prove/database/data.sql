/* Password: thisisanamazingpassword */
INSERT INTO users (first_name, last_name, email, password)
VALUES ('Nick', 'Routsong', 'routy@byui.edu', '$2y$10$lvNbDwhYYSMRDRMm41uCte2URV0f8j9bG21hjBMIC8QWYpeQn1Y9.');

INSERT INTO statuses (name) VALUES ('Pending'),('Active'),('Completed'),('Canceled');

INSERT INTO locations (name, address1, address2, city, state, zip, phone, status_id) VALUES
('The UPS Store 3950', '9221 E Baseline Rd.', 'Suite 109', 'Mesa', 'AZ', '85209', '4803800700', 2),
('Hobby Lobby', '10656 E Southern Ave.', null, 'Mesa', 'AZ', '85209', '4803801509', 2),
('Taco Bell', '9315 E Baseline Rd.', null, 'Mesa', 'AZ', '85209', '4803800700', 2);

INSERT INTO location_user (location_id, user_id) VALUES
(1, 1), (2, 1), (3, 1);

INSERT INTO queues (location_id, status_id) VALUES
(1, 2), (2, 2), (3, 2);

INSERT INTO queue_items (queue_id, status_id, token, queue_position) VALUES
(1, 3, 'qm-5ecabb0bf155d1.45346814', 0),
(1, 2, 'qm-5ecabb0bf155d1.45346815', 0),
(1, 1, 'qm-5ecabb0bf155d1.45346816', 1),
(1, 1, 'qm-5ecabb0bf155d1.45346817', 2),
(1, 1, 'qm-5ecabb0bf155d1.45346818', 3),
(2, 2, 'qm-5ecabb0bf155d1.45346819', 0),
(2, 1, 'qm-5ecabb0bf155d1.45346810', 1),
(2, 1, 'qm-5ecabb0bf155d1.45346811', 2),
(2, 2, 'qm-5ecabb0bf155d1.45346812', 0),
(2, 1, 'qm-5ecabb0bf155d1.45346813', 1),
(2, 1, 'qm-5ecabb0bf155d1.45346814', 2);

