
CREATE DATABASE IF NOT EXISTS realestate;
USE realestate;

CREATE TABLE IF NOT EXISTS properties (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description_ TEXT,
    img_url VARCHAR(255),
    url_ VARCHAR(255),
    address VARCHAR(255),
    property_type VARCHAR(255),
    bedrooms INT(11),
    bathrooms INT(11),
    square_footage INT(11),
    available TINYINT(1),
    city VARCHAR(255),
    zip_code INT(5),
    price INT(11)
);

CREATE TABLE IF NOT EXISTS property_images (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    property_id INT(11),
    img_url VARCHAR(255),
    FOREIGN KEY (property_id) REFERENCES properties(id)
);

CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    username VARCHAR(20),
    email VARCHAR(30),
    user_type ENUM('buyer', 'seller'),
    pword VARCHAR(255),
    wishlist VARCHAR(255)
);
