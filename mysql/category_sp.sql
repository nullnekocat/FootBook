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

 -- ACTUALIZACION DEL SP_GET_CATEGORYS 
 -- ACTUALIZA EL SP !!!!

DELIMITER $$
CREATE PROCEDURE `sp_get_categorys`()
BEGIN
  -- Devuelve solo categorías activas (status = 1)
  SELECT id, name
  FROM categories
  WHERE status = 1
  ORDER BY name ASC; 
END$$

DELIMITER ;



USE footbook_db;

-- Eliminar procedimientos si existen
DROP PROCEDURE IF EXISTS sp_update_category;
DROP PROCEDURE IF EXISTS sp_delete_category;

DELIMITER $$

-- ===== Actualizar categoría =====
CREATE PROCEDURE sp_update_category(
    IN p_id INT,
    IN p_name VARCHAR(32)
)
BEGIN
    DECLARE v_name VARCHAR(32);
    DECLARE v_exists INT DEFAULT 0;

    -- Validar que el ID existe
    SELECT COUNT(*) INTO v_exists
      FROM categories
     WHERE id = p_id;

    IF v_exists = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'La categoría no existe';
    END IF;

    -- Normalizar nombre
    SET v_name = TRIM(p_name);

    -- Validar nombre
    IF v_name IS NULL OR v_name = '' THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El nombre de la categoría es requerido';
    END IF;

    IF CHAR_LENGTH(v_name) > 32 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El nombre de la categoría excede 32 caracteres';
    END IF;

    -- Verificar duplicados (excluyendo el mismo registro)
    SELECT COUNT(*) INTO v_exists
      FROM categories
     WHERE LOWER(name) = LOWER(v_name)
       AND id != p_id;

    IF v_exists > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Ya existe otra categoría con ese nombre';
    END IF;

    -- Actualizar
    UPDATE categories
       SET name = v_name
     WHERE id = p_id;

    -- Retornar datos actualizados
    SELECT id, name
      FROM categories
     WHERE id = p_id;
END$$

-- ===== Eliminar categoría (soft delete) =====
CREATE PROCEDURE sp_delete_category(IN p_id INT)
BEGIN
    DECLARE v_exists INT DEFAULT 0;
    DECLARE v_posts_count INT DEFAULT 0;

    -- Verificar que existe
    SELECT COUNT(*) INTO v_exists
      FROM categories
     WHERE id = p_id;

    IF v_exists = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'La categoría no existe';
    END IF;

    -- Verificar si tiene posts asociados
    SELECT COUNT(*) INTO v_posts_count
      FROM posts
     WHERE category_id = p_id
       AND status = 1;

    IF v_posts_count > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede eliminar: hay publicaciones asociadas a esta categoría';
    END IF;

    -- Soft delete
    UPDATE categories
       SET status = 0
     WHERE id = p_id;

    -- Confirmar eliminación
    SELECT id, name, status
      FROM categories
     WHERE id = p_id;
END$$

DELIMITER ;