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

-- Modify procedures
DROP PROCEDURE IF EXISTS change_password|
DROP PROCEDURE IF EXISTS update_project|
DROP PROCEDURE IF EXISTS update_customer|
DROP PROCEDURE IF EXISTS update_helicopter|
DROP PROCEDURE IF EXISTS update_engine|
DROP PROCEDURE IF EXISTS change_file_name|
DROP PROCEDURE IF EXISTS update_manual|
DROP PROCEDURE IF EXISTS update_generic_aircraft|
DROP PROCEDURE IF EXISTS update_generic_sub_task|
DROP PROCEDURE IF EXISTS update_generic_part|
DROP PROCEDURE IF EXISTS update_subtask|
DROP PROCEDURE IF EXISTS update_project_part|
DROP PROCEDURE IF EXISTS update_link_pp_s|
DROP PROCEDURE IF EXISTS update_stock_part|
DROP PROCEDURE IF EXISTS update_vendor|
DROP PROCEDURE IF EXISTS update_user|
DROP PROCEDURE IF EXISTS update_link_gp_gst|
DROP PROCEDURE IF EXISTS update_recorded_work|
DROP PROCEDURE IF EXISTS merge_generic_parts|
DROP PROCEDURE IF EXISTS toggle_user_work|
DROP PROCEDURE IF EXISTS toggle_user_work_on_st|

-- ************************************************************************************************ --
-- ----------------------------- Create Procedure ------------------------------------------------- --
-- ************************************************************************************************ --

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- change_password
-- Info: Set new password of user user_ID
-- Parameters: [IN user_ID INT, IN new_pass_hash VARCHAR(64)]
-- Return: Tab of columns [P_ID 'id', P_NAME 'p_name', C_NAME 'c_name', GA_NAME 'ga_name']
-- ---

CREATE PROCEDURE change_password(IN user_ID INT, IN new_pass_hash VARCHAR(64))
  -- Parameters def
  BEGIN
    -- Content
    UPDATE T_USER SET U_PASSWORD=new_pass_hash
    WHERE U_ID=user_ID;
    CALL new_log('Update', user_ID,'change_password', CONCAT(user_ID), 'Change password');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_project
-- Info: update project information
-- Parameters: [IN id_project INT, IN new_opened_date DATE, IN new_closed_date DATE]
-- Return: Na
-- ---

CREATE PROCEDURE update_project(IN id_project INT, IN new_opened_date DATE, IN new_closed_date DATE)
  -- Parameters def
  BEGIN
    -- Content
    UPDATE T_PROJECT SET P_OPENED_DATE=new_opened_date, P_CLOSED_DATE=new_closed_date
    WHERE P_ID=id_project;
    CALL new_log('Update', id_project,'update_project', CONCAT(id_project,'::',new_opened_date,'::',new_closed_date), 'Change password');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_customer
-- Info: update customer information
-- Parameters: [IN id_customer INT, IN new_name VARCHAR(32), IN new_phone VARCHAR(20), IN new_mail VARCHAR(128)]
-- Return: Na
-- ---

CREATE PROCEDURE update_customer(IN id_customer INT, IN new_name VARCHAR(32), IN new_phone VARCHAR(20), IN new_mail VARCHAR(128))
  -- Parameters def
  BEGIN
    -- Content
    UPDATE T_CUSTOMER SET C_NAME=new_name, C_PHONE=new_phone, C_MAIL=new_mail
    WHERE C_ID=id_customer;
    CALL new_log('Update', id_customer,'update_customer', CONCAT(id_customer,'::',new_name,'::',new_phone,'::',new_mail), 'Change password');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_helicopter
-- Info: update helicopter information
-- Parameters: [IN id_helicopter INT, IN new_serial VARCHAR(32), IN new_time DECIMAL(10,2), IN new_landing INT, IN new_status_sheet INT, IN new_log_book INT]
-- Return: Na
-- ---

