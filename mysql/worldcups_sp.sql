DELIMITER $$
CREATE PROCEDURE sp_create_worldcup (
	IN p_name VARCHAR(64),
	IN p_country VARCHAR(32),
	IN p_year INT,
	IN p_description TEXT,
	IN p_banner LONGBLOB,
	IN p_status TINYINT(1)
)
BEGIN
	INSERT INTO WorldCups (name, country, year, description, banner, status)
	VALUES (p_name, p_country, p_year, p_description, p_banner, p_status);
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_get_all_worldcups ()
BEGIN
	SELECT id, name, country, year, description, banner FROM WorldCups
    WHERE status = 1;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_get_worldcup_by_id_light (IN p_id INT)
BEGIN
	SELECT name, country, year FROM WorldCups 
    WHERE id = p_id AND status = 1;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_update_worldcup (
	IN p_id INT,
	IN p_name VARCHAR(64),
	IN p_country VARCHAR(32),
	IN p_year INT,
	IN p_description TEXT,
	IN p_banner LONGBLOB,
	IN p_status TINYINT(1)
)
BEGIN
	UPDATE WorldCups
	SET
		name = p_name,
		country = p_country,
		year = p_year,
		description = p_description,
		banner = p_banner,
		status = p_status
	WHERE id = p_id;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_hard_delete_worldcup (IN p_id INT)
BEGIN
	DELETE FROM WorldCups WHERE id = p_id;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_soft_delete_worldcup (IN p_id INT)
BEGIN
	UPDATE WorldCups SET status = 0 WHERE id = p_id;
END $$
/*Cosas nuevas*/
DELIMITER ;
CREATE PROCEDURE `sp_get_worldcups_data`()
BEGIN
    /* Devuelve todas los mundiales ordenadas por id del menor a mayor*/
    SELECT 
    id, 
    name, 
    country, 
    year, 
    description,
    TO_BASE64(banner) AS banner_b64
    FROM worldcups WHERE status = 1 ORDER BY id ASC;  -- orden por id
END$$
DELIMITER ;

DELIMITER ;
CREATE PROCEDURE `sp_get_worldcup_by_id`(
IN p_id INT
)
BEGIN
    /* Devuelve todas los mundiales ordenadas por id del menor a mayor*/
    SELECT 
    id,
    name,
    country,
    year,
    description,
    TO_BASE64(banner) AS banner_b64 
    FROM worldcups WHERE status = 1 AND id = p_id; -- orden por id
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_get_all_worldcups_light ()
BEGIN
	SELECT id, name, country, year FROM WorldCups
    WHERE status = 1 ORDER BY id ASC;
END $$
DELIMITER ;
USE `footbook_db`;
DROP procedure IF EXISTS `sp_create_post`;

USE `footbook_db`;
DROP procedure IF EXISTS `footbook_db`.`sp_create_post`;
;

DELIMITER $$
USE `footbook_db`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_post`(
    IN p_user_id     BIGINT,	   -- NOTNULL
    IN p_category_id INT,          -- NOTNULL
    IN p_worldcup_id INT,          -- NOTNULL
    IN p_team        VARCHAR(32),  -- equipo (opcional),
    IN p_title		 VARCHAR(64),  -- NOTNULL
    IN p_description TEXT,         -- NOTNULL
    IN p_media       LONGBLOB      -- imagen/video (opcional)
)
BEGIN
    DECLARE v_user    INT DEFAULT 0;
    DECLARE v_cat     INT DEFAULT 0;
    DECLARE v_wc      INT DEFAULT 0;

    /* ===== Validaciones básicas ===== */
    IF p_user_id IS NULL OR p_user_id <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_user_id es requerido';
    END IF;
    
    IF p_category_id IS NULL OR p_category_id <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_category_id es requerido';
    END IF;
    
	IF p_worldcup_id IS NULL OR p_worldcup_id <= 0 THEN --
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_worldcup_id es requerido';
    END IF;
    
	IF p_description IS NULL OR CHAR_LENGTH(TRIM(p_description)) = 0 THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_description es requerido';
	END IF;
    
	IF p_title IS NULL OR CHAR_LENGTH(TRIM(p_title)) = 0 OR CHAR_LENGTH(TRIM(p_title)) > 64 THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'campo p_title invalido';
	END IF;
    
    SELECT COUNT(*) INTO v_user
      FROM Users
     WHERE id = p_user_id AND status = 1;
    IF v_user = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario no existe o está inactivo';
    END IF;

    IF p_category_id IS NOT NULL THEN
        SELECT COUNT(*) INTO v_cat
          FROM categories
         WHERE id = p_category_id;
        IF v_cat = 0 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La categoría no existe';
        END IF;
    END IF;

    IF p_worldcup_id IS NOT NULL THEN
        SELECT COUNT(*) INTO v_wc
          FROM worldcups
         WHERE id = p_worldcup_id;
        IF v_wc = 0 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El mundial (worldcup) no existe';
        END IF;
    END IF;

    IF p_description IS NULL OR CHAR_LENGTH(TRIM(p_description)) = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La descripción es requerida';
    END IF;

    IF p_team IS NOT NULL AND CHAR_LENGTH(p_team) > 32 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El campo team excede 32 caracteres';
    END IF;

    /* ===== Inserción ===== */
    START TRANSACTION;

    INSERT INTO posts(
        user_id,
        category_id,
        worldcup_id,
        team,
        title,
        description,
        media,
        approved,
        status,
        created_at
    )
    VALUES(
        p_user_id,
        p_category_id,
        p_worldcup_id,
        p_team,
        p_title,
        p_description,
        p_media,
        0,                    -- pendiente de aprobación
        1,                    -- activo
        CURRENT_TIMESTAMP()
    );

    /*Devuelve el id creado*/
    SELECT LAST_INSERT_ID() AS post_id;

    COMMIT;
END$$

DELIMITER ;
;


