<?php


namespace Framework\lib;

// all the models extends this abstract model


class AbstractModel
{
	public static $db;
	private $logger;

	const DATA_TYPE_INT = \PDO::PARAM_INT;
	const DATA_TYPE_STR = \PDO::PARAM_STR;
	const DATA_TYPE_FLOAT = 4;

	public function __construct()
    {
        $loggerModel = new LoggerModel('database');
        $logger = $loggerModel->InitializeLogger();
        $this->logger = $logger['logger'];
    }

    ##### BuildSQLstring ##########
	// Parameters :- None
	// Return :- SQL Query
	//Purpose :- every database table has a model only for it
	// so because of that at every model we create an array contains the table's columns
	// and then we loop that array and arrange these coulmns and put the data on it and by that
	// this function can really generate the sql strings like insert and update
	###########################
	private function BuildSQLstring(): string
    {
		$params = '';
	 	foreach ($this->tableSchema as $columnName => $type) {
	 		if ($this->$columnName === '0' || $this->$columnName === 0 || ($this->$columnName !== null && !empty($this->$columnName))) {
	 		    $params .= $columnName . ' = :' . $columnName . ", ";
//                $params .= $columnName . " = '" . $this->$columnName . "', ";
	 		}
	 	}
	 	return trim($params, ', ');
	}

	private function PrepareValues(\PDOStatement &$stmt)
    {
        foreach ($this->tableSchema as $columnName => $type) {
            if ($this->$columnName === '0' || $this->$columnName === 0 || ($this->$columnName !== null && !empty($this->$columnName))) {
                if ($type == 4) {
                    $sanitizedValue = filter_var($this->$columnName, FILTER_SANITIZE_NUMBER_FLOAT,
                        FILTER_FLAG_ALLOW_FRACTION);
                    $stmt->bindValue(":{$columnName}", $sanitizedValue);
                } else {
                    $stmt->bindValue(":{$columnName}", $this->$columnName, $type);
                }
            }
        }
    }

	##### Create ##########
	// Parameters :- None
	// Return Type :- true/false
	// Purpose :- inserts data to the database,
	// this function uses the $tableName variable at the model and the BuildSQLstring function
	// to generate the whole SQL insert query automatically.
	##########################
	private function Create(): bool
    {
		$sql = 'INSERT INTO ' . static::$tableName . ' SET ' . self::BuildSQLstring();
        $stmt = self::$db->prepare($sql);
        $this->PrepareValues($stmt);
        try {
            $stmt->execute();
            $this->{static::$pk} = self::$db->lastInsertId();
            return true;
        } catch (\Exception $e) {
            $this->logger->error("MYSQL INSERT: ". $e->getMessage());
            $this->logger->error("MYSQL INSERT SQL: ". $sql);
            return false;
        }
	}

    ##### Update ##########
    // Parameters :- a: Primary Key
    // Return Type :- true/false
    // Purpose :- same as the Create function above, but for updating instead of creating a new entry
    ###########################
    private function Update(): bool
    {
        $sql = 'UPDATE ' . static::$tableName . ' SET ' . self::BuildSQLstring() . ' WHERE ' . static::$pk . ' = ' . $this->{static::$pk};
        $stmt = self::$db->prepare($sql);
        $this->PrepareValues($stmt);
        try {
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            $this->logger->error("MYSQL UPDATE: ". $e->getMessage());
            $this->logger->error("MYSQL UPDATE SQL: ". $sql);
            return false;
        }
    }

    public function Save(): bool
    {
        return $this->{static::$pk} === null ? $this->Create() : $this->Update();
    }

	##### Delete ##########
	// Parameters :- a: Primary Key
	// Return Type :- true/false
	// Purpose :- deletes something from the database, uses the $tableName variable at the model to figure out where 
	// to delete from
	###########################
	public function Delete($options = false): bool
    {
		$sql = 'DELETE FROM ' . static::$tableName . ' WHERE ';
		$sql .= $options !== false ? $options : static::$pk . ' = ' . $this->{static::$pk};
        $stmt = self::$db->prepare($sql);
        try {
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            $this->logger->error("MYSQL DELETE: ". $e->getMessage());
            $this->logger->error("MYSQL DELETE SQL: ". $sql);
            return false;
        }
	}

	public function UpdateMany($condition = ''): bool
    {
        $sql = 'UPDATE ' . static::$tableName . ' SET ' . self::BuildSQLstring() . ' WHERE ' . $condition;
        $stmt = self::$db->prepare($sql);
        $this->PrepareValues($stmt);
        try {
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            $this->logger->error("MYSQL UPDATE MULTIPLE: ". $e->getMessage());
            $this->logger->error("MYSQL UPDATE MULTIPLE SQL: ". $sql);
            return false;
        }
    }

	##### Count ##########
	// Parameters :- None
	// Return :- count (INT)
	// Purpose :- get the count of some database table ie. Products / Subscribers
	###########################
	public static function Count($options = ''): int
    {
		$sql = "SELECT COUNT(*) FROM " . static::$tableName . ' ' . $options;
		$stmt = self::$db->prepare($sql);
		if ($stmt->execute()) {
		    $count = $stmt->fetchColumn();
		    return $count !== false ? $count : 0;
        }
	}


    ##### Execute SQLs & Get Data functions! ##########
	public static function getAll($options = '', $shift = false)
    {
        $sql = "SELECT * FROM " . static::$tableName . ' ' . $options;
		$stmt = self::$db->prepare($sql);
		$stmt->execute();
		$results = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class());

		if ($results) {
		    return $shift ? array_shift($results) : $results;
        } else {
		    return false;
        }
	}

	public static function getOne($id, $options = '')
    {
	    $where = $options ?: static::$pk . " = '".$id."'";
		$sql = "SELECT * FROM " . static::$tableName . " WHERE ".$where." LIMIT 1";
		$stmt = self::$db->prepare($sql);
		$stmt->execute();
        $result = $stmt->rowCount() == 1 ? $stmt->fetchObject(__CLASS__) : false;
		return is_object($result) ? $result : false;
	}

	public static function getColumns($cols = ['*'], $where = '1', $shift = false)
    {
        $cols_string = is_array($cols) ? implode(', ', $cols) : '*';

        $sql = "SELECT ".$cols_string." FROM " . static::$tableName . " WHERE " . $where;
        $stmt = self::$db->prepare($sql);
        $stmt->execute();

        $result = count($cols) > 1 ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : $stmt->fetchAll(\PDO::FETCH_COLUMN);

        return $result && is_array($result) ?
            $shift != false ? array_shift($result) : $result
            : false;
    }


    public static function getSQL($sql, $options = array(), $shift = false)
    {
        $stmt = self::$db->prepare($sql);
        if (!empty($options)) {
            foreach ($options as $columnName => $option) {
                $type = $option[0];
                $value = $option[1];

                if ($type == 4) {
                    $sanitizedValue = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    $stmt->bindValue(":{$columnName}", $sanitizedValue);
                } else {
                    $stmt->bindValue(":{$columnName}", $value, $type);
                }
            }
        }

        if ($stmt->execute()) {
            if ($shift === true) {
                $result = $stmt->fetchObject(get_called_class());
                return (is_object($result) && !empty($result)) ? $result : false;
            } else {
                switch ($shift) {
                    case 'column':
                        $results = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                        break;
                    case 'assoc' :
                        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        break;
                    default:
                        $results = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class());
                        break;
                }
                return (is_array($results) && !empty($results)) ? $results : false;
            }
        } else {
            return false;
        }
    }
}

