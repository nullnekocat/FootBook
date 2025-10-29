CREATE DATABASE IF NOT EXISTS footbook_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE footbook_db;

CREATE TABLE Users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    admin TINYINT(1) NOT NULL,
    username VARCHAR(32) UNIQUE NOT NULL,
    email VARCHAR(64) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
	fullname VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    gender INT NOT NULL,
    birth_country VARCHAR(32) NOT NULL,
    country VARCHAR(32) NOT NULL,
    avatar LONGBLOB,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Categories (
	id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(32) NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE WorldCups (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(64) NOT NULL,
    country VARCHAR(32) NOT NULL,
    year INT NOT NULL,
    description TEXT,
    banner LONGBLOB,
    status TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE Posts (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    category_id INT NOT NULL,
    worldcup_id INT NOT NULL,
    title VARCHAR(64) NOT NULL,
    team VARCHAR(32),
    description TEXT,
    media LONGBLOB,
    views INT,
    approved TINYINT(1),
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id),
	FOREIGN KEY (category_id) REFERENCES Categories(id),
	FOREIGN KEY (worldcup_id) REFERENCES WorldCups(id)
);

CREATE TABLE Comments (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    post_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    content TEXT NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES Posts(id),
    FOREIGN KEY (user_id) REFERENCES Users(id)
);

CREATE TABLE PostLikes (
	id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    post_id BIGINT NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (post_id) REFERENCES Posts(id),
    UNIQUE (post_id, user_id)
);

CREATE TABLE CommentLikes (
	id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    comment_id BIGINT NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (comment_id) REFERENCES Comments(id),
    UNIQUE (comment_id, user_id)
);