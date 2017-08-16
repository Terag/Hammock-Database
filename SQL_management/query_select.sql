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

-- Get/Select procedure
DROP PROCEDURE IF EXISTS project_to_helicopter_id|
DROP PROCEDURE IF EXISTS helicopter_to_project_id|
DROP PROCEDURE IF EXISTS project_list|
DROP PROCEDURE IF EXISTS project_specific|
DROP PROCEDURE IF EXISTS project_recapitulate_from_project_ID|
DROP PROCEDURE IF EXISTS project_recapitulate_from_helicopter_ID|
DROP PROCEDURE IF EXISTS engine_recapitulate|
DROP PROCEDURE IF EXISTS work_order_name_list_for_specific_project|
DROP PROCEDURE IF EXISTS sub_task_list_for_specific_work_order|
DROP PROCEDURE IF EXISTS project_part_list_for_specific_work_order|
DROP PROCEDURE IF EXISTS project_part_list_by_po_for_specific_work_order|
DROP PROCEDURE IF EXISTS available_stock_list_by_po_from_gp_id|
DROP PROCEDURE IF EXISTS used_stock_list_by_po_from_pp|
DROP PROCEDURE IF EXISTS stock_list|
DROP PROCEDURE IF EXISTS available_stock_list|
DROP PROCEDURE IF EXISTS generic_aircraft_list_by_type|
DROP PROCEDURE IF EXISTS generic_part_list|
DROP PROCEDURE IF EXISTS lps_for_pp_s_id|
DROP PROCEDURE IF EXISTS ordered_part_list|
DROP PROCEDURE IF EXISTS number_available_for_gp_id|
DROP PROCEDURE IF EXISTS generic_part_by_part_number|
DROP PROCEDURE IF EXISTS recorded_work_list|
DROP PROCEDURE IF EXISTS user_work_for_rw|
DROP PROCEDURE IF EXISTS user_work_for_rw_and_user|
DROP PROCEDURE IF EXISTS not_performed_sub_task_list|
DROP PROCEDURE IF EXISTS user_work_list_between_two_date|
DROP PROCEDURE IF EXISTS project_work_list_between_two_date|
DROP PROCEDURE IF EXISTS current_user_work_for_user|
DROP PROCEDURE IF EXISTS current_user_work_list|

-- ************************************************************************************************ --
-- ----------------------------- Create Procedure ------------------------------------------------- --
-- ************************************************************************************************ --

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- project_to_helicopter_id
-- Info: Get helicopter_id linked to project_id
-- Parameters: [IN project_ID INT]
-- Return: P_ID_H 'helicopter_ID'
-- ---

CREATE PROCEDURE project_to_helicopter_id(IN project_ID INT, OUT helicopter_ID INT)
	-- Parameters def
BEGIN
	-- Content
	SELECT P_ID_H INTO helicopter_ID FROM T_PROJECT
	WHERE P_ID=project_ID;
END|

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- helicopter_to_project_id
-- Info: Get project_id linked to helicopter_id
-- Parameters: [IN helicopter_ID INT]
-- Return: H_ID_P 'project_ID'
-- ---

CREATE PROCEDURE helicopter_to_project_id(IN helicopter_ID INT, OUT project_ID INT)
  -- Parameters def
BEGIN
  -- Content
  SELECT P_ID INTO project_ID FROM T_PROJECT
  WHERE P_ID_H=helicopter_ID;
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- project_list
-- Info: Get the list of current_choice projects
-- Parameters: NULL
-- Return: Tab of columns [P_ID 'id', P_NAME 'p_name', C_NAME 'c_name', GA_NAME 'ga_name']
-- ---

CREATE PROCEDURE project_list()  
	-- Parameters def
BEGIN
	-- Content
	SELECT P_ID id, P_NAME p_name, C_NAME c_name, GA_NAME ga_name, H_SERIAL h_serial, H_ID h_id FROM T_PROJECT
	INNER JOIN T_CUSTOMER ON P_ID_C = C_ID
	INNER JOIN T_HELICOPTER ON P_ID_H = H_ID
	INNER JOIN T_GENERIC_AIRCRAFT ON H_ID_GA = GA_ID;
