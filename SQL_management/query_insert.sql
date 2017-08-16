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
DROP PROCEDURE IF EXISTS new_log|
DROP PROCEDURE IF EXISTS new_generic_aircraft|
DROP PROCEDURE IF EXISTS new_manual|
DROP PROCEDURE IF EXISTS new_generic_sub_task|
DROP PROCEDURE IF EXISTS new_generic_part|
DROP PROCEDURE IF EXISTS new_link_gp_gst|
DROP PROCEDURE IF EXISTS add_link_gp_gst|
DROP PROCEDURE IF EXISTS new_customer|
DROP PROCEDURE IF EXISTS new_engine|
DROP PROCEDURE IF EXISTS new_helicopter|
DROP PROCEDURE IF EXISTS new_project|
DROP PROCEDURE IF EXISTS new_work_order|
DROP PROCEDURE IF EXISTS new_subtask|
DROP PROCEDURE IF EXISTS new_project_part|
DROP PROCEDURE IF EXISTS new_link_pp_s|
DROP PROCEDURE IF EXISTS new_vendor|
DROP PROCEDURE IF EXISTS new_user|
DROP PROCEDURE IF EXISTS add_part_stock|
DROP PROCEDURE IF EXISTS add_parts_in_subtask_from_gst|
DROP PROCEDURE IF EXISTS new_recorded_work|
DROP PROCEDURE IF EXISTS new_user_work|

-- Create procedures
DROP PROCEDURE IF EXISTS create_project|

-- ************************************************************************************************ --
-- ----------------------------- Create Procedure ------------------------------------------------- --
-- ************************************************************************************************ --

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_log
-- Info: Insert a new log
-- Parameters: [IN l_type VARCHAR(16), IN l_target INT, IN l_function VARCHAR(64), IN l_parameters LONGTEXT, IN l_info MEDIUMTEXT]
-- Return: Na
-- ---

CREATE PROCEDURE new_log(IN l_type VARCHAR(16), IN l_target INT, IN l_function VARCHAR(64), IN l_parameters LONGTEXT, IN l_info MEDIUMTEXT)
  -- Parameters def
  BEGIN
  -- Content
    INSERT INTO T_LOG(`LOG_ID`,`LOG_EVENT_TIME`,`LOG_USER`,`LOG_TYPE`,`LOG_ID_TARGET`,`LOG_FUNCTION`,`LOG_PARAMETERS`,`LOG_INFO`) VALUES
      (NULL, NOW(), USER(), l_type, l_target,l_function, l_parameters, l_info);
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_generic_aircraft
-- Info: add a new generic aircraft
-- Parameters: [IN name VARCHAR(32), IN constructor VARCHAR(32), IN type VARCHAR(8)]
-- Set: @last_insert
-- Return: Na
-- ---

CREATE PROCEDURE new_generic_aircraft(IN name VARCHAR(32), IN constructor VARCHAR(32), IN type VARCHAR(8))
  -- Parameters def
  BEGIN
    -- Content
    INSERT INTO T_GENERIC_AIRCRAFT (`GA_ID`,`GA_NAME`,`GA_CONSTRUCTOR`,`GA_TYPE`) VALUES
      (NULL,name,constructor,type);
    SET @last_insert := LAST_INSERT_ID();
    CALL new_log('Insert', @last_insert,'new_generic_aircraft', CONCAT(name,'::',constructor,'::',type), 'New GA');

    CALL new_manual(@last_insert, NULL, 'NO MANUAL', 'N/A', 'For sub-task without any manual reference');

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_manual
-- Info: add a new manual
-- Parameters: [IN id_ga INT, IN id_manual INT, IN name VARCHAR(32), IN reference VARCHAR(16), IN description MEDIUMTEXT]
-- Return: Na
-- ---

