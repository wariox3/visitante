DELIMITER $$

USE `bdvisitante1teg`$$

DROP PROCEDURE IF EXISTS `RegistroVisitante`$$

CREATE DEFINER=`jgmysql`@`%` PROCEDURE `RegistroVisitante`(IN IdCliente INT, IN NumIdentificacion VARCHAR(25), IN fecha DATE, IN hora TIME)
BEGIN
DECLARE codigoVisitantePk INT DEFAULT 0;
DECLARE codigoGrupoFk INT; 
DECLARE codigoCargoFk INT;
DECLARE fechaArl DATE;
DECLARE codigoRegistroPk INT DEFAULT 0;
DECLARE arlVencida TINYINT DEFAULT 0;
DECLARE diferenciaEntrada DOUBLE;
DECLARE fechaHoraEntrada DATETIME;
DECLARE fechaHora DATETIME;
SELECT codigo_visitante_pk, codigo_grupo_fk, codigo_cargo_fk, fecha_arl INTO codigoVisitantePk, codigoGrupoFk, codigoCargoFk, fechaArl FROM visitante WHERE codigo = NumIdentificacion COLLATE utf8_spanish_ci; 
IF codigoVisitantePk <> 0 THEN	
	SELECT codigo_registro_pk, fecha_entrada INTO codigoRegistroPk, fechaHoraEntrada FROM registro WHERE codigo_visitante_fk = codigoVisitantePk AND estado_entrada = 1 AND estado_salida = 0 AND DATE_FORMAT(fecha_entrada,'%Y-%m-%d') = fecha; 
	#INSERT INTO prueba VALUES('Entro');
	IF codigoRegistroPk <> 0 THEN
		#INSERT INTO prueba VALUES('Actualizar salida');
		SET fechaHora = CONCAT(fecha,' ',hora);
		SET diferenciaEntrada = TIMEDIFF(fechaHoraEntrada, fechaHora);
		SET diferenciaEntrada = (diferenciaEntrada * 60) + MINUTE(TIMEDIFF(fechaHoraEntrada, fechaHora));																																									
		if diferenciaEntrada > 5 then
			UPDATE registro SET fecha_salida = fechaHora, estado_salida = 1 WHERE codigo_registro_pk = codigoRegistroPk;
		end if;
	ELSE
		IF fechaArl < NOW() THEN
			SET arlVencida = 1;
		END IF;
		#INSERT INTO prueba VALUES('Registro nuevo');
		INSERT INTO registro (codigo_visitante_fk, fecha_entrada, estado_entrada, codigo_cliente_fk, codigo_grupo_fk, codigo_cargo_fk, fecha_arl, arl_vencida) VALUES (codigoVisitantePk, CONCAT(fecha,' ',hora), 1, IdCliente, codigoGrupoFk, codigoCargoFk, fechaArl, arlVencida);
	END IF;
END IF;
END$$

DELIMITER ;