END|

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- project_specific
-- Info: Get general information about specific project
-- Parameters: [IN project_ID INT]
-- Return: Tab of columns [P_ID 'id', P_NAME 'p_name', C_NAME 'c_name', GA_NAME 'ga_name']
-- ---

CREATE PROCEDURE project_specific(IN project_ID INT)
	-- Parameters def
BEGIN
	-- Content
	SELECT P_ID id, P_NAME p_name, C_NAME c_name, GA_NAME ga_name, H_SERIAL h_serial FROM T_PROJECT
	INNER JOIN T_CUSTOMER ON P_ID_C = C_ID
	INNER JOIN T_HELICOPTER ON P_ID_H = H_ID 
	INNER JOIN T_GENERIC_AIRCRAFT ON H_ID_GA = GA_ID 
	WHERE P_ID=project_ID;
END|

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- project_recapitulate_from_project_ID
-- Info: Get information about specific project
-- Parameters: [IN project_ID INT]
-- Return: Tab of columns [*] (All columns of T_PROJECT, T_CUSTOMER, T_HELICOPTER, T_GENERIC_AIRCRAFT)
-- ---

CREATE PROCEDURE project_recapitulate_from_project_ID(IN project_ID INT)
	-- Parameters def
BEGIN
	-- Content
	SELECT * FROM T_PROJECT
	INNER JOIN T_CUSTOMER ON P_ID_C = C_ID 
	INNER JOIN T_HELICOPTER ON P_ID_H = H_ID
	INNER JOIN T_GENERIC_AIRCRAFT ON H_ID_GA = GA_ID
	WHERE P_ID=project_ID;
END|

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- project_recapitulate_from_helicopter_ID
-- Info: Get information about specific project from helicopter_ID
-- Parameters: [IN helicopter_ID INT]
-- Return: Tab of columns [*] (All columns of T_PROJECT, T_CUSTOMER, T_HELICOPTER, T_GENERIC_AIRCRAFT)
-- ---

CREATE PROCEDURE project_recapitulate_from_helicopter_ID(IN helicopter_ID INT)
  -- Parameters def
  BEGIN
    -- Content
    SELECT * FROM T_PROJECT
      INNER JOIN T_CUSTOMER ON P_ID_C = C_ID
      INNER JOIN T_HELICOPTER ON P_ID_H = H_ID
      INNER JOIN T_GENERIC_AIRCRAFT ON H_ID_GA = GA_ID
    WHERE H_ID=helicopter_ID;
  END|

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- engine_recapitulate
-- Info: Get information about specific engine
-- Parameters: [IN engine_ID INT]
-- Return: Tab of columns [*] (All columns of T_ENGINE, T_GENERIC_AIRCRAFT)
-- ---

CREATE PROCEDURE engine_recapitulate(IN engine_ID INT)
	-- Parameters def
BEGIN
	-- Content
	SELECT * FROM T_ENGINE 
	INNER JOIN T_GENERIC_AIRCRAFT ON E_ID_GA = GA_ID 
	WHERE E_ID=engine_ID;
END|

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- work_order_name_list_for_specific_project
-- Info: Get list of name of work orders for a specific project
-- Parameters: [IN project_ID INT]
-- Return: Tab of columns [WO_ID, WO_NAME]
-- ---

CREATE PROCEDURE work_order_name_list_for_specific_project(IN project_ID INT)
	-- Parameters def
BEGIN
	-- Content
	DECLARE helicopter_ID INT;

	CALL project_to_helicopter_id(project_ID, helicopter_ID);

	SELECT WO_ID, WO_NAME FROM T_WORK_ORDER
	WHERE WO_ID_H=helicopter_ID;
END|

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- sub_task_list_for_specific_work_order
-- Info: Get list of sub task for a specific work order
-- Parameters: [IN work_order_ID INT]
-- Return: Tab of columns [*] (All columns of T_SUB_TASK, T_GENERIC_SUB_TASK, T_MANUAL)
-- ---

CREATE PROCEDURE sub_task_list_for_specific_work_order(IN work_order_ID INT)
  -- Parameters def
