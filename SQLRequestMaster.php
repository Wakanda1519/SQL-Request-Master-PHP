<?PHP

class SQLRequestMaster {

    private $connect;
    private $pdo;

    // Подключение к базе данных
    public function __construct($db_server, $db_user, $db_password, $db_name) {
        
        $dsn = "mysql:host=$db_server;dbname=$db_name";

        try {
            $this->pdo = new PDO($dsn, $db_user, $db_password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Ошибка соединения: " . $e->getMessage());
        }
    }

    // Создание записи в базе данных
    public function Create($table, $data) {
        $keys = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        
        $stmt = $this->pdo->prepare("INSERT INTO $table ($keys) VALUES ($values)");
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
    }

    // Чтение записей в базе данных
    public function Read($table, $conditions = array(), $limit = null) {
        $query = "SELECT * FROM $table";
        
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $params = array();
            foreach ($conditions as $key => $value) {
                $params[] = "$key = :$key";
            }
            $query .= implode(" AND ", $params);
        }
        
        if ($limit) {
            $query .= " LIMIT $limit";
        }
        
        $stmt = $this->pdo->prepare($query);
        
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Обновление записи в базе данных
    public function Update($table, $data, $conditions) {
        $setValues = array();
        foreach ($data as $key => $value) {
            $setValues[] = "$key = :$key";
        }
        $setValuesStr = implode(", ", $setValues);
        
        $query = "UPDATE $table SET $setValuesStr WHERE ";
        $whereConditions = array();
        foreach ($conditions as $key => $value) {
            $whereConditions[] = "$key = :$key";
        }
        $whereConditionsStr = implode(" AND ", $whereConditions);
        
        $query .= $whereConditionsStr;
        
        $stmt = $this->pdo->prepare($query);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
    }

    // Удаление записей из базы данных
    public function Delete($table, $conditions) {
        $query = "DELETE FROM $table WHERE ";
        $whereConditions = array();
        foreach ($conditions as $key => $value) {
            $whereConditions[] = "$key = :$key";
        }
        $whereConditionsStr = implode(" AND ", $whereConditions);
        
        $query .= $whereConditionsStr;
        
        $stmt = $this->pdo->prepare($query);
        
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
    }

    // Закрытие соединения с базой данных
    public function Close() {
        $this->pdo = null;
    }
}