CREATE PROCEDURE update_helicopter(IN id_helicopter INT, IN new_serial VARCHAR(32), IN new_time DECIMAL(10,2), IN new_landing INT, IN new_status_sheet INT, IN new_log_book INT)
  -- Parameters
  BEGIN
    -- Content
    DECLARE id_log_book INT;
    DECLARE id_status_sheet INT;
    SELECT H_ID_LOG_BOOK, H_ID_STATUS_SHEET INTO id_log_book, id_status_sheet FROM T_HELICOPTER WHERE H_ID=id_helicopter;

    IF new_log_book > 0 THEN
      SET id_log_book = new_log_book;
    END IF ;
    IF new_status_sheet > 0 THEN
      SET id_status_sheet = new_status_sheet;
    END IF;

    UPDATE T_HELICOPTER SET H_SERIAL=new_serial, H_TIME=new_time, H_LANDING=new_landing, H_ID_STATUS_SHEET=id_status_sheet, H_ID_LOG_BOOK=id_log_book
    WHERE H_ID=id_helicopter;
    CALL new_log('Update', id_helicopter,'update_helicopter', CONCAT(id_helicopter,'::',new_serial,'::',new_time,'::',new_landing,'::',new_status_sheet,'::',new_log_book), 'Change password');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_engine
-- Info: update engine information
-- Parameters: [IN id_engine INT, IN new_serial VARCHAR(32), IN new_time DECIMAL(10,2), IN new_ng_cycle DECIMAL(10,1), IN new_nf_cycle DECIMAL(10,1), IN new_log_book INT]
-- Return: Na
-- ---

CREATE PROCEDURE update_engine(IN id_engine INT, IN new_serial VARCHAR(32), IN new_time DECIMAL(10,2), IN new_ng_cycle DECIMAL(10,1), IN new_nf_cycle DECIMAL(10,1), IN new_log_book INT)
  -- Parameters
  BEGIN
    -- Content
    DECLARE id_log_book INT;
    SELECT E_ID_LOG_BOOK INTO id_log_book FROM T_ENGINE WHERE E_ID=id_engine;

    IF new_log_book > 0 THEN
      SET id_log_book=new_log_book;
    END IF;

    UPDATE T_ENGINE SET E_SERIAL=new_serial, E_TIME=new_time, E_NG_CYCLE=new_ng_cycle, E_NF_CYCLE=new_nf_cycle, E_ID_LOG_BOOK=id_log_book
    WHERE E_ID=id_engine;
    CALL new_log('Update', id_engine,'update_engine', CONCAT(CONVERT(id_engine,CHAR),'::',new_serial,'::',CONVERT(new_time,CHAR),'::',CONVERT(new_log_book,CHAR)), 'Update Engine');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- change_file_name
-- Info: change name of a file
-- Parameters: [IN id_file INT, IN new_filename VARCHAR(32)]
-- Return: Na
-- ---

CREATE PROCEDURE change_file_name(IN id_file INT, IN new_filename VARCHAR(32))
  -- Parameters
  BEGIN
    -- Content
    UPDATE T_FILE SET F_NAME=new_filename WHERE F_ID=id_file;
    CALL new_log('Update', id_file,'change_file_name', CONCAT(id_file,'::',new_filename), 'Change Filename');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_manual
-- Info: update manual information
-- Parameters: [IN id_manual INT, IN new_file INT, IN new_name VARCHAR(128), IN new_reference VARCHAR(32), IN new_description MEDIUMTEXT]
-- Return: Na
-- ---

