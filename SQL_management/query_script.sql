-- ************************************************************************************************ --
-- ----------------------------- Documentation format --------------------------------------------- --
-- ************************************************************************************************ --

-- ---
-- <Query_Name>
-- Info: <Query_Infos>
-- Parameters: [<IN|OUT|INOUT> <Columns_Name> <TYPE> '<Comment>' ,.. ]
-- Return: <Format_Info> [<Columns_Name>] '<Alias>' ,.. ]
-- ---

-- ---
-- Queries List
-- ---

-- inPhpMyAdmin you have to set delimiter to | in interface
-- Set delimiter to | (unquote to use outside phpMyAdmin)
DELIMITER |

-- ************************************************************************************************ --
-- ----------------------------- Drop existing Queries -------------------------------------------- --
-- ************************************************************************************************ --

-- Insert procedure
DROP PROCEDURE IF EXISTS import_old_to_new_stock|

-- ************************************************************************************************ --
-- ----------------------------- Create Procedure ------------------------------------------------- --
-- ************************************************************************************************ --

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_log
-- Info: Insert a new log
-- Parameters: [IN name VARCHAR(32), IN constructor VARCHAR(32), IN type VARCHAR(8)]
-- Return: Na
-- ---

CREATE PROCEDURE import_old_to_new_stock(
  IN new_number VARCHAR(16), IN new_alt_number VARCHAR(16), IN new_name VARCHAR(32), IN new_type VARCHAR(16), IN new_location VARCHAR(32), IN new_description MEDIUMTEXT,
  IN qty_in INT, IN qty_number INT, IN serial VARCHAR(32), IN u_price FLOAT, IN currency VARCHAR(8), IN vendor VARCHAR(32), IN received DATE, IN expiration DATE,
  IN arc_name VARCHAR(512), IN arc_id INT, IN po_name VARCHAR(512), IN po_id INT
)
  BEGIN
    DECLARE v_gp_id INT DEFAULT 0;
    DECLARE isReceived VARCHAR(3) DEFAULT 'no';

    SELECT GP_ID INTO v_gp_id FROM T_GENERIC_PART WHERE GP_NUMBER=new_number;

    IF expiration = 0000-00-00 THEN
      SET expiration = NULL;
    END IF;
    IF received = 0000-00-00 THEN
      SET received = NULL;
    END IF;

    SET isReceived = 'yes';

    IF v_gp_id < 1 THEN
      CALL new_generic_part(new_number, new_alt_number, new_name, new_type, new_location, new_description);
      SET v_gp_id = @last_insert;
    END IF;

    INSERT INTO T_STOCK (`S_ID`,`S_ID_GP`,`S_ID_PURCHASE_ORDER`,`S_ID_ARC`,`S_SERIAL`,`S_ARC_NAME`,`S_PO_NAME`,`S_QUANTITY_IN`,`S_QUANTITY_NUMBER`,`S_RECEIVED`,`S_RECEIVED_DATE`,`S_EXPIRATION_DATE`,`S_PRICE`,`S_ACCURENCY`,`S_VENDOR`,`S_COMMENT`) VALUES
      (NULL,v_gp_id,po_id,arc_id,serial,arc_name,po_name,qty_in,qty_number,isReceived,received,expiration,u_price,currency,vendor,NULL);

  END |