CREATE PROCEDURE new_manual(IN id_ga INT, IN id_manual INT, IN name VARCHAR(128), IN reference VARCHAR(32), IN description MEDIUMTEXT)
  -- Parameters def
  BEGIN
    -- Content
    INSERT INTO T_MANUAL (`M_ID`,`M_ID_GA`,`M_ID_MANUAL`,`M_NAME`,`M_REFERENCE`,`M_DESCRIPTION`) VALUES
      (NULL,id_ga,id_manual,name,reference,description);
    CALL new_log('Insert', last_insert_id(),'new_manual', CONCAT(id_ga,'::',id_manual,'::',name,'::',reference,'::',description), 'New MAN');

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_generic_sub_task
-- Info: add a new generic_sub_task
-- Parameters: [IN id_m INT, IN number VARCHAR(16), IN description MEDIUMTEXT]
-- Set: @last_insert
-- Return: Na
-- ---

CREATE PROCEDURE new_generic_sub_task(IN id_m INT, IN number VARCHAR(16), IN description MEDIUMTEXT)
  -- Parameters def
  BEGIN
    -- Content

    INSERT INTO T_GENERIC_SUB_TASK (`GST_ID`,`GST_ID_M`,`GST_NUMBER`,`GST_DESCRIPTION`) VALUES
      (NULL,id_m,number,description);

    SET @last_insert := LAST_INSERT_ID();
    CALL new_log('Insert', @last_insert,'new_generic_sub_task', CONCAT(CONVERT(id_m,CHAR),'::',number,'::',description), 'New GST');

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_generic_part
-- Info: add a new generic part and check if part number already exists
-- Parameters: [IN number MEDIUMTEXT, IN name VARCHAR(32), IN type VARCHAR(16), IN location VARCHAR(32), IN description MEDIUMTEXT]
-- Set: @last_insert
-- Return: Na
-- ---

CREATE PROCEDURE new_generic_part(IN number MEDIUMTEXT, IN name VARCHAR(32), IN type VARCHAR(16), IN location VARCHAR(32), IN description MEDIUMTEXT)
  -- Parameters def
  BEGIN
    DECLARE id_gp INT DEFAULT 0;
    -- Content
    SELECT GP_ID INTO id_gp FROM T_GENERIC_PART
      WHERE INSTR(CONCAT(';',CONCAT(REPLACE(GP_NUMBER,'-',''),';')), CONCAT(';',CONCAT(REPLACE(number,'-',''),';')));

    IF id_gp<1 THEN
      INSERT INTO T_GENERIC_PART (`GP_ID`,`GP_NUMBER`,`GP_NAME`,`GP_TYPE`,`GP_LOCATION`,`GP_DESCRIPTION`) VALUES
        (NULL,number,name,type,location,description);

      SELECT @last_insert := LAST_INSERT_ID();
      CALL new_log('Insert', @last_insert,'new_generic_part', CONCAT(number,'::',name,'::',type,'::',description), 'New GP');
    ELSE
      SELECT @last_insert := id_gp;
    END IF;
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_link_gp_gst
-- Info: add a new link between gp and gst, gp needs to exists
-- Parameters: [IN id_gp INT, IN id_gst INT, IN qty INT, IN ipc_lgg VARCHAR(32)]
-- Return: Na
-- ---

CREATE PROCEDURE new_link_gp_gst(IN id_gp INT, IN id_gst INT, IN qty INT, IN ipc_lgg VARCHAR(32))
  BEGIN
    -- Parameters def
    DECLARE id_lgg INT DEFAULT 0;

    -- Content
    SELECT LGG_ID INTO id_lgg FROM T_LINK_GP_GST
      WHERE LGG_ID_GP=id_gp AND LGG_ID_GST=id_gst;

    IF id_lgg>0 THEN
      UPDATE T_LINK_GP_GST SET LGG_QTY=qty, LGG_IPC=ipc_lgg WHERE LGG_ID=id_lgg;
    ELSE
      INSERT INTO T_LINK_GP_GST (`LGG_ID`,`LGG_ID_GP`,`LGG_ID_GST`,`LGG_QTY`,`LGG_IPC`) VALUES
        (NULL,id_gp,id_gst, qty, ipc_lgg);
      CALL new_log('Insert', last_insert_id(),'new_link_gp_gst', CONCAT(id_gp,'::',id_gst), 'New LGG');
    END IF;
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- add_link_gp_gst
-- Info: add a new link between gp and gst and create new generic part if needed
-- Parameters: [IN id_gp INT, IN id_gst INT, IN quantity INT]
-- Return: Na
-- ---