BEGIN
  -- Content
  SELECT * FROM T_SUB_TASK
  INNER JOIN T_GENERIC_SUB_TASK ON ST_ID_GST = GST_ID
	LEFT JOIN T_MANUAL ON GST_ID_M = M_ID
  WHERE ST_ID_WO=work_order_ID
	ORDER BY GST_NUMBER;
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- project_parts_list_for_specific_work_order
-- Info: Get list of part of a specific work order (Use for ERV)
-- Parameters: [IN work_order_ID INT]
-- Return: Tab of columns [*] (All columns of T_PROJECT_PART, T_GENERIC_PART, T_SUB_TASK, T_GENERIC_SUB_TASK, T_ATA_REF, T_LINK_PP_S, SUM(LPS_QUANTITY_NUMBER) 'LPS_QUANTITY_USED')
-- ---

CREATE PROCEDURE project_part_list_for_specific_work_order(IN work_order_ID INT)
	-- Parameters def
BEGIN
	-- Content
	SELECT T_PROJECT_PART.*, T_GENERIC_PART.*, T_SUB_TASK.*, T_GENERIC_SUB_TASK.*, SUM(LPS_QUANTITY_NUMBER) AS LPS_QUANTITY_USED FROM T_PROJECT_PART
	LEFT JOIN T_GENERIC_PART ON PP_ID_GP=GP_ID
	LEFT JOIN T_SUB_TASK ON PP_ID_ST=ST_ID
	LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
	LEFT JOIN T_LINK_PP_S ON LPS_ID_PP=PP_ID
	WHERE ST_ID_WO=work_order_ID
	GROUP BY PP_ID;
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- project_parts_list_for_specific_work_order
-- Info: Get list of part of a specific work order (Use for ERV)
-- Parameters: [IN work_order_ID INT]
-- Return: Tab of columns [*] (All columns of T_LINK_PP_S, T_PROJECT_PART, T_SUB_TASK, T_GENERIC_SUB_TASK, T_WORK_ORDER, T_STOCK, T_GENERIC_PART)
-- ---

CREATE PROCEDURE project_part_list_by_po_for_specific_work_order(IN work_order_ID INT)
	-- Parameters def
BEGIN
	-- Content
	SELECT * FROM T_LINK_PP_S
	LEFT JOIN T_PROJECT_PART ON LPS_ID_PP=PP_ID
	LEFT JOIN T_SUB_TASK ON PP_ID_ST=ST_ID
	LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
	LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
	LEFT JOIN T_STOCK ON LPS_ID_S=S_ID
	LEFT JOIN T_GENERIC_PART ON S_ID_GP=GP_ID
	WHERE WO_ID=work_order_ID
	ORDER BY GST_NUMBER;
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- available_stock_list_by_po_from_gp_id
-- Info: Get available stock list by po for a specific generic_part
-- Parameters: [IN gp_ID INT]
-- Return: Tab of columns [SUM(COALESCE(LPS_QUANTITY_NUMBER,0)) 'S_QUANTITY_USED', S_QUANTITY_NUMBER - SUM(COALESCE(LPS_QUANTITY_NUMBER,0)) 'S_QUANTITY_AVAILABLE', *] (All columns of T_STOCK)
-- ---

CREATE PROCEDURE available_stock_list_by_po_from_gp_id(IN gp_ID INT)
	-- Parameters def
BEGIN
	-- Content
	SELECT T_STOCK.*, SUM(COALESCE(LPS_QUANTITY_NUMBER,0)) AS S_QUANTITY_USED, S_QUANTITY_NUMBER - SUM(COALESCE(LPS_QUANTITY_NUMBER,0)) AS S_QUANTITY_AVAILABLE FROM T_STOCK
	LEFT JOIN T_LINK_PP_S ON LPS_ID_S=S_ID
	LEFT JOIN T_FILE ON S_ID_PURCHASE_ORDER=F_ID
	WHERE S_ID_GP=gp_ID
	GROUP BY S_ID
	ORDER BY S_RECEIVED_DATE;

END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- used_stock_list_by_po_from_pp
-- Info: Get list of used parts for a specific project part (Use for ERV)
-- Parameters: [IN ppart_ID INT]
-- Return: Tab of columns [LPS_ID, S_ID_GP, S_RECEIVED_DATE, S_RECEIVED, S_ID_PURCHASE_ORDER, S_PO_NAME, LPS_QUANTITY_NUMBER]
-- ---

