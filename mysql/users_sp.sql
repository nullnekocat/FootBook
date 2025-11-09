use footbook_db;

-- CRUD de usuarios

DELIMITER $$

CREATE PROCEDURE sp_create_user (
    IN p_admin TINYINT(1),
    IN p_username VARCHAR(32),
    IN p_email VARCHAR(64),
    IN p_password VARCHAR(255),
    IN p_fullname VARCHAR(255),
    IN p_birthday DATE,
    IN p_gender INT,
    IN p_birth_country VARCHAR(32),
    IN p_country VARCHAR(32),
    IN p_avatar LONGBLOB
)
BEGIN
    INSERT INTO Users (
        admin, username, email, password, fullname, birthday, gender, 
        birth_country, country, avatar, status, created_at
    )
    VALUES (
        p_admin, p_username, p_email, p_password, p_fullname, p_birthday, p_gender,
        p_birth_country, p_country, p_avatar, 1, CURRENT_TIMESTAMP
    );
END $$


CREATE PROCEDURE sp_get_all_users ()
BEGIN
    SELECT 
        id, admin, username, email, fullname, birthday, gender,
        birth_country, country, avatar, status, created_at
    FROM Users
    WHERE status = 1;
END $$


CREATE PROCEDURE sp_get_user_by_id (IN p_id BIGINT)
BEGIN
    SELECT 
        id, admin, username, email, fullname, birthday, gender,
        birth_country, country, avatar, status, created_at
    FROM Users
    WHERE id = p_id AND status = 1;
END $$


CREATE PROCEDURE sp_update_user (
    IN p_id BIGINT,
    IN p_admin TINYINT(1),
    IN p_username VARCHAR(32),
    IN p_email VARCHAR(64),
    IN p_password VARCHAR(32),
    IN p_fullname VARCHAR(255),
    IN p_birthday DATE,
    IN p_gender INT,
    IN p_birth_country VARCHAR(32),
    IN p_country VARCHAR(32),
    IN p_avatar LONGBLOB,
    IN p_status TINYINT(1)
)
BEGIN
    UPDATE Users
    SET
        admin = p_admin,
        username = p_username,
        email = p_email,
        password = p_password,
        fullname = p_fullname,
        birthday = p_birthday,
        gender = p_gender,
        birth_country = p_birth_country,
        country = p_country,
        avatar = p_avatar,
        status = p_status
    WHERE id = p_id;
END $$