CREATE PROCEDURE update_manual(IN id_manual INT, IN new_file INT, IN new_name VARCHAR(128), IN new_reference VARCHAR(32), IN new_description MEDIUMTEXT)
  -- Parameters
  BEGIN
    IF new_file IS NOT NULL THEN
      UPDATE T_MANUAL SET M_NAME=new_name, M_ID_MANUAL=new_file, M_REFERENCE=new_reference, M_DESCRIPTION=new_description
      WHERE M_ID=id_manual;
    ELSE
      UPDATE T_MANUAL SET M_NAME=new_name, M_REFERENCE=new_reference, M_DESCRIPTION=new_description
      WHERE M_ID=id_manual;
    END IF;
    CALL new_log('Update', id_manual,'update_engine', CONCAT(id_manual,'::',new_file,'::',new_name,'::',new_reference,'::',new_description), 'Update Manual');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_generic_aircraft
-- Info: update generic aircraft information
-- Parameters: [IN id_ga INT ,IN new_name VARCHAR(32), IN new_constructor VARCHAR(32)]
-- Return: Na
-- ---

CREATE PROCEDURE update_generic_aircraft(IN id_ga INT ,IN new_name VARCHAR(32), IN new_constructor VARCHAR(32))
  -- Parameters
  BEGIN
    UPDATE T_GENERIC_AIRCRAFT SET GA_NAME=new_name, GA_CONSTRUCTOR=new_constructor
    WHERE GA_ID=id_ga;
    CALL new_log('Update', id_ga,'update_generic_aircraft', CONCAT(id_ga,'::',new_name,'::',new_constructor), 'Update Generic Aircraft');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_generic_sub_task
-- Info: update generic sub task information
-- Parameters: [IN id_gst INT, IN new_number VARCHAR(16), IN new_description MEDIUMTEXT]
-- Return: Na
-- ---

CREATE PROCEDURE update_generic_sub_task(IN id_gst INT, IN new_number VARCHAR(16), IN new_description MEDIUMTEXT)
  -- Parameters
  BEGIN

    UPDATE T_GENERIC_SUB_TASK SET GST_NUMBER=new_number, GST_DESCRIPTION=new_description
    WHERE GST_ID=id_gst;
    CALL new_log('Update', id_gst,'update_engine', CONCAT(id_gst,'::',new_number,'::',new_description), 'Update Generic Aircraft');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_generic_part
-- Info: update generic part information
-- Parameters: [IN id_gp INT, IN new_number MEDIUMTEXT, IN new_name VARCHAR(32), IN new_type VARCHAR(16), IN new_location MEDIUMTEXT, IN new_description MEDIUMTEXT]
-- Return: Na
-- ---

CREATE PROCEDURE update_generic_part(IN id_gp INT, IN new_number MEDIUMTEXT, IN new_name VARCHAR(32), IN new_type VARCHAR(16), IN new_location MEDIUMTEXT, IN new_description MEDIUMTEXT)
  -- Paremeters Def
  BEGIN
    DECLARE id_check INT DEFAULT 0;

    -- Content
    UPDATE T_GENERIC_PART SET GP_NUMBER=new_number, GP_NAME=new_name, GP_TYPE=new_type, GP_LOCATION=new_location, GP_DESCRIPTION=new_description
    WHERE GP_ID=id_gp;
    CALL new_log('Update', id_gp,'update_generic_part', CONCAT(new_number,'::',new_name,'::',new_type,'::',new_description), 'Update Generic Part');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_subtask
-- Info: update subtask information. Will always select non void information between old and new
-- Parameters: [IN id_st INT, IN new_ref VARCHAR(32), IN new_approved DATE, IN new_required MEDIUMTEXT, IN new_rectification MEDIUMTEXT,
--              IN new_performed DATE, IN new_performer INT, IN new_released DATE, IN new_releaser INT, IN id_user INT,
--              IN new_s_references MEDIUMTEXT, IN new_s_remove MEDIUMTEXT, IN new_s_install MEDIUMTEXT]
-- Return: Na
-- ---