CREATE PROCEDURE used_stock_list_by_po_from_pp(IN ppart_ID INT)
	-- Parameters def
BEGIN
	-- Content
	SELECT LPS_ID, S_ID_GP, S_RECEIVED_DATE, S_RECEIVED, S_ID_PURCHASE_ORDER, S_PO_NAME, LPS_QUANTITY_NUMBER FROM T_LINK_PP_S
	LEFT JOIN T_PROJECT_PART ON LPS_ID_PP=PP_ID
	LEFT JOIN T_STOCK ON LPS_ID_S=S_ID
	LEFT JOIN T_FILE ON S_ID_PURCHASE_ORDER=F_ID
	WHERE LPS_ID_PP=ppart_ID;
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- stock_list
-- Info: Get list of stock parts
-- Parameters: [*]
-- Return: Tab of columns [po.F_ID 'PO_ID', po.F_DIRECTORY 'PO_DIRECTORY', po.F_NAME 'PO_NAME', po.F_FORMAT 'PO_FORMAT',
-- 												 arc.F_ID 'ARC_ID', arc.F_DIRECTORY 'ARC_DIRECTORY', arc.F_NAME 'ARC_NAME', arc.F_FORMAT 'ARC_FORMAT',
-- 												 SUM(COALESCE(LPS_QUANTITY_NUMBER,0)) 'S_QUANTITY_USED', s.*, gp.*] (All columns of T_STOCK 's', T_GENERIC_PART 'gp')
-- ---

CREATE PROCEDURE stock_list()
	-- Parameters def
BEGIN
	-- Content
	SELECT s.*, gp.*, po.F_ID AS PO_ID, po.F_DIRECTORY AS PO_DIRECTORY, po.F_NAME AS PO_NAME, po.F_FORMAT AS PO_FORMAT, arc.F_ID AS ARC_ID,
		arc.F_DIRECTORY AS ARC_DIRECTORY, arc.F_NAME AS ARC_NAME, arc.F_FORMAT AS ARC_FORMAT, SUM(COALESCE(LPS_QUANTITY_NUMBER,0)) AS S_QUANTITY_USED
	FROM T_STOCK s
	LEFT JOIN T_FILE po ON (S_ID_PURCHASE_ORDER=po.F_ID)
	LEFT JOIN T_FILE arc ON (S_ID_ARC=arc.F_ID)
	LEFT JOIN T_GENERIC_PART gp ON (S_ID_GP=GP_ID)
	LEFT JOIN T_LINK_PP_S ON (LPS_ID_S=S_ID)
	GROUP BY S_ID
	HAVING S_RECEIVED <> 'no'
	ORDER BY GP_NUMBER;
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- available_stock_list
-- Info: Get list of available stock parts
-- Parameters: []
-- Return: Tab of columns [po.F_ID 'PO_ID', po.F_DIRECTORY 'PO_DIRECTORY', po.F_NAME 'PO_NAME', po.F_FORMAT 'PO_FORMAT',
-- 												 arc.F_ID 'ARC_ID', arc.F_DIRECTORY 'ARC_DIRECTORY', arc.F_NAME 'ARC_NAME', arc.F_FORMAT 'ARC_FORMAT',
-- 												 S_QUANTITY_NUMBER - SUM(COALESCE(LPS_QUANTITY_NUMBER,0)) 'S_QUANTITY_AVAILABLE', s.*, gp.*] (All columns of T_STOCK 's', T_GENERIC_PART 'gp')
-- ---

CREATE PROCEDURE available_stock_list()
	-- Parameters def
	BEGIN
		-- Content
		SELECT s.*, gp.*, po.F_ID AS PO_ID, po.F_DIRECTORY AS PO_DIRECTORY, po.F_NAME AS PO_NAME, po.F_FORMAT AS PO_FORMAT, arc.F_ID AS ARC_ID,
											arc.F_DIRECTORY AS ARC_DIRECTORY, arc.F_NAME AS ARC_NAME, arc.F_FORMAT AS ARC_FORMAT, S_QUANTITY_NUMBER - SUM(COALESCE(LPS_QUANTITY_NUMBER,0)) AS S_QUANTITY_AVAILABLE
		FROM T_STOCK s
		LEFT JOIN T_FILE po ON (S_ID_PURCHASE_ORDER=po.F_ID)
		LEFT JOIN T_FILE arc ON (S_ID_ARC=arc.F_ID)
		LEFT JOIN T_GENERIC_PART gp ON (S_ID_GP=GP_ID)
		LEFT JOIN T_LINK_PP_S ON (LPS_ID_S=S_ID)
		GROUP BY S_ID
		HAVING S_QUANTITY_AVAILABLE > 0 AND S_RECEIVED != 'no'
		ORDER BY GP_NUMBER;
	END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- generic_part_list