CREATE PROCEDURE add_link_gp_gst(IN id_gp INT, IN id_gst INT, IN qty INT, IN ipc_lgg VARCHAR(32), IN name VARCHAR(13), IN number MEDIUMTEXT, IN type VARCHAR(16), IN location VARCHAR(32), IN description MEDIUMTEXT)
  -- Parameters
  BEGIN
    -- Content
    IF id_gp > 0 THEN
      CALL new_link_gp_gst(id_gp, id_gst, qty, ipc_lgg);
    ELSE
      IF number IS NOT NULL THEN
        CALL new_generic_part(number,name,type,location,description);

        CALL new_log('Insert', @last_insert,'add_link_gp_gst', CONCAT(number,'::',name,'::',type,'::',description), 'Add LGG');

        CALL new_link_gp_gst(@last_insert, id_gst, qty, ipc_lgg);
      ELSE
        SIGNAL SQLSTATE 'ERR0R'
        SET
        MESSAGE_TEXT = 'part number is null',
        MYSQL_ERRNO = 'LGG : GP NUMBER NULL';
      END IF;
    END IF;

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_customer
-- Info: add a new customer
-- Parameters: [IN name VARCHAR(32), IN phone VARCHAR(20), IN mail VARCHAR(128)]
-- Return: Na
-- ---


CREATE PROCEDURE new_customer(IN name VARCHAR(32), IN phone VARCHAR(20), IN mail VARCHAR(128))
  -- Parameters def
  BEGIN
    -- Content
    INSERT INTO T_CUSTOMER (`C_ID`,`C_NAME`,`C_PHONE`,`C_MAIL`) VALUES
      (NULL, name,phone,mail);

    SET @last_insert := last_insert_id();
    CALL new_log('Insert', @last_insert,'new_customer', CONCAT(name,'::',phone,'::',mail), 'New Customer');

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_engine
-- Info: add a new engine
-- Parameters: [IN id_ga INT, IN serial VARCHAR(32), IN total_time DECIMAL(10,2), IN ng_cycle DECIMAL(10,1), IN nf_cycle DECIMAL(10,1), IN id_log_book INT]
-- Return: Na
-- ---


CREATE PROCEDURE new_engine(IN id_ga INT, IN serial VARCHAR(32), IN total_time DECIMAL(10,2), IN ng_cycle DECIMAL(10,1), IN nf_cycle DECIMAL(10,1), IN id_log_book INT)
  -- Parameters def
  BEGIN
    -- Content
    INSERT INTO T_ENGINE (`E_ID`,`E_ID_GA`,`E_ID_LOG_BOOK`,`E_SERIAL`,`E_NAME`,`E_TYPE`,`E_TIME`,`E_NG_CYCLE`,`E_NF_CYCLE`) VALUES
      (NULL,id_ga,id_log_book,serial,NULL,NULL,total_time,ng_cycle,nf_cycle);

    SET @last_insert := last_insert_id();
    CALL new_log('Insert', @last_insert,'new_engine', CONCAT(id_ga,'::',serial,'::',total_time,'::',id_log_book), 'New Engine');

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_helicopter
-- Info: add a new helicopter
-- Parameters: [IN id_ga INT, IN id_e1 INT, IN id_e2 INT, IN id_log_book INT, IN id_status_sheet INT, IN serial VARCHAR(32), IN time DECIMAL(10,2), IN landing INT]
-- Return: Na
-- ---


CREATE PROCEDURE new_helicopter(IN id_ga INT, IN id_e1 INT, IN id_e2 INT, IN id_log_book INT, IN id_status_sheet INT, IN serial VARCHAR(32), IN time DECIMAL(10,2), IN landing INT)
  -- Parameters def
  BEGIN
    -- Content
    INSERT INTO T_HELICOPTER (`H_ID`,`H_ID_GA`,`H_ID_E1`,`H_ID_E2`,`H_ID_LOG_BOOK`,`H_ID_STATUS_SHEET`,`H_SERIAL`,`H_TIME`,`H_LANDING`) VALUES
      (NULL,id_ga,id_e1,id_e2,id_log_book,id_status_sheet,serial,time,landing);

    SET @last_insert := last_insert_id();
    CALL new_log('Insert', @last_insert,'new_helicopter', CONCAT(id_ga,'::',id_e1,'::',id_e2,'::',id_log_book,'::',id_status_sheet,'::',serial,'::',time,'::',landing), 'New Helicopter');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_project
