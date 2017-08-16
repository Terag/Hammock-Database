-- ************************************************************************************************ --
-- ----------------------------- Documentation format --------------------------------------------- --
-- ************************************************************************************************ --

-- ---
-- <Function_Name>
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
-- ----------------------------- Drop existing Functions ------------------------------------------ --
-- ************************************************************************************************ --

-- Insert procedure
DROP FUNCTION IF EXISTS CountOccurrencesOfString|

-- ************************************************************************************************ --
-- --------------------------------- Functions ---------------------------------------------------- --
-- ************************************************************************************************ --

-- ---
-- CountOccurrencesOfString
-- Info: Gives the number of occurrences of sub_str in str
-- Parameters: [ IN str NVARCHAR(1024), IN sub_str NVARCHAR(1024)]
-- Return: INT
-- ---

CREATE FUNCTION CountOccurrencesOfString(
  str NVARCHAR(1024),
  sub_str NVARCHAR(1024)
)
RETURNS INT
DETERMINISTIC
BEGIN
  return (LENGTH(str)-LENGTH(REPLACE(str,sub_str,'')))/LENGTH(sub_str);
END|