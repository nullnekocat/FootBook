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
CREATE PROCEDURE sp_get_all_worldcups_light ()
BEGIN
	SELECT id, name, country, year FROM WorldCups
    WHERE status = 1;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_get_worldcup_by_id (IN p_id INT)
BEGIN
	SELECT name, country, year, description, banner FROM WorldCups 
    WHERE id = p_id AND status = 1;
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
DELIMITER ;


