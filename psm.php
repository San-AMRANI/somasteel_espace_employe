<?php
try {
   // SQL Server connection details
   $sqlsrvDsn = "sqlsrv:Server=172.10.10.\\ACRONYM;Database=ESclocking";
   $sqlsrvUsername = "sa";
   $sqlsrvPassword = "Acronym_2015";

   // MariaDB connection details
   $mariadbDsn = "mysql:host=192.168.1.;dbname=somasteel_espace_employe";
   $mariadbUsername = "SQLSRV";
   $mariadbPassword = "SQLSRV";

   // Create PDO instance for SQL Server
   $sqlsrvPdo = new PDO($sqlsrvDsn, $sqlsrvUsername, $sqlsrvPassword, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   ]);

   // Create PDO instance for MariaDB
   $mariadbPdo = new PDO($mariadbDsn, $mariadbUsername, $mariadbPassword, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
   ]);

   // Fetch the last inserted record from MariaDB
   $lastInsertedStmt = $mariadbPdo->query("SELECT matricule, Date, hour FROM clocking ORDER BY Date DESC, hour DESC LIMIT 1");
   $lastInsertedRecord = $lastInsertedStmt->fetch(PDO::FETCH_ASSOC);

   // Build SQL Server query based on the last inserted record
   $query = "SELECT Number, 
                     CONVERT(varchar, CAST(Date AS date), 120) AS Date, 
                     CONVERT(varchar, Hour, 108) AS Hour 
              FROM ES_clockings";

   $params = [];

   if ($lastInsertedRecord) {
      // Adjust the query to use correct parameter placeholder and formats
      $query .= " WHERE (CAST(Date AS date) > ?) 
                    OR (CAST(Date AS date) = ? AND Hour > ?)";
      $params = [
         $lastInsertedRecord['Date'],
         $lastInsertedRecord['Date'],
         $lastInsertedRecord['hour'],
      ];
   } else {
      $query .= " WHERE 1=1"; // If no last record, fetch all records
   }

   $query .= " ORDER BY Date ASC, Hour ASC";

   // Debugging output
   echo "SQL Query: $query\n";
   echo "Parameters: " . json_encode($params) . "\n";

   $stmt = $sqlsrvPdo->prepare($query);

   // Execute the query with positional parameters
   $stmt->execute($params);

   $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

   if (!empty($results)) {
      // Prepare the insert statement for MariaDB
      $insertStmt = $mariadbPdo->prepare("INSERT INTO clocking (matricule, Date, hour) VALUES (?, ?, ?)");

      foreach ($results as $row) {
         // Remove leading zeros from 'Number'
         $matricule = ltrim($row['Number'], '0');

         // Insert the new record into MariaDB
         $insertStmt->execute([
            $matricule,
            $row['Date'],
            $row['Hour'],
         ]);
      }

      echo json_encode(['message' => 'Data inserted successfully'], JSON_UNESCAPED_UNICODE);
   } else {
      echo json_encode(['message' => 'No new records to insert'], JSON_UNESCAPED_UNICODE);
   }

} catch (PDOException $e) {
   echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
?>