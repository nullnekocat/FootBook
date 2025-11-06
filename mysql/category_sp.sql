DELIMITER $$
CREATE PROCEDURE `sp_create_category`(
    IN p_name VARCHAR(255)
)
BEGIN
    DECLARE v_name   VARCHAR(255);
    DECLARE v_exists INT DEFAULT 0;

    -- Normaliza
    SET v_name = TRIM(p_name);

    -- Requerido
    IF v_name IS NULL OR v_name = '' THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El nombre de la categoría es requerido';
    END IF;

    -- Longitud
    IF CHAR_LENGTH(v_name) > 32 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El nombre de la categoría excede 32 caracteres (máx 32)';
    END IF;

    -- Duplicados (case-insensitive)
    SELECT COUNT(*) INTO v_exists
      FROM categories
     WHERE LOWER(name) = LOWER(v_name);

    IF v_exists > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'La categoría ya existe';
    END IF;

    -- Inserción
    INSERT INTO categories (name)
    VALUES (v_name);

    -- Retorna el id creado
    SELECT LAST_INSERT_ID() AS category_id;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `sp_get_categorys`()
BEGIN
    /* Devuelve todas las categorías ordenadas por id del menor a mayor*/
    SELECT 
        id,
        name
    FROM categories
    ORDER BY id ASC;  -- orden por id
END$$

DELIMITER ;