CREATE PROCEDURE update_subtask(IN id_st INT, IN new_ref VARCHAR(32), IN new_approved DATE, IN new_required MEDIUMTEXT, IN new_rectification MEDIUMTEXT,
                                IN new_performed DATE, IN new_performer INT, IN new_released DATE, IN new_releaser INT, IN id_user INT,
                                IN new_s_references MEDIUMTEXT, IN new_s_remove MEDIUMTEXT, IN new_s_install MEDIUMTEXT)
  -- Parameters
BEGIN
  DECLARE old_approved DATE DEFAULT NULL;
  DECLARE id_gst INT DEFAULT NULL;

  SELECT  ST_ID_GST, COALESCE(new_ref,ST_REFERENCE), ST_APPROVED_DATE, COALESCE(new_required, ST_WORK_REQUIRED), COALESCE(new_rectification, ST_RECTIFICATION_DETAILS),
          COALESCE(new_performed, ST_PERFORM_DATE), COALESCE(new_performer, ST_ID_U_PERFORMER), COALESCE(new_released, ST_RELEASE_DATE), COALESCE(new_releaser, ST_ID_U_RELEASER),
          COALESCE(new_s_references, ST_S_REFERENCES), COALESCE(new_s_remove, ST_S_REMOVE), COALESCE(new_s_install, ST_S_INSTALL)
  INTO id_gst, new_ref, old_approved, new_required, new_rectification, new_performed, new_performer, new_released, new_releaser, new_s_references, new_s_remove, new_s_install
  FROM T_SUB_TASK
  WHERE ST_ID=id_st;

  IF old_approved IS NULL AND new_approved IS NOT NULL THEN
    CALL add_parts_in_subtask_from_gst(id_gst, id_st, id_user);
  END IF;

  SET new_approved = COALESCE(new_approved, old_approved);

  IF new_performed IS NULL THEN
    SET new_performer = NULL;
  END IF;
  IF new_released IS NULL THEN
    SET new_releaser = NULL;
  END IF;
  -- Content
  UPDATE T_SUB_TASK
  SET ST_REFERENCE=new_ref, ST_APPROVED_DATE=new_approved, ST_WORK_REQUIRED=new_required,ST_RECTIFICATION_DETAILS=new_rectification,
      ST_PERFORM_DATE=new_performed, ST_ID_U_PERFORMER=new_performer, ST_RELEASE_DATE=new_released, ST_ID_U_RELEASER=new_releaser,
      ST_S_REFERENCES=new_s_references, ST_S_REMOVE=new_s_remove, ST_S_INSTALL=new_s_install
  WHERE ST_ID=id_st;
  CALL new_log('Update', id_st,'update_subtask', CONCAT(id_st,'::',new_ref,'::',new_approved,'::',new_required,'::',new_rectification,'::',new_performed,'::',new_performer,'::',new_released,'::',new_releaser), 'Update SubTask');
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_project_part
-- Info: update project part information
-- Parameters: [IN id_pp INT, IN new_pp_id_st INT, IN new_gp_number MEDIUMTEXT, IN new_pp_ipc VARCHAR(32), IN new_defect VARCHAR(3),
--              IN new_pp_user INT, IN new_pp_requested_date DATE, IN new_pp_quantity_requested INT, IN new_validated VARCHAR(3)]
-- Return: Na
-- ---

