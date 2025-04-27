<?php
class Workout {
    private $conn;
    private $table_name = "workouts";

    public $id;
    public $user_id;
    public $distance;
    public $duration;
    public $average_speed;
    public $calories_burned;
    public $start_location;
    public $end_location;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    user_id = :user_id,
                    distance = :distance,
                    duration = :duration,
                    average_speed = :average_speed,
                    calories_burned = :calories_burned,
                    start_location = :start_location,
                    end_location = :end_location,
                    created_at = :created_at";

        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->distance = htmlspecialchars(strip_tags($this->distance));
        $this->duration = htmlspecialchars(strip_tags($this->duration));
        $this->average_speed = htmlspecialchars(strip_tags($this->average_speed));
        $this->calories_burned = htmlspecialchars(strip_tags($this->calories_burned));
        $this->start_location = htmlspecialchars(strip_tags($this->start_location));
        $this->end_location = htmlspecialchars(strip_tags($this->end_location));
        $this->created_at = date('Y-m-d H:i:s');

        // Bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":distance", $this->distance);
        $stmt->bindParam(":duration", $this->duration);
        $stmt->bindParam(":average_speed", $this->average_speed);
        $stmt->bindParam(":calories_burned", $this->calories_burned);
        $stmt->bindParam(":start_location", $this->start_location);
        $stmt->bindParam(":end_location", $this->end_location);
        $stmt->bindParam(":created_at", $this->created_at);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }
}
?> 