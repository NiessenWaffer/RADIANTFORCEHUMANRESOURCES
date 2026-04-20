-- MAJOR PHILIPPINE CITIES WITH SM MALLS AND ROBINSONS
-- These are the key deployment cities across Luzon, Visayas, and Mindanao
USE `dbronnie`;
-- LUZON MAJOR CITIES
INSERT IGNORE INTO `cities` (`city_name`, `island`, `status`) VALUES
-- NCR (Metro Manila)
('Manila', 'Luzon', 'active'),
('Quezon City', 'Luzon', 'active'),
('Makati', 'Luzon', 'active'),
('Pasig', 'Luzon', 'active'),
('Taguig', 'Luzon', 'active'),
('Caloocan', 'Luzon', 'active'),
('Parañaque', 'Luzon', 'active'),
('Las Piñas', 'Luzon', 'active'),
('Marikina', 'Luzon', 'active'),
('Muntinlupa', 'Luzon', 'active'),
('Valenzuela', 'Luzon', 'active'),
('Navotas', 'Luzon', 'active'),
('Malabon', 'Luzon', 'active'),
('Pasay', 'Luzon', 'active'),
('San Juan', 'Luzon', 'active'),
-- Nearby Metro Manila
('Antipolo', 'Luzon', 'active'),
('Sta. Rosa', 'Luzon', 'active'),
('Calamba', 'Luzon', 'active'),
('Biñan', 'Luzon', 'active'),
('Laguna', 'Luzon', 'active'),
('Bacoor', 'Luzon', 'active'),
('Dasmariñas', 'Luzon', 'active'),
('Cavite City', 'Luzon', 'active'),
('Imus', 'Luzon', 'active'),
('Tagaytay', 'Luzon', 'active'),
-- Regional Centers
('Baguio', 'Luzon', 'active'),
('Tuguegarao', 'Luzon', 'active'),
('Laoag', 'Luzon', 'active'),
('Vigan', 'Luzon', 'active'),
('Dagupan', 'Luzon', 'active'),
('Urdaneta', 'Luzon', 'active'),
('Olongapo', 'Luzon', 'active'),
('Subic', 'Luzon', 'active'),
('Meycauayan', 'Luzon', 'active'),
('Marilao', 'Luzon', 'active'),
('Cabanatuan', 'Luzon', 'active'),
('San Fernando', 'Luzon', 'active'),
('Malolos', 'Luzon', 'active'),
('Lucena', 'Luzon', 'active'),
('Batangas City', 'Luzon', 'active'),
('Lipa', 'Luzon', 'active'),
('Legazpi', 'Luzon', 'active'),
('Naga', 'Luzon', 'active'),
('Iriga', 'Luzon', 'active'),
('Sorsogon City', 'Luzon', 'active'),
('Masbate City', 'Luzon', 'active'),
('Puerto Princesa', 'Luzon', 'active'),
('Calapan', 'Luzon', 'active');

-- VISAYAS MAJOR CITIES
INSERT IGNORE INTO `cities` (`city_name`, `island`, `status`) VALUES
-- Central Visayas
('Cebu City', 'Visayas', 'active'),
('Mandaue', 'Visayas', 'active'),
('Lapu-Lapu', 'Visayas', 'active'),
('Talisay', 'Visayas', 'active'),
('Dumaguete', 'Visayas', 'active'),
('Tagbilaran', 'Visayas', 'active'),
-- Western Visayas
('Iloilo City', 'Visayas', 'active'),
('Bacolod', 'Visayas', 'active'),
('Roxas', 'Visayas', 'active'),
('Kalibo', 'Visayas', 'active'),
('Calinog', 'Visayas', 'active'),
('Silay', 'Visayas', 'active'),
('Himamaylan', 'Visayas', 'active'),
('Sagay', 'Visayas', 'active'),
('Kabankalan', 'Visayas', 'active'),
-- Eastern Visayas
('Tacloban', 'Visayas', 'active'),
('Ormoc', 'Visayas', 'active'),
('Catbalogan', 'Visayas', 'active'),
('Calbayog', 'Visayas', 'active'),
('Borongan', 'Visayas', 'active');

-- MINDANAO MAJOR CITIES
INSERT IGNORE INTO `cities` (`city_name`, `island`, `status`) VALUES
-- Northern Mindanao
('Cagayan de Oro', 'Mindanao', 'active'),
('Butuan', 'Mindanao', 'active'),
('Surigao City', 'Mindanao', 'active'),
('Tandag', 'Mindanao', 'active'),
('Bislig', 'Mindanao', 'active'),
-- Davao Region
('Davao City', 'Mindanao', 'active'),
('Tagum', 'Mindanao', 'active'),
('Panabo', 'Mindanao', 'active'),
('Digos', 'Mindanao', 'active'),
('Mati', 'Mindanao', 'active'),
-- Zamboanga Peninsula
('Zamboanga City', 'Mindanao', 'active'),
('Pagadian', 'Mindanao', 'active'),
('Dipolog', 'Mindanao', 'active'),
('Isabela', 'Mindanao', 'active'),
-- SOCCSKSARGEN
('General Santos', 'Mindanao', 'active'),
('Koronadal', 'Mindanao', 'active'),
('Tacurong', 'Mindanao', 'active'),
('Isulan', 'Mindanao', 'active'),
('Cotabato', 'Mindanao', 'active'),
('Kidapawan', 'Mindanao', 'active'),
-- BARMM
('Cotabato City', 'Mindanao', 'active'),
('Marawi', 'Mindanao', 'active'),
('Iligan', 'Mindanao', 'active');
