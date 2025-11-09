-- Sustituir el sp de antes por este
DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_feed`(
    IN p_user_id     BIGINT,     -- 0/NULL => no filtra por usuario (modo feed general)
    IN p_after_id    BIGINT,     -- cursor
    IN p_limit       INT,        -- tamaño de página
    IN p_category_id INT,        -- 0/NULL => todas
    IN p_worldcup_id INT         -- 0/NULL => todas
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
            ELSE EXISTS(SELECT 1
                        FROM PostLikes x
                        WHERE x.post_id = p.id
                          AND x.user_id = p_user_id
                          AND x.status = 1)
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
      AND (p_user_id     IS NULL OR p_user_id     = 0 OR p.user_id = p_user_id)
    ORDER BY p.id DESC
    LIMIT p_limit;
END$$

DELIMITER ;