-- Info: Get list of generic parts
-- Parameters: []
-- Return: Tab of columns [*] (All columns of T_GENERIC_PART)
-- ---

CREATE PROCEDURE generic_part_list()
	-- Parameters def
	BEGIN
		-- Content
		SELECT * FROM T_GENERIC_PART ORDER BY GP_NAME, GP_NUMBER;
	END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- ordered_part_list
-- Info: Get list of ordered parts (Receive parts)
-- Parameters: []
-- Return: Tab of columns [po.F_ID 'PO_ID', po.F_DIRECTORY 'PO_DIRECTORY', po.F_NAME 'PO_NAME', po.F_FORMAT 'PO_FORMAT', arc.F_ID 'ARC_ID',
-- 												 arc.F_DIRECTORY 'ARC_DIRECTORY', arc.F_NAME 'ARC_NAME', arc.F_FORMAT 'ARC_FORMAT', s.*, gp.*] (All columns of T_STOCK 's', T_GENERIC_PART 'gp')
-- ---

CREATE PROCEDURE ordered_part_list()
	-- Parameters def
BEGIN
	-- Content
	SELECT s.*, gp.*, po.F_ID AS PO_ID, po.F_DIRECTORY AS PO_DIRECTORY, po.F_NAME AS PO_NAME, po.F_FORMAT AS PO_FORMAT, arc.F_ID AS ARC_ID,
										arc.F_DIRECTORY AS ARC_DIRECTORY, arc.F_NAME AS ARC_NAME, arc.F_FORMAT AS ARC_FORMAT
	FROM T_STOCK s
	LEFT JOIN T_FILE po ON (S_ID_PURCHASE_ORDER=po.F_ID)
	LEFT JOIN T_FILE arc ON (S_ID_ARC=arc.F_ID)
	LEFT JOIN T_GENERIC_PART gp ON (S_ID_GP=GP_ID)
	WHERE S_RECEIVED <> 'yes';
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- generic_aircraft_list_by_type
-- Info: Get generic aircraft list by type (engine|helico)
-- Parameters: [IN type VARCHAR(8)]
-- Return: Tab of columns [*] (All columns of T_GENERIC_AIRCRAFT)
-- ---

CREATE PROCEDURE generic_aircraft_list_by_type(IN type VARCHAR(8))
	-- Parameters def
BEGIN
	-- Content
	SELECT * FROM T_GENERIC_AIRCRAFT
	WHERE GA_TYPE=type;
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- lps_for_pp_s_id
-- Info: Get lps linked to a project part id and stock id if exists
-- Parameters: [IN id_pp INT, IN id_s INT]
-- Return: Tab of columns [*] (All columns of T_LINK_PP_S)
-- ---

CREATE PROCEDURE lps_for_pp_s_id(IN id_pp INT, IN id_s INT)
	-- Parameters def
BEGIN
	-- Content
	SELECT * FROM T_LINK_PP_S
	WHERE LPS_ID_PP=id_pp AND LPS_ID_S=id_s;
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- number_available_for_gp_id
-- Info: Get number available for a generic part
-- Parameters: [IN id_gp INT]
-- Return: Tab of columns [QTY_PROJECT, QTY_STOCK, QTY_STOCK-QTY_PROJECT 'S_QUANTITY_AVAILABLE']
-- ---

CREATE PROCEDURE number_available_for_gp_id(IN id_gp INT)
	-- Parameters def
