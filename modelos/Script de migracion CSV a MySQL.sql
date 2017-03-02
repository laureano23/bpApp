-- BORRADO DE TABLAS
USE metbptest;
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE articulos;
SET FOREIGN_KEY_CHECKS = 1;


-- TABLA COMPROBANTES

LOAD DATA INFILE '/almacen/exportacion/comp.TXT'
INTO TABLE comprobantes
FIELDS TERMINATED BY ';' 
	OPTIONALLY ENCLOSED BY '"'
    LINES  TERMINATED BY '\r\n'
IGNORE 1 LINES
(id, comprobante, abreviatura);

-- TABLA FAMILIA
USE metbptest;
LOAD DATA INFILE '/almacen/exportacion/familia.TXT'
INTO TABLE Familia
FIELDS TERMINATED BY ';' 
	OPTIONALLY ENCLOSED BY '"'
    LINES  TERMINATED BY '\r\n'
IGNORE 1 LINES
(@NRO_ID, @COD_FAM, familia);

-- TABLA SUBFAMILIA
USE metbptest;
LOAD DATA INFILE '/almacen/exportacion/subfamilia.TXT'
INTO TABLE SubFamilia
FIELDS TERMINATED BY ';' 
	OPTIONALLY ENCLOSED BY '"'
    LINES  TERMINATED BY '\r\n'
IGNORE 1 LINES
(@NRO_ID, @COD_MOD, subFamilia);

-- TABLA ARTICULOS
USE metbptest;
LOAD DATA INFILE '/almacen/exportacion/articulos.TXT'
IGNORE INTO TABLE articulos
FIELDS TERMINATED BY '~' 
	OPTIONALLY ENCLOSED BY '}'
    LINES  TERMINATED BY '\r\n'
IGNORE 1 LINES
(@idArticulos, codigo, @dummy1, descripcion, precio, costo, @PAN_ART, @CAN_ART, @fechaPrecio, @FAN_ART, @subFamiliaId,
 @familiaId, @DTO_ART, @OBS_ART, @UBI_ART, @unidad, stock, @dummy8, @dummy9, @dummy10, iva, presentacion, @KG_LTS,
 @PRE_UNI, @POR_MON, @COMISION, @STK_MIN, @SUB_FAM, @PENDIENTE, @NRO_OT, moneda, @FEM_OP, @LOT_REP, @STK_MAX,
 @TIE_PRO, @PRO_SUG, @PTO_REP, @SI_MRP, @STK_TRA, @AUT_MAN, @NRO_DEP, @CLI_ART, @RUT_PLA, @FUV_ART, @FUC_ART,
 @COS_PRO, @FCO_PRO, @ULT_PRO, @FEC_VIG, @PROD_TER, utilidadPretendida, @CPE_CDO, @PRO_SUG2, @PRO_SUG3, @PRE_OOB,
 @COS_OOB)
SET fechaPrecio = IF(char_length(trim(@fechaPrecio)) > 0, str_to_date(@fechaPrecio, '%d/%m/%Y'),
					NULL),
	subFamiliaId = NULL,
    idArticulos = NULL,
    unidad = if(char_length(@unidad) > 0, @unidad, NULL);