-- Info: add a new project
-- Parameters: [IN name VARCHAR(32), IN id_customer INT, IN id_helicopter INT, IN opened_date DATE]
-- Return: Na
-- ---


CREATE PROCEDURE new_project(IN name VARCHAR(32), IN id_customer INT, IN id_helicopter INT, IN opened_date DATE)
  -- Parameters def
  BEGIN
    -- Content
    INSERT INTO T_PROJECT (`P_ID`,`P_ID_C`,`P_ID_H`,`P_NAME`,`P_OPENED_DATE`,`P_CLOSED_DATE`) VALUES
      (NULL,id_customer,id_helicopter,name,opened_date,NULL);
    SET @last_insert := LAST_INSERT_ID();
    CALL new_log('Insert', @last_insert,'new_project', CONCAT(name,'::',CONVERT(id_customer,CHAR),'::',CONVERT(id_helicopter,CHAR),'::',CONVERT(opened_date,CHAR)), 'New Project');

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_work_order
-- Info: add a new work order
-- Parameters: [IN id_p INT, IN name VARCHAR(128), IN opened_date DATE]
-- Return: Na
-- ---


CREATE PROCEDURE new_work_order(IN id_p INT, IN name VARCHAR(128), IN opened_date DATE)
  -- Parameters def
  BEGIN
    DECLARE id_h INT DEFAULT NULL;
    SELECT P_ID_H INTO id_h FROM T_PROJECT WHERE P_ID=id_p;

    INSERT INTO T_WORK_ORDER (`WO_ID`,`WO_ID_H`,`WO_NAME`,`WO_OPENED_DATE`,`WO_CLOSED_DATE`) VALUES
      (NULL,id_h,name,opened_date,NULL);
    CALL new_log('Insert', last_insert_id(),'new_work_order', CONCAT(CONVERT(id_p,CHAR),'::',name,'::',CONVERT(opened_date,CHAR)), 'New WO');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- create_project
-- Info: Create a project and add all the elements for it
-- Parameters: [IN p_name VARCHAR(32), IN opened_date DATE,
--              IN c_name VARCHAR(32), IN c_phone VARCHAR(20), IN c_mail VARCHAR(128),
--              IN h_id_ga INT, IN h_id_log_book INT, IN h_id_status_sheet INT, IN h_serial VARCHAR(32), IN h_total_time DECIMAL(10,2), IN h_landing INT,
--              IN e1_id_ga INT, IN e1_serial VARCHAR(32), IN e1_total_time DECIMAL(10,2), IN e1_ng_cycle DECIMAL(10,1), IN e1_nf_cycle DECIMAL(10,1), IN e1_log_book INT,
--              IN e2_id_ga INT, IN e2_serial VARCHAR(32), IN e2_total_time DECIMAL(10,2), IN e2_ng_cycle DECIMAL(10,1), IN e2_nf_cycle DECIMAL(10,1), IN e2_log_book INT]
-- Return: Na
-- ---