BEGIN
	-- Content
	DECLARE QTY_STOCK INT DEFAULT 0;
	DECLARE QTY_PROJECT INT DEFAULT 0;

	SELECT SUM(LPS_QUANTITY_NUMBER) INTO QTY_PROJECT FROM T_LINK_PP_S
		LEFT JOIN T_STOCK ON LPS_ID_S=S_ID
		WHERE S_ID_GP=id_gp
		GROUP BY S_ID_GP;

	SELECT SUM(S_QUANTITY_NUMBER) INTO QTY_STOCK FROM T_STOCK
		WHERE S_ID_GP=id_gp
		GROUP BY S_ID_GP;

	SELECT QTY_PROJECT, QTY_STOCK, QTY_STOCK-QTY_PROJECT AS S_QUANTITY_AVAILABLE;
END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- generic_part_by_part_number
-- Info: Get generic part by part number and avoid a certain ID
-- Parameters: [IN number CHAR(128), IN id_avoid INT]
-- Return: Tab of columns [GP_ID]
-- ---

CREATE PROCEDURE generic_part_by_part_number(IN number CHAR(128), IN id_avoid INT)
	BEGIN
		SELECT GP_ID FROM T_GENERIC_PART
			WHERE INSTR(CONCAT(';',CONCAT(REPLACE(GP_NUMBER,'-',''),';')), CONCAT(';',CONCAT(REPLACE(number,'-',''),';')))
				AND GP_ID!=id_avoid;
	END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- recorded_work_list
-- Info: Get list of recorded work
-- Parameters: []
-- Return: Tab of columns [*] (All columns of T_RECORDED_WORK, T_SUB_TASK, T_GENERIC_SUB_TASK, T_WORK_ORDER, T_PROJECT)
-- ---

CREATE PROCEDURE recorded_work_list()
	BEGIN
		SELECT * FROM T_RECORDED_WORK
			LEFT JOIN T_SUB_TASK ON RW_ID_ST=ST_ID
			LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
			LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
			LEFT JOIN T_PROJECT ON WO_ID_H=P_ID_H;
	END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- user_work_for_rw
-- Info: Get list of user work for a specific recorder work
-- Parameters: [IN id_rw INT]
-- Return: Tab of columns [*] (All columns of T_USER_WORK, T_USER)
-- ---

CREATE PROCEDURE user_work_for_rw(IN id_rw INT)
	BEGIN
		SELECT * FROM T_USER_WORK
			LEFT JOIN T_USER ON UW_ID_U=U_ID
			WHERE UW_ID_RW = id_rw;
	END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- user_work_for_rw_and_user
-- Info: Get user work list for a recorded work and user
-- Parameters: [IN id_rw INT, IN id_u INT]
-- Return: Tab of columns [*] (All columns of T_USER_WORK, T_USER)
-- ---

CREATE PROCEDURE user_work_for_rw_and_user(IN id_rw INT, IN id_u INT)
	BEGIN
		SELECT * FROM T_USER_WORK
			LEFT JOIN T_USER ON UW_ID_U=U_ID
		WHERE UW_ID_RW = id_rw AND U_ID=id_u;
	END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- not_performed_sub_task_list
-- Info: Get all not performed sub tasks
-- Parameters: VOID
-- Return: Tab of columns [*] (All columns of T_SUB_TASK, T_GENERIC_SUB_TASK, T_WORK_ORDER, T_PROJECT)
-- ---

CREATE PROCEDURE not_performed_sub_task_list()
	BEGIN
		SELECT * FROM T_SUB_TASK
			LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
			LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
			LEFT JOIN T_PROJECT ON WO_ID_H=P_ID_H
			WHERE ST_PERFORM_DATE IS NULL AND ST_APPROVED_DATE IS NOT NULL
			ORDER BY P_NAME, GST_NUMBER;
	END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- user_work_list_between_two_date
-- Info: Get user work list between two dates
-- Parameters: [IN id_user INT, IN date_begin DATE, IN date_end DATE]
-- Return: Tab of columns [*] (All columns of T_USER_WORK, T_RECORDED_WORK, T_SUB_TASK, T_GENERIC_SUB_TASK, T_WORK_ORDER, T_PROJECT)
-- ---

