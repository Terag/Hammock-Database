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
DROP PROCEDURE IF EXISTS delete_project_part|
DROP PROCEDURE IF EXISTS remove_entire_wo|
DROP PROCEDURE IF EXISTS remove_project|
DROP PROCEDURE IF EXISTS close_user_work|

-- ************************************************************************************************ --
-- ----------------------------- Create Procedure ------------------------------------------------- --
-- ************************************************************************************************ --

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- delete_project_part
-- Info: Deletes a project part and removes the associated LINK_PP_S
-- Parameters: [IN delete_pp_id INT]
-- Return: Na
-- ---

CREATE PROCEDURE delete_project_part(IN delete_pp_id INT)
  BEGIN
    DELETE FROM T_LINK_PP_S WHERE LPS_ID_PP=delete_pp_id;

    DELETE FROM T_PROJECT_PART WHERE PP_ID=delete_pp_id;
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- remove_entire_wo
-- Info: Delete a ROW from T_WORK_ORDER and associated SUB_TASKs. Used in project closure part. Error if an associated PROJECT_PART still exist.
-- Parameters: [IN id_wo INT 'wo to delete']
-- Return: Na
-- ---

CREATE PROCEDURE remove_entire_wo(IN id_wo INT)
  BEGIN
    DELETE FROM T_PROJECT_PART USING T_PROJECT_PART
      LEFT JOIN T_SUB_TASK ON PP_ID_ST=ST_ID
      LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
      WHERE T_WORK_ORDER.WO_ID=id_wo;

    DELETE FROM T_SUB_TASK USING T_SUB_TASK
      LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
      WHERE T_WORK_ORDER.WO_ID=id_wo;

    DELETE FROM T_WORK_ORDER
      WHERE T_WORK_ORDER.WO_ID=id_wo;
  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- remove_project
-- Info: Delete a ROW from T_PROJECT and associated ENGINES / HELICOPTER / CUSTOMER. Used in project closure part. Error if an associated WORK_ORDER still exist
-- Parameters: [IN id_project INT 'project to delete']
-- Return: Na
-- ---

CREATE PROCEDURE remove_project(IN id_project INT)
  BEGIN

    DECLARE id_helicopter INT DEFAULT NULL;
    DECLARE id_customer INT DEFAULT NULL;
    SELECT P_ID_H, P_ID_C INTO id_helicopter, id_customer FROM T_PROJECT WHERE P_ID=id_project;

    -- Delete Project
    DELETE FROM T_PROJECT WHERE P_ID=id_project;

    -- Delete Engine 1
    DELETE FROM T_ENGINE USING T_ENGINE
      LEFT JOIN T_HELICOPTER ON H_ID_E1=E_ID
      WHERE T_HELICOPTER.H_ID=id_helicopter;

    -- Delete Engine 2
    DELETE FROM T_ENGINE USING T_ENGINE
      LEFT JOIN T_HELICOPTER ON H_ID_E2=E_ID
      WHERE T_HELICOPTER.H_ID=id_helicopter;

    -- Delete Helicopter
    DELETE FROM T_HELICOPTER USING T_HELICOPTER
      LEFT JOIN T_PROJECT ON P_ID_H=H_ID
      WHERE T_HELICOPTER.H_ID=id_helicopter;

    -- Delete Customer
    DELETE FROM T_CUSTOMER WHERE C_ID=id_customer;

  END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- closure_user_work
-- Info: Close a currently open user_work by putting UW_DATETIME_END to current DATETIME.
-- Parameters: [IN id_uw INT]
-- Return: Na
-- ---

CREATE PROCEDURE close_user_work(IN id_uw INT)
  BEGIN
    DECLARE date_end DATETIME DEFAULT NULL;
    DECLARE date_begin DATETIME DEFAULT NULL;
    DECLARE id_rw INT DEFAULT NULL;
    DECLARE tt_min INT DEFAULT NULL;

    SELECT UW_ID_RW, UW_DATETIME_BEGIN, UW_DATETIME_END INTO id_rw, date_begin, date_end FROM T_USER_WORK
      WHERE UW_ID=id_uw;

    IF date_end IS NULL THEN
      SET date_end = CURRENT_TIMESTAMP;
      SET tt_min = time_to_sec(timediff(date_end, date_begin)) / 60;

      UPDATE T_RECORDED_WORK SET RW_TOTAL_MIN=RW_TOTAL_MIN+tt_min WHERE RW_ID=id_rw;

      UPDATE T_USER_WORK SET UW_DATETIME_END = date_end, UW_TOTAL_MIN = tt_min WHERE UW_ID=id_uw;
    END IF;
  END |