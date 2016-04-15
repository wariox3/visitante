DELIMITER $$

USE `bdvisitante`$$

DROP PROCEDURE IF EXISTS `RegistroVisitante`$$

CREATE PROCEDURE `RegistroVisitante`(IN IdCliente INT, IN NumIdentificacion VARCHAR(25))
BEGIN
DECLARE codigoVisitantePk INT DEFAULT 0;
DECLARE codigoGrupoFk INT; 
DECLARE codigoCargoFk INT;
DECLARE fechaArl DATE;
DECLARE codigoRegistroPk INT DEFAULT 0;
DECLARE arlVencida TINYINT DEFAULT 0;

SELECT codigo_visitante_pk, codigo_grupo_fk, codigo_cargo_fk, fecha_arl INTO codigoVisitantePk, codigoGrupoFk, codigoCargoFk, fechaArl FROM visitante WHERE codigo = NumIdentificacion COLLATE utf8_spanish_ci; 
IF codigoVisitantePk <> 0 THEN	
	SELECT codigo_registro_pk INTO codigoRegistroPk FROM registro WHERE codigo_visitante_fk = codigoVisitantePk AND estado_entrada = 1 AND estado_salida = 0 AND DATE_FORMAT(fecha_entrada,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d'); 
	IF codigoRegistroPk <> 0 THEN
		UPDATE registro SET fecha_salida = NOW(), estado_salida = 1 WHERE codigo_registro_pk = codigoRegistroPk;
	ELSE
		IF fechaArl < NOW() THEN
			SET arlVencida = 1;
		END IF;
		INSERT INTO registro (codigo_visitante_fk, fecha_entrada, estado_entrada, codigo_cliente_fk, codigo_grupo_fk, codigo_cargo_fk, fecha_arl, arl_vencida) VALUES (codigoVisitantePk, NOW(), 1, IdCliente, codigoGrupoFk, codigoCargoFk, fechaArl, arlVencida);
	END IF;
END IF;
END$$

DELIMITER ;