CREATE PROCEDURE user_work_list_between_two_date(IN id_user INT, IN date_begin DATE, IN date_end DATE)
	BEGIN
		SELECT * FROM T_USER_WORK
			LEFT JOIN T_RECORDED_WORK ON UW_ID_RW=RW_ID
			LEFT JOIN T_SUB_TASK ON RW_ID_ST=ST_ID
			LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
			LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
			LEFT JOIN T_PROJECT ON WO_ID_H=P_ID_H
		WHERE UW_ID_U = id_user AND date_begin <= UW_DATETIME_BEGIN AND UW_DATETIME_BEGIN <= date_end
		ORDER BY UW_DATETIME_BEGIN, P_ID;
	END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- project_work_list_between_two_date
-- Info: Get project work list between two date
-- Parameters: [IN id_project INT, IN date_begin DATE, IN date_end DATE]
-- Return: Tab of columns [*] (All columns of T_USER_WORK, T_USER, T_RECORDED_WORK, T_SUB_TASK, T_GENERIC_SUB_TASK, T_WORK_ORDER, T_PROJECT)
-- ---

CREATE PROCEDURE project_work_list_between_two_date(IN id_project INT, IN date_begin DATE, IN date_end DATE)
	BEGIN
		IF id_project = 0 THEN
			SELECT * FROM T_USER_WORK
				LEFT JOIN T_USER ON UW_ID_U=U_ID
				LEFT JOIN T_RECORDED_WORK ON UW_ID_RW=RW_ID
				LEFT JOIN T_SUB_TASK ON RW_ID_ST=ST_ID
				LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
				LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
				LEFT JOIN T_PROJECT ON WO_ID_H=P_ID_H
			WHERE RW_ID_ST IS NULL AND date_begin <= UW_DATETIME_BEGIN AND UW_DATETIME_BEGIN <= date_end
			ORDER BY UW_DATETIME_BEGIN;
		ELSE
			SELECT * FROM T_USER_WORK
				LEFT JOIN T_USER ON UW_ID_U=U_ID
				LEFT JOIN T_RECORDED_WORK ON UW_ID_RW=RW_ID
				LEFT JOIN T_SUB_TASK ON RW_ID_ST=ST_ID
				LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
				LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
				LEFT JOIN T_PROJECT ON WO_ID_H=P_ID_H
			WHERE P_ID = id_project AND date_begin <= UW_DATETIME_BEGIN AND UW_DATETIME_BEGIN <= date_end
			ORDER BY UW_DATETIME_BEGIN, ST_ID;
		END IF;

	END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- current_user_work_for_user
-- Info: Get current user work for a user
-- Parameters: [IN id_user INT]
-- Return: Tab of columns [*] (All columns of T_USER_WORK, T_RECORDED_WORK, T_SUB_TASK, T_GENERIC_SUB_TASK, T_WORK_ORDER, T_PROJECT)
-- ---

CREATE PROCEDURE current_user_work_for_user(IN id_user INT)
	BEGIN
		SELECT * FROM T_USER_WORK
			LEFT JOIN T_RECORDED_WORK ON UW_ID_RW=RW_ID
			LEFT JOIN T_SUB_TASK ON RW_ID_ST=ST_ID
			LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
			LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
			LEFT JOIN T_PROJECT ON WO_ID_H=P_ID_H
			WHERE UW_ID_U=id_user AND UW_DATETIME_END IS NULL;
	END |

-- -------------------------------------------------------------------------------------------------------------------------------------------- --
-- current_user_work_list
-- Info: Get current user work list for all user work
-- Parameters: []
-- Return: Tab of columns [*] (All columns of T_USER_WORK, T_USER, T_RECORDED_WORK, T_SUB_TASK, T_GENERIC_SUB_TASK, T_WORK_ORDER, T_PROJECT)
-- ---

CREATE PROCEDURE current_user_work_list()
	BEGIN
		SELECT * FROM T_USER_WORK
			LEFT JOIN T_USER ON UW_ID_U=U_ID
			LEFT JOIN T_RECORDED_WORK ON UW_ID_RW=RW_ID
			LEFT JOIN T_SUB_TASK ON RW_ID_ST=ST_ID
			LEFT JOIN T_GENERIC_SUB_TASK ON ST_ID_GST=GST_ID
			LEFT JOIN T_WORK_ORDER ON ST_ID_WO=WO_ID
			LEFT JOIN T_PROJECT ON WO_ID_H=P_ID_H
		WHERE UW_DATETIME_END IS NULL;
	END |