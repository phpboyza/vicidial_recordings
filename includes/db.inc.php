<?php
    try {
      $conn = new PDO("mysql:host={$db['host']};dbname={$db['name']}", $db['username'], $db['password']);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }