DELIMITER $$
CREATE TRIGGER trg_criar_preferencia_default
AFTER INSERT ON utilizador
FOR EACH ROW
BEGIN
    DECLARE novo_id_preferencia INT;
    IF NEW.id_funcao >= 4 THEN
        INSERT INTO preferencias (preferencia)
        SELECT preferencia
        FROM preferencias
        WHERE id_preferencias = 1;
        SET novo_id_preferencia = LAST_INSERT_ID();
        INSERT INTO utilizador_preferencia (id_utilizador, id_preferencias)
        VALUES (NEW.id_utilizador, novo_id_preferencia);
    END IF;
END$$
DELIMITER ;


DELIMITER $$
CREATE TRIGGER trg_criar_preferencia_turma_default
AFTER INSERT ON turma
FOR EACH ROW
BEGIN
    DECLARE novo_id_preferencia INT;
    -- Copia a preferência com id_preferencias = 3
    INSERT INTO preferencias (preferencia)
    SELECT preferencia
    FROM preferencias
    WHERE id_preferencias = 3;
    -- Guarda o ID da nova preferência criada
    SET novo_id_preferencia = LAST_INSERT_ID();
    -- Cria a ligação turma <-> preferência
    INSERT INTO preferencias_turma (id_turma, id_preferencias)
    VALUES (NEW.id_turma, novo_id_preferencia);
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER trg_criar_preferencia_sala_default
AFTER INSERT ON sala
FOR EACH ROW
BEGIN
    DECLARE novo_id_preferencia INT;
    -- Copia a preferência com id_preferencias = 2
    INSERT INTO preferencias (preferencia)
    SELECT preferencia
    FROM preferencias
    WHERE id_preferencias = 2;
    -- Guarda o ID da nova preferência criada
    SET novo_id_preferencia = LAST_INSERT_ID();
    -- Cria a ligação sala <-> preferência
    INSERT INTO preferencia_sala (id_sala, id_preferencias)
    VALUES (NEW.id_sala, novo_id_preferencia);
END$$
DELIMITER ;