CREATE PROCEDURE update_project_part(IN id_pp INT, IN new_pp_id_st INT, IN new_gp_number MEDIUMTEXT, IN new_pp_ipc VARCHAR(32), IN new_defect VARCHAR(3),
                           IN new_pp_user INT, IN new_pp_requested_date DATE, IN new_pp_quantity_requested INT, IN new_validated VARCHAR(3))
  -- Parameters Def
  BEGIN
  -- Content
    DECLARE id_gp INT DEFAULT 0;
    DECLARE id_gst INT DEFAULT 0;
    DECLARE new_lgg_id_gst INT DEFAULT 0;
    DECLARE ipc_pp VARCHAR(32) DEFAULT NULL;
    DECLARE id_lgg INT DEFAULT 0;

    SELECT PP_ID_GP, ST_ID_GST, PP_IPC INTO id_gp, id_gst, ipc_pp FROM T_PROJECT_PART
    INNER JOIN T_SUB_TASK ON PP_ID_ST=ST_ID
    WHERE id_pp=PP_ID;

    SELECT LGG_ID INTO id_lgg FROM T_LINK_GP_GST
    WHERE LGG_ID_GST=id_gst AND LGG_ID_GP=id_gp AND LGG_IPC=ipc_pp;

    SELECT ST_ID_GST INTO new_lgg_id_gst FROM T_SUB_TASK
    WHERE ST_ID=new_pp_id_st;

    IF id_lgg > 0 AND new_lgg_id_gst > 0 THEN
      IF new_defect = 'no' THEN
        UPDATE T_LINK_GP_GST SET LGG_IPC=new_pp_ipc, LGG_ID_GST=new_lgg_id_gst, LGG_IPC=new_pp_ipc, LGG_QTY=new_pp_quantity_requested
        WHERE LGG_ID=id_lgg;
      ELSE
        DELETE FROM T_LINK_GP_GST WHERE LGG_ID=id_lgg;
      END IF;
    ELSEIF id_lgg = 0 AND new_defect = 'no' THEN
      CALL new_link_gp_gst(id_gp, id_gst, new_pp_quantity_requested, ipc_pp);
    END IF;

    IF new_validated IS NULL OR new_validated != 'yes' THEN
      SET new_validated = 'no';
    END IF;

    UPDATE T_GENERIC_PART SET GP_NUMBER=new_gp_number
    WHERE GP_ID=id_gp;
    UPDATE T_PROJECT_PART SET PP_ID_ST=new_pp_id_st, PP_DEFECT=new_defect, PP_IPC=new_pp_ipc, PP_ID_USER=new_pp_user, PP_REQUESTED_DATE=new_pp_requested_date, PP_QUANTITY_REQUESTED=new_pp_quantity_requested, PP_VALIDATED=new_validated
    WHERE PP_ID=id_pp;
    CALL new_log('Update', id_pp, 'update_project_part', CONCAT(CONVERT(id_pp,CHAR),'::',CONVERT(new_pp_id_st,CHAR),'::',new_gp_number,'::',new_pp_ipc,'::',CONVERT(new_pp_user,CHAR),'::',CONVERT(new_pp_requested_date,CHAR),'::',CONVERT(new_pp_quantity_requested,CHAR)), 'Update Project-Part');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_link_pp_s
-- Info: update link between project part and stock information
-- Parameters: [IN id_pp INT, IN id_s INT, IN fe_pp_qty_dlv INT]
-- Return: Na
-- ---

