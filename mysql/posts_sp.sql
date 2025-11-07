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
END
DELIMITER $$

CREATE PROCEDURE `sp_get_posts_to_approved` ()
BEGIN
    /*
      Devuelve los posts pendientes de aprobación.
      - id del post
      - username (tabla Users.username)
      - category_name (tabla categories.name)
      - worldcup_name (tabla worldcups.name)
      - title
      - description
      - media_b64: TO_BASE64(media) si existe, NULL en caso contrario
    */

    SELECT
        p.id,
        u.username,
        c.name AS category_name,
        w.name AS worldcup_name,
        p.title,
        p.description,
        CASE WHEN p.media IS NULL THEN NULL ELSE TO_BASE64(p.media) END AS media_b64,
        p.created_at
    FROM posts p
    LEFT JOIN Users      u ON u.id = p.user_id
    LEFT JOIN categories c ON c.id = p.category_id
    LEFT JOIN worldcups  w ON w.id = p.worldcup_id
    WHERE p.approved = 0
      AND p.status   = 1       -- si manejas estatus de post
    ORDER BY p.id ASC;
END$$

DELIMITER ;
DELIMITER $$

CREATE PROCEDURE `sp_approve_post` (
    IN p_post_id     BIGINT,
    IN p_is_approved TINYINT
)
BEGIN
    DECLARE v_exists INT DEFAULT 0;

    /* ===== Validaciones ===== */
    IF p_post_id IS NULL OR p_post_id <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_post_id inválido';
    END IF;

    IF p_is_approved NOT IN (0,1) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'p_is_approved debe ser 0 o 1';
    END IF;

    SELECT COUNT(*) INTO v_exists
      FROM posts
     WHERE id = p_post_id;

    IF v_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El post no existe';
    END IF;

    /* ===== Update ===== */
    START TRANSACTION;

    UPDATE posts
       SET approved   = p_is_approved,
           approved_at = CASE WHEN p_is_approved = 1 THEN CURRENT_TIMESTAMP() ELSE NULL END
     WHERE id = p_post_id;

    /* Devuelve el estado final */
    SELECT id,
           approved,
           approved_at
      FROM posts
     WHERE id = p_post_id;

    COMMIT;
END$$

DELIMITER ;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_feed`(
    IN p_user_id     BIGINT,     -- 0 o NULL => anónimo
    IN p_after_id    BIGINT,     -- cursor: último id recibido; NULL/0 => primera página
    IN p_limit       INT,        -- tamaño de página
    IN p_category_id INT,        -- filtro opcional; NULL/0 => todos
    IN p_worldcup_id INT         -- filtro opcional; NULL/0 => todos
)
BEGIN
    IF p_limit IS NULL OR p_limit <= 0 THEN
        SET p_limit = 10;
    END IF;

    SELECT 
        p.id, p.title, p.team, p.description, p.created_at, p.approved_at,
        u.id AS user_id, u.username, TO_BASE64(u.avatar) AS avatar_b64,
        c.id AS category_id, c.name AS category_name,
        w.id AS worldcup_id, w.name AS worldcup_name, w.year AS worldcup_year,
        TO_BASE64(p.media) AS media_b64,
        (SELECT COUNT(*) FROM PostLikes pl WHERE pl.post_id = p.id AND pl.status = 1) AS likes_count,
        (SELECT COUNT(*) FROM Comments  co WHERE co.post_id = p.id AND co.status = 1) AS comments_count,
        CASE 
            WHEN p_user_id IS NULL OR p_user_id = 0 THEN 0
            ELSE EXISTS(SELECT 1 FROM PostLikes x 
                        WHERE x.post_id = p.id AND x.user_id = p_user_id AND x.status = 1)
        END AS liked_by_me
    FROM Posts p
    JOIN Users      u ON u.id = p.user_id     AND u.status = 1
    JOIN Categories c ON c.id = p.category_id AND c.status = 1
    JOIN WorldCups  w ON w.id = p.worldcup_id AND w.status = 1
    WHERE p.status = 1 
      AND p.approved = 1
      AND (p_after_id    IS NULL OR p_after_id    = 0 OR p.id < p_after_id)
      AND (p_category_id IS NULL OR p_category_id = 0 OR p.category_id = p_category_id)
      AND (p_worldcup_id IS NULL OR p_worldcup_id = 0 OR p.worldcup_id = p_worldcup_id)
    ORDER BY p.id DESC
    LIMIT p_limit;
END