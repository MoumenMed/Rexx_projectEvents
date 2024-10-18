CREATE DATABASE event_management;

USE event_management;

CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255),
    UNIQUE(email)
);

CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    date DATETIME,
    version VARCHAR(10) null
);

CREATE TABLE participations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT,
    event_id INT,
    fee DECIMAL(10, 2),
    FOREIGN KEY (employee_id) REFERENCES employees(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
);