CREATE PROCEDURE update_link_pp_s(IN id_pp INT, IN id_s INT, IN fe_pp_qty_dlv INT)
  -- Paremeters Def
  BEGIN
    -- Content
    DECLARE id_lps INT DEFAULT 0;
    DECLARE delivered_qty INT DEFAULT 0;
    DECLARE used_qty INT DEFAULT 0;
    DECLARE owned_qty INT DEFAULT 0;
    DECLARE available_qty INT DEFAULT 0;

    -- Check fe_pp_qty_dlv integrity
    SELECT LPS_ID, LPS_QUANTITY_NUMBER INTO id_lps, delivered_qty FROM T_LINK_PP_S WHERE LPS_ID_S=id_s AND LPS_ID_PP=id_pp;
    SELECT SUM(LPS_QUANTITY_NUMBER) INTO used_qty FROM T_LINK_PP_S WHERE LPS_ID_S=id_s GROUP BY LPS_ID_S;
    SELECT S_QUANTITY_NUMBER INTO owned_qty FROM T_STOCK WHERE S_ID=id_s;
    SET available_qty = owned_qty + delivered_qty - used_qty;

    IF fe_pp_qty_dlv <= available_qty THEN
      IF id_lps < 1 AND fe_pp_qty_dlv > 0 THEN
        CALL new_link_pp_s(id_pp, id_s, fe_pp_qty_dlv);
      ELSEIF fe_pp_qty_dlv < 1 THEN
        DELETE FROM T_LINK_PP_S WHERE LPS_ID=id_lps;
        CALL new_log('Delete', id_lps,'update_link_pp_s', CONCAT(CONVERT(id_pp,CHAR),'::',CONVERT(id_s,CHAR),'::',CONVERT(fe_pp_qty_dlv,CHAR)),'Delete Link PP_S');
      ELSE
        UPDATE T_LINK_PP_S SET LPS_QUANTITY_NUMBER=fe_pp_qty_dlv WHERE LPS_ID=id_lps;
        CALL new_log('Update', id_lps,'update_link_pp_s', CONCAT(CONVERT(id_pp,CHAR),'::',CONVERT(id_s,CHAR),'::',CONVERT(fe_pp_qty_dlv,CHAR)),'Update QTY Link PP_S');
      END IF;
    ELSE
      SIGNAL SQLSTATE 'ERR0R'
      SET
      MESSAGE_TEXT = 'Invalid quantity added';
    END IF;
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_stock_part
-- Info: update stock part information
-- Parameters: [IN id_s INT, IN new_serial VARCHAR(32), IN new_arc_name VARCHAR(512), IN new_arc INT, IN new_po_name VARCHAR(512), IN new_po INT, IN new_index INT,
--              IN new_qty INT, IN new_u_price DECIMAL(10,2), IN new_currency VARCHAR(8), IN new_vendor VARCHAR(128),
--              IN new_received VARCHAR(3), IN new_received_date DATE, IN new_expiration_date DATE]
-- Return: Na
-- ---

CREATE PROCEDURE update_stock_part(IN id_s INT, IN new_serial VARCHAR(32), IN new_arc_name VARCHAR(512), IN new_arc INT, IN new_po_name VARCHAR(512), IN new_po INT, IN new_index INT,
                                   IN new_qty INT, IN new_u_price DECIMAL(10,2), IN new_currency VARCHAR(8), IN new_vendor VARCHAR(128),
                                   IN new_received VARCHAR(3), IN new_received_date DATE, IN new_expiration_date DATE,
                                   IN new_gp_location MEDIUMTEXT)
  -- Parameters Def
  BEGIN

    DECLARE id_gp INT DEFAULT 0;

    IF new_received IS NULL THEN
      SET new_received = 'no';
    END IF;

    IF new_gp_location IS NOT NULL THEN
      SELECT S_ID_GP INTO id_gp FROM T_STOCK WHERE S_ID=id_s;
      IF id_gp != 0 THEN
        UPDATE T_GENERIC_PART SET GP_LOCATION=new_gp_location WHERE GP_ID=id_gp;
      END IF;
    END IF;

    IF new_received = 'no' THEN
      UPDATE T_STOCK SET S_INDEX_PN=new_index, S_SERIAL=new_serial, S_ARC_NAME=new_arc_name, S_PO_NAME=new_po_name, S_QUANTITY_IN=new_qty ,S_QUANTITY_NUMBER=new_qty,S_PRICE=new_u_price, S_ACCURENCY=new_currency, S_VENDOR=new_vendor, S_RECEIVED=new_received, S_RECEIVED_DATE=new_received_date, S_EXPIRATION_DATE=new_expiration_date
      WHERE S_ID=id_s;
    ELSE
      UPDATE T_STOCK SET S_INDEX_PN=new_index, S_SERIAL=new_serial, S_ARC_NAME=new_arc_name, S_PO_NAME=new_po_name, S_QUANTITY_NUMBER=new_qty,S_PRICE=new_u_price, S_ACCURENCY=new_currency, S_VENDOR=new_vendor, S_RECEIVED=new_received, S_RECEIVED_DATE=new_received_date, S_EXPIRATION_DATE=new_expiration_date
      WHERE S_ID=id_s;
    END IF;

    -- Content
    IF new_arc > 0 THEN
      UPDATE T_STOCK SET S_ID_ARC= new_arc WHERE S_ID=id_s;
    END IF;
    IF new_po > 0 THEN
      UPDATE T_STOCK SET S_ID_PURCHASE_ORDER= new_po WHERE S_ID=id_s;
    END IF;
    CALL new_log('Update', id_s,'update_stock_part',NULL,'Update Stock Part Information');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_vendor
