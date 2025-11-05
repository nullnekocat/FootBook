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


CREATE PROCEDURE sp_soft_delete_user (IN p_id BIGINT)
BEGIN
    UPDATE Users
    SET status = 0
    WHERE id = p_id;
END $$


CREATE PROCEDURE sp_hard_delete_user (IN p_id BIGINT)
BEGIN
    DELETE FROM Users
    WHERE id = p_id;
END $$

DELIMITER ;



DELIMITER $$

-- Username y avatar

CREATE PROCEDURE sp_get_post_with_user_light (IN p_post_id BIGINT)
BEGIN
    SELECT 
        p.id AS post_id,
        p.title,
        p.description,
        p.views,
        p.created_at,
        u.username,
        u.avatar
    FROM Posts p
    INNER JOIN Users u ON p.user_id = u.id
    WHERE p.id = p_post_id 
      AND p.status = 1 
      AND u.status = 1;
END $$

DELIMITER ;

-- Rename

DELIMITER $$

CREATE PROCEDURE sp_rename_user (
    IN p_id BIGINT,
    IN p_new_username VARCHAR(32)
)
BEGIN
    UPDATE Users
    SET username = p_new_username
    WHERE id = p_id AND status = 1;
END $$

CREATE PROCEDURE sp_get_all_users_light ()
BEGIN
    SELECT 
        id, username, avatar
    FROM Users
    WHERE status = 1;
END $$

CREATE PROCEDURE sp_get_user_by_id_light (IN p_id BIGINT)
BEGIN
    SELECT 
        id, username, avatar
    FROM Users
    WHERE id = p_id AND status = 1;
END $$

DELIMITER ;

--Login del usuario
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_user_for_login`(IN p_identity VARCHAR(64))
BEGIN
    SELECT 
        id, admin, username, email, password, fullname, birthday, gender,
        birth_country, country, status, created_at
    FROM Users
    WHERE (username = p_identity)
      AND status = 1
    LIMIT 1;
END

DELIMITER ;

CREATE PROCEDURE sp_get_user_data(IN p_id BIGINT)
BEGIN
    SELECT 
        id,
        admin,
        username,
        email,
        fullname,
        birthday,
        gender,
        birth_country,
        country,
        status,
        created_at,
        avatar     -- LONGBLOB (si lo vas a servir como binario o base64 desde PHP)
    FROM Users
    WHERE id = p_id AND status = 1
    LIMIT 1;
END $$
DELIMITER ;