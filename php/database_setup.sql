CREATE DATABASE IF NOT EXISTS foss_estate
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE foss_estate;

CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    role ENUM('admin', 'tenant') NOT NULL DEFAULT 'tenant',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_username (username),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS properties (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    property_type ENUM('house', 'apartment', 'garage lot') NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    postal_code VARCHAR(20) NULL,
    country VARCHAR(255) NOT NULL DEFAULT 'Unknown',
    description TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS rentals (
    id INT NOT NULL AUTO_INCREMENT,
    property_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    monthly_rent DECIMAL(10,2) NOT NULL,
    security_deposit DECIMAL(10,2) NULL,
    availability_status ENUM('available', 'occupied', 'maintenance') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_rentals_property_id (property_id),
    CONSTRAINT fk_rentals_property
        FOREIGN KEY (property_id) REFERENCES properties (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tenants (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NULL,
    emergency_contact VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_tenants_user_id (user_id),
    CONSTRAINT fk_tenants_user
        FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS leases (
    id INT NOT NULL AUTO_INCREMENT,
    rental_id INT NOT NULL,
    tenant_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NULL,
    monthly_rent DECIMAL(10,2) NOT NULL,
    status ENUM('active', 'ended', 'pending') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_leases_rental_id (rental_id),
    KEY idx_leases_tenant_id (tenant_id),
    CONSTRAINT fk_leases_rental
        FOREIGN KEY (rental_id) REFERENCES rentals (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_leases_tenant
        FOREIGN KEY (tenant_id) REFERENCES tenants (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
    id INT NOT NULL AUTO_INCREMENT,
    lease_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'card', 'other') NOT NULL DEFAULT 'bank_transfer',
    status ENUM('pending', 'paid', 'failed') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_payments_lease_id (lease_id),
    CONSTRAINT fk_payments_lease
        FOREIGN KEY (lease_id) REFERENCES leases (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS maintenance_requests (
    id INT NOT NULL AUTO_INCREMENT,
    rental_id INT NOT NULL,
    created_by_user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') NOT NULL DEFAULT 'medium',
    status ENUM('open', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'open',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY idx_maintenance_rental_id (rental_id),
    KEY idx_maintenance_created_by_user_id (created_by_user_id),
    CONSTRAINT fk_maintenance_rental
        FOREIGN KEY (rental_id) REFERENCES rentals (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_maintenance_created_by_user
        FOREIGN KEY (created_by_user_id) REFERENCES users (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;