-- Info: update vendor information
-- Parameters: [IN id_vendor INT, IN new_name VARCHAR(128), IN new_address VARCHAR(512), IN new_city VARCHAR(128), IN new_country VARCHAR(128), IN new_phone VARCHAR(16), IN new_mail VARCHAR(128), IN new_contact VARCHAR(128)]
-- Return: Na
-- ---

CREATE PROCEDURE update_vendor(IN id_vendor INT, IN new_name VARCHAR(128), IN new_address VARCHAR(512), IN new_city VARCHAR(128), IN new_country VARCHAR(128), IN new_phone VARCHAR(16), IN new_mail VARCHAR(128), IN new_contact VARCHAR(128))
    -- Parameters Def
  BEGIN
    -- Content
    UPDATE T_VENDOR SET V_NAME=new_name, V_ADDRESS=new_address, V_CITY=new_city, V_COUNTRY=new_country, V_PHONE=new_phone, V_MAIL=new_mail, V_CONTACT=new_contact WHERE V_ID=id_vendor;
    CALL new_log('Update', id_vendor,'update_vendor',NULL,'Update Vendor Information');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_user
-- Info: update user information
-- Parameters: [IN id_user INT, IN new_password VARCHAR(64), IN new_status INT, IN new_code VARCHAR(16)]
-- Return: Na
-- ---

CREATE PROCEDURE update_user(IN id_user INT, IN new_password VARCHAR(64), IN new_status INT, IN new_code VARCHAR(16))
  -- Parameters Def
  BEGIN
    -- Content
    IF new_password IS NOT NULL AND new_password != '' THEN
      UPDATE T_USER SET U_PASSWORD=new_password, U_STATUS=new_status, U_CODE=new_code WHERE U_ID=id_user;
    ELSE
      UPDATE T_USER SET U_STATUS=new_status, U_CODE=new_code WHERE U_ID=id_user;
    END IF;
    CALL new_log('Update', id_user,'update_user',NULL,'Update User account');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_link_gp_gst
-- Info: update link between generic part and generic sub task information
-- Parameters: [IN id_lgg INT, IN new_qty INT, IN new_ipc VARCHAR(32)]
-- Return: Na
-- ---

CREATE PROCEDURE update_link_gp_gst(IN id_lgg INT, IN new_qty INT, IN new_ipc VARCHAR(32))
  -- Parameters Def
  BEGIN
  -- Content
    UPDATE T_LINK_GP_GST SET LGG_QTY=new_qty, LGG_IPC=new_ipc WHERE LGG_ID=id_lgg;
    CALL new_log('Update', id_lgg, 'update link_gp_gst', CONVERT(new_qty, CHAR), 'Update Link GP GST');
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- update_recorded_work
-- Info: update recorded work information
-- Parameters: [IN id_rw INT, IN tt_min INT, IN description MEDIUMTEXT]
-- Return: Na
-- ---

CREATE PROCEDURE update_recorded_work(IN id_rw INT, IN tt_min INT, IN description MEDIUMTEXT)
  BEGIN
    UPDATE T_RECORDED_WORK SET RW_TOTAL_MIN=tt_min, RW_DESCRIPTION=description WHERE RW_ID=id_rw;
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- merge_generic_parts
-- Info: Merge two generic parts in one and update linked row corresponding in other tables
-- Parameters: [IN id_gp_1 INT, IN id_gp_2 INT]
-- Return: Na
-- ---