CREATE PROCEDURE  create_project(IN p_name VARCHAR(32), IN opened_date DATE,
                                 IN c_name VARCHAR(32), IN c_phone VARCHAR(20), IN c_mail VARCHAR(128),
                                 IN h_id_ga INT, IN h_id_log_book INT, IN h_id_status_sheet INT, IN h_serial VARCHAR(32), IN h_total_time DECIMAL(10,2), IN h_landing INT,
                                 IN e1_id_ga INT, IN e1_serial VARCHAR(32), IN e1_total_time DECIMAL(10,2), IN e1_ng_cycle DECIMAL(10,1), IN e1_nf_cycle DECIMAL(10,1), IN e1_log_book INT,
                                 IN e2_id_ga INT, IN e2_serial VARCHAR(32), IN e2_total_time DECIMAL(10,2), IN e2_ng_cycle DECIMAL(10,1), IN e2_nf_cycle DECIMAL(10,1), IN e2_log_book INT)
  -- Parameters def
  BEGIN
    -- Content
    DECLARE id_customer INT DEFAULT NULL;
    DECLARE id_helicopter INT DEFAULT NULL;
    DECLARE id_e1 INT DEFAULT NULL;
    DECLARE id_e2 INT DEFAULT NULL;
    -- Create Customer
    CALL new_customer(c_name, c_phone, c_mail);
    SET id_customer = @last_insert;
    -- Create Engine 1
    CALL new_engine(e1_id_ga, e1_serial, e1_total_time, e1_ng_cycle, e1_nf_cycle, e1_log_book);
    SET id_e1 = @last_insert;
    -- Create Engine 2
    IF e2_id_ga > 0 THEN
      CALL new_engine(e2_id_ga, e2_serial, e2_total_time, e2_ng_cycle, e2_nf_cycle, e2_log_book);
      SET id_e2 = @last_insert;
    ELSE
      SET id_e2 = NULL;
    END IF;
    -- Create Helicopter
    CALL new_helicopter(h_id_ga, id_e1, id_e2, h_id_log_book, h_id_status_sheet, h_serial, h_total_time, h_landing);
    SET id_helicopter=@last_insert;
    -- Create Project
    CALL new_project(p_name, id_customer, id_helicopter, opened_date);

    CALL new_log('Insert', last_insert_id(),'create_project',NULL, 'Create Project, check previous logs');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_subtask
-- Info: add a new subtask. If new gst required, it will add it too
-- Parameters: [IN id_wo INT, IN id_m INT, IN id_gst INT, IN reference VARCHAR(32), IN required MEDIUMTEXT, IN rectification MEDIUMTEXT, IN approved DATE, IN in_sow VARCHAR(3), IN id_user INT,
--              IN new_number VARCHAR(16), IN new_description MEDIUMTEXT,
--              IN s_references MEDIUMTEXT, IN s_remove MEDIUMTEXT, IN s_install MEDIUMTEXT]
-- Return: Na
-- ---

CREATE PROCEDURE new_subtask( IN id_wo INT, IN id_m INT, IN id_gst INT, IN reference VARCHAR(32), IN required MEDIUMTEXT, IN rectification MEDIUMTEXT, IN approved DATE, IN in_sow VARCHAR(3), IN id_user INT,
                              IN new_number VARCHAR(16), IN new_description MEDIUMTEXT,
                              IN s_references MEDIUMTEXT, IN s_remove MEDIUMTEXT, IN s_install MEDIUMTEXT)
  -- Parameters Def
  BEGIN
    -- Content
    IF id_gst < 1 THEN
      CALL new_generic_sub_task(id_m, new_number, new_description);
      SET id_gst=@last_insert;
    ELSE
      SELECT GST_DESCRIPTION INTO new_description FROM T_GENERIC_SUB_TASK
      WHERE GST_ID=id_gst;
    END IF;

    IF required = '' THEN
      SET required = NULL;
    END IF;

    INSERT INTO T_SUB_TASK (`ST_ID`,`ST_ID_WO`,`ST_ID_GST`,`ST_ID_U_PERFORMER`,`ST_ID_U_RELEASER`,`ST_REFERENCE`,`ST_WORK_REQUIRED`,`ST_RECTIFICATION_DETAILS`,`ST_IN_SOW`,`ST_APPROVED_DATE`,`ST_PERFORM_DATE`,`ST_RELEASE_DATE`,`ST_PRICE_QUOTE`, `ST_ACCURRENCY`, `ST_S_REFERENCES`, `ST_S_REMOVE`, `ST_S_INSTALL`) VALUES
      (NULL,id_wo,id_gst,NULL,NULL,reference,COALESCE(required,new_description),rectification,in_sow,approved,NULL,NULL,NULL,NULL,s_references,s_remove,s_install);

    SET @last_insert = LAST_INSERT_ID();
    CALL new_log('Insert', @last_insert,'new_subtask', CONCAT(id_wo,'::',id_m,'::',id_gst,'::',reference,'::',required,'::',rectification,'::',approved,'::',in_sow), 'New SubTask');

    IF approved = 'yes' THEN
      CALL add_parts_in_subtask_from_gst(id_gst, @last_insert, id_user);
    END IF;
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_project_part
-- Info: add a new project part. Create generic part if needed
-- Parameters: [IN id_st INT, IN quantity INT, IN id_gp INT, IN id_user INT, IN f_ipc VARCHAR(32), IN is_defect VARCHAR(3),
--              IN name_gp VARCHAR(32), IN number_gp MEDIUMTEXT, IN gp_type VARCHAR(16), IN gp_location VARCHAR(32), IN gp_description MEDIUMTEXT]
-- Return: Na
-- ---

CREATE PROCEDURE new_project_part( IN id_st INT, IN quantity INT, IN id_gp INT, IN id_user INT, IN f_ipc VARCHAR(32), IN is_defect VARCHAR(3),
                                  IN name_gp VARCHAR(32), IN number_gp MEDIUMTEXT, IN gp_type VARCHAR(16), IN gp_location VARCHAR(32), IN gp_description MEDIUMTEXT)
  -- Parameters Def
  BEGIN
    DECLARE id_gst INT DEFAULT 0;

    IF id_gp < 1 THEN
      CALL new_generic_part(number_gp, name_gp, gp_type, gp_location, gp_description);
      SET id_gp=@last_insert;
    END IF;

    IF is_defect!='yes' THEN
      SELECT ST_ID_GST INTO id_gst FROM T_SUB_TASK WHERE ST_ID=id_st;

      CALL new_link_gp_gst(id_gp, id_gst, quantity, f_ipc);
    END IF;

  -- Content
    INSERT INTO T_PROJECT_PART (`PP_ID`,`PP_ID_ST`,`PP_ID_GP`,`PP_ID_USER`,`PP_DEFECT`,`PP_IPC`,`PP_STATUS`,`PP_VALIDATED`,`PP_QUANTITY_REQUESTED`,`PP_REQUESTED_DATE`) VALUES
      (NULL,id_st,id_gp,id_user,is_defect,f_ipc,'required',DEFAULT,quantity,curdate());
    CALL new_log('Insert', last_insert_id(),'new_part_in_subtask', CONCAT(CONVERT(id_st,CHAR),'::',CONVERT(quantity,CHAR),'::',CONVERT(id_gp,CHAR),'::',CONVERT(id_user,CHAR),'::',f_ipc), 'New Part in SubTask');

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_link_pp_s
-- Info: add a new link between a project part and stock part
-- Parameters: [IN id_pp INT, IN id_s INT, IN fe_pp_qty_dlv INT]
-- Return: Na
-- ---

CREATE PROCEDURE new_link_pp_s(IN id_pp INT, IN id_s INT, IN fe_pp_qty_dlv INT)
  -- Parameters Def
  BEGIN
    -- Content
    INSERT INTO T_LINK_PP_S(`LPS_ID`,`LPS_ID_PP`,`LPS_ID_S`,`LPS_QUANTITY_NUMBER`,`LPS_DATE_OUT`) VALUES
      (NULL, id_pp, id_s, fe_pp_qty_dlv, NULL);
    SET @last_insert = LAST_INSERT_ID();
    CALL new_log('Insert', @last_insert,'new_link_pp_s', CONCAT(CONVERT(id_pp,CHAR),'::',CONVERT(id_s,CHAR),'::',CONVERT(fe_pp_qty_dlv,CHAR)),'Insert new LPS');

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- add_part_stock
-- Info: add a part to stock. Create new generic part if needed
-- Parameters: [IN id_gp INT, IN arc_name VARCHAR(512), IN po_name VARCHAR(512), IN arc INT, IN po INT, IN index_pn INT,
--              IN qty_number INT, IN serial VARCHAR(32), IN u_price DECIMAL(10,2), IN currency VARCHAR(8), IN vendor VARCHAR(128), IN received DATE, IN expiration DATE,
--              IN new_gp_name VARCHAR(32), IN new_gp_number MEDIUMTEXT, IN new_gp_type VARCHAR(16), IN new_gp_location VARCHAR(32), IN new_gp_description MEDIUMTEXT]
-- Return: Na
-- ---

CREATE PROCEDURE add_part_stock(IN id_gp INT, IN arc_name VARCHAR(512), IN po_name VARCHAR(512), IN arc INT, IN po INT, IN index_pn INT,
                                IN qty_number INT, IN serial VARCHAR(32), IN u_price DECIMAL(10,2), IN currency VARCHAR(8), IN vendor VARCHAR(128), IN received DATE, IN expiration DATE,
                                IN new_gp_name VARCHAR(32), IN new_gp_number MEDIUMTEXT, IN new_gp_type VARCHAR(16), IN new_gp_location VARCHAR(32), IN new_gp_description MEDIUMTEXT)
  -- Parameters Def
  BEGIN
    -- Content
    DECLARE isReceived VARCHAR(3) DEFAULT 'no';
    IF received IS NOT NULL THEN
      SET isReceived = 'yes';
    END IF;

    IF id_gp < 1 THEN
      CALL new_generic_part(new_gp_number, new_gp_name, new_gp_type, new_gp_location, new_gp_description);
      SET id_gp = @last_insert;
    END IF;

    INSERT INTO T_STOCK (`S_ID`,`S_ID_GP`,`S_ID_PURCHASE_ORDER`,`S_ID_ARC`,`S_INDEX_PN`,`S_SERIAL`,`S_ARC_NAME`,`S_PO_NAME`,`S_QUANTITY_IN`,`S_QUANTITY_NUMBER`,`S_RECEIVED`,`S_RECEIVED_DATE`,`S_EXPIRATION_DATE`,`S_PRICE`,`S_ACCURENCY`,`S_VENDOR`,`S_COMMENT`) VALUES
      (NULL,id_gp,po,arc,index_pn,serial,arc_name,po_name,qty_number,qty_number,isReceived,received,expiration,u_price,currency,vendor,NULL);
    SET @last_insert := LAST_INSERT_ID();
    CALL new_log('Insert', @last_insert,'add_part_stock', CONCAT(CONVERT(id_gp,CHAR),'::',CONVERT(po,CHAR),'::',CONVERT(arc,CHAR),'::',serial,'::',arc_name,'::',po_name,'::',CONVERT(qty_number,CHAR),'::',CONVERT(received,CHAR),'::',CONVERT(expiration,CHAR),'::',CONVERT(u_price,CHAR),'::',currency,'::',vendor),'Insert new Stock Part');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_vendor
-- Info: add a new vendor
-- Parameters: [IN name VARCHAR(128), IN address VARCHAR(512), IN city VARCHAR(128), IN country VARCHAR(128), IN phone VARCHAR(16), IN mail VARCHAR(128), IN contact VARCHAR(128)]
-- Return: Na
-- ---

CREATE PROCEDURE new_vendor(IN name VARCHAR(128), IN address VARCHAR(512), IN city VARCHAR(128), IN country VARCHAR(128), IN phone VARCHAR(16), IN mail VARCHAR(128), IN contact VARCHAR(128))
  -- Parameters Def
BEGIN
  -- Content
  INSERT INTO T_VENDOR (`V_NAME`, `V_ADDRESS`, `V_CITY`, `V_COUNTRY`, `V_PHONE`, `V_MAIL`, `V_CONTACT`) VALUES
    (name, address, city, country, phone, mail, contact);
  SET @last_insert := LAST_INSERT_ID();
  CALL new_log('Insert', @last_insert,'add_vendor', CONCAT(name,'::',address,'::',city,'::',country,'::',phone,'::',mail,'::',contact),'Insert new Vendor');

END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_user
-- Info: add a new user
-- Parameters: [IN login VARCHAR(16), IN password VARCHAR(64), IN status INT, IN code VARCHAR(16)]
-- Return: Na
-- ---

CREATE PROCEDURE new_user(IN login VARCHAR(16), IN password VARCHAR(64), IN status INT, IN code VARCHAR(16))
  -- Parameters Def
BEGIN
  -- Content
  INSERT INTO T_USER (`U_ID`,`U_NAME`,`U_PASSWORD`,`U_STATUS`,`U_CODE`) VALUES
    (NULL, login, password, status, code);
  SET @last_insert := LAST_INSERT_ID();
  CALL new_log('Insert', @last_insert, 'add_user',login,'insert_new_user');
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- add_parts_in_subtask_from_gst
-- Info: add a new project part for a sub task
-- Parameters: [IN id_gst INT, IN id_st INT, IN id_user INT]
-- Return: Na
-- ---

CREATE PROCEDURE add_parts_in_subtask_from_gst(IN id_gst INT, IN id_st INT, IN id_user INT)
  -- Parameters Def
BEGIN
  -- Content
  DECLARE done INT DEFAULT FALSE;
  DECLARE ipc CHAR(32);
  DECLARE id_gp, qty INT;
  DECLARE cur CURSOR FOR SELECT LGG_ID_GP, LGG_QTY, LGG_IPC FROM T_LINK_GP_GST WHERE LGG_ID_GST=id_gst;
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  OPEN cur;

  exec_loop: LOOP
    FETCH cur INTO id_gp, qty, ipc;
    IF done THEN
      LEAVE exec_loop;
    END IF;
    INSERT INTO T_PROJECT_PART(`PP_ID`,`PP_ID_ST`,`PP_ID_GP`,`PP_ID_USER`,`PP_DEFECT`,`PP_IPC`,`PP_STATUS`,`PP_VALIDATED`,`PP_QUANTITY_REQUESTED`,`PP_REQUESTED_DATE`) VALUES
      (NULL,id_st,id_gp,id_user,'no',ipc,'required',DEFAULT,qty,curdate());
  END LOOP;

  CLOSE cur;

END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_recorded_work
-- Info: add a new recorded work. Create as DAILY or linked to a sub task
-- Parameters: [IN id_st INT, IN description MEDIUMTEXT]
-- Return: Na
-- ---

CREATE PROCEDURE new_recorded_work(IN id_st INT, IN description MEDIUMTEXT)
  -- Parameters Def
  BEGIN
    -- Content
    DECLARE record INT DEFAULT 0;

    SELECT RW_ID INTO record FROM T_RECORDED_WORK
      WHERE RW_ID_ST=id_st;

    IF record > 0 THEN
      SIGNAL SQLSTATE 'ERR0R'
      SET MESSAGE_TEXT = 'Recorded work for this ST already exist',
      MYSQL_ERRNO = '1337';
    ELSE
      IF id_st < 1 THEN
        INSERT INTO T_RECORDED_WORK(`RW_ID`, `RW_ID_ST`, `RW_DESCRIPTION`, RW_TOTAL_MIN) VALUES
          (NULL, NULL, description, DEFAULT);
      ELSE
        INSERT INTO T_RECORDED_WORK(`RW_ID`, `RW_ID_ST`, `RW_DESCRIPTION`, RW_TOTAL_MIN) VALUES
          (NULL, id_st, NULL, DEFAULT);
      END IF;
    END IF;

    SET @last_insert := LAST_INSERT_ID();

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- new_user_work
-- Info: add a new user work to a recorded work
-- Parameters: [IN id_rw INT, IN id_user INT]
-- Return: Na
-- ---

CREATE PROCEDURE new_user_work(IN id_rw INT, IN id_user INT)
  BEGIN
    DECLARE id_uw INT DEFAULT NULL;

    SELECT UW_ID INTO id_uw FROM T_USER_WORK
      WHERE UW_ID_U=id_user AND UW_DATETIME_END IS NULL;

    IF id_uw IS NOT NULL THEN
      CALL close_user_work(id_uw);
    END IF;

    INSERT INTO T_USER_WORK (`UW_ID`, `UW_ID_RW`, `UW_ID_U`, `UW_DATETIME_BEGIN`, `UW_DATETIME_END`, `UW_TOTAL_MIN`) VALUES
      (NULL, id_rw, id_user, CURRENT_TIMESTAMP, DEFAULT, DEFAULT);
  END |