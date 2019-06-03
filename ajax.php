<?php
    // Connect to DB
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'mysqlDataManager';

    //Set DSN
    $dsn = 'mysql:host=localhost;dbname=mysqlDataManager';

    // $countryName = $_POST['name'];
    // $shortDesc = $_POST['shortDesc'];
    // $longDesc = $_POST['longDesc'];

    try {
        // Create A PDO Instance
        $pdo = new PDO($dsn, $username, $password);
        // set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        // Set the PDO attribute to OBJ
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    } catch (PDOException $e) {
        print 'Connection Failed' . $e->getMessage() . '<br>';
        die();
    }


    if (isset($_POST['key'])) {
        // Get Data
        $countryName = $_POST['countryName'];
        $shortDesc = $_POST['shortDesc'];
        $longDesc = $_POST['longDesc'];

        //Check Key
        if ($_POST['key'] === 'addNew') {
            $stmt = $pdo->prepare('SELECT id FROM country WHERE countryName = :countryName');
            $stmt->execute(['countryName' => $countryName]);

            // Check to see if country already exits
            if ($stmt->rowCount() > 0) {
                exit('Country with this name already exists!');
            } else {
                // Does not exist add country
                $sql = 'INSERT INTO country(countryName, shortDesc, longDesc) VALUES(:countryName, :shortDesc, :longDesc)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['countryName' => $countryName, 'shortDesc' => $shortDesc, 'longDesc' => $longDesc]);
                exit('Country has been added');
            }
         }
    }