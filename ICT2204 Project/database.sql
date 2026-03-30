-- ============================================================
--  Una Beach Restaurant – MySQL Database
--  HOW TO USE:
--  1. Open phpMyAdmin → http://localhost/phpmyadmin
--  2. Click Import → Choose File → select this file → Go
-- ============================================================

CREATE DATABASE IF NOT EXISTS una_beach_restaurant;
USE una_beach_restaurant;

-- --------------------------------------------------------
-- Table 1: users (login & registration)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- Table 2: messages (contact form)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS messages (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    phone      VARCHAR(20),
    subject    VARCHAR(150),
    message    TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- Table 3: reservations (dine-in table booking)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS reservations (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL,
    guest_name   VARCHAR(100) NOT NULL,
    guest_count  VARCHAR(20)  NOT NULL,
    seating      ENUM('indoor','outdoor') DEFAULT 'indoor',
    date         DATE NOT NULL,
    time         TIME NOT NULL,
    special_note TEXT,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Table 4: orders (takeaway & dine-in orders from menu)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS orders (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL,
    order_type   ENUM('takeaway','dinein') NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    phone        VARCHAR(20)  NOT NULL,
    address      TEXT,                        -- takeaway only
    party_size   VARCHAR(20),                 -- dinein only
    seating      VARCHAR(20),                 -- dinein only
    booking_date DATE,                        -- dinein only
    booking_time TIME,                        -- dinein only
    special_note TEXT,
    total_amount DECIMAL(10,2) NOT NULL,
    status       ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Table 5: order_items (food items inside each order)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS order_items (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    order_id   INT NOT NULL,
    item_name  VARCHAR(100) NOT NULL,
    price      DECIMAL(10,2) NOT NULL,
    qty        INT NOT NULL,
    subtotal   DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