CREATE PROCEDURE merge_generic_parts(IN id_gp_1 INT, IN id_gp_2 INT)
  BEGIN
    DECLARE pn_number1 INT DEFAULT 1;
    DECLARE part_number1 MEDIUMTEXT;
    DECLARE part_number2 MEDIUMTEXT;
    DECLARE location2 MEDIUMTEXT;

    IF id_gp_1 = id_gp_2 OR id_gp_1 < 1 OR id_gp_2 < 1 THEN
      SIGNAL SQLSTATE 'ERR0R'
      SET
      MESSAGE_TEXT = 'Invalid PN to merge';
    END IF;

    SELECT GP_NUMBER INTO part_number1 FROM T_GENERIC_PART
      WHERE GP_ID=id_gp_1;
    SELECT GP_NUMBER, GP_LOCATION INTO part_number2, location2 FROM T_GENERIC_PART
      WHERE GP_ID=id_gp_2;

    IF id_gp_1 = id_gp_2 OR id_gp_1 < 1 OR id_gp_2 < 1 THEN
      SIGNAL SQLSTATE 'ERR0R'
      SET
      MESSAGE_TEXT = 'Invalid PN to merge';
    END IF;

    SET pn_number1 = CountOccurrencesOfString(part_number1, ';;') + 1;

    -- MERGE GP2 IN GP1
    UPDATE T_GENERIC_PART SET GP_NUMBER=CONCAT(GP_NUMBER,CONCAT(';;',part_number2)), GP_LOCATION=CONCAT(GP_LOCATION,CONCAT(';;',location2)) WHERE GP_ID=id_gp_1;

    -- UPDATE STOCK PART
    UPDATE T_STOCK SET S_ID_GP=id_gp_1, S_INDEX_PN=S_INDEX_PN+pn_number1 WHERE S_ID_GP=id_gp_2;

    -- UPDATE PROJECT PART
    UPDATE T_PROJECT_PART SET PP_ID_GP=id_gp_1 WHERE PP_ID_GP=id_gp_2;

    -- UPDATE LINK GP GST
    UPDATE T_LINK_GP_GST SET LGG_ID_GP=id_gp_1 WHERE LGG_ID_GP=id_gp_2;

    -- DELETE OLD GP2
    DELETE FROM T_GENERIC_PART WHERE GP_ID=id_gp_2;

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- toggle_user_work
-- Info: toggle a user work for a recorded work. Create if it is not yet existing and close if it is
-- Parameters: [IN id_rw INT, IN id_user INT]
-- Return: Na
-- ---

CREATE PROCEDURE toggle_user_work(IN id_rw INT, IN id_user INT)
  BEGIN
    DECLARE id_uw INT DEFAULT 0;

    SELECT UW_ID INTO id_uw FROM T_USER_WORK
      WHERE UW_ID_RW = id_rw AND UW_ID_U = id_user AND UW_DATETIME_END IS NULL;
    IF id_uw = 0 THEN
      CALL new_user_work(id_rw, id_user);
    ELSE
      CALL close_user_work(id_uw);
    END IF;
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- toggle_user_work_on_st
-- Info: toggle a user work for a specific sub task. If recorded work doesn't exit, create it. And toggle user work
-- Parameters: [IN id_user INT, IN id_st INT]
-- Return: Na
-- ---

CREATE PROCEDURE toggle_user_work_on_st(IN id_user INT, IN id_st INT)
  BEGIN
    DECLARE id_rw INT DEFAULT 0;

    SELECT RW_ID INTO id_rw FROM T_RECORDED_WORK
      WHERE RW_ID_ST = id_st;

    IF id_rw = 0 THEN
      CALL new_recorded_work(id_st, NULL);
      SELECT @last_insert INTO id_rw;
    END IF;

    CALL toggle_user_work(id_rw, id_user);

  END |