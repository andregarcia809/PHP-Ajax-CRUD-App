<?php
    // Connect to DB
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'mysqlDataManager';

    //Set DSN
    $dsn = 'mysql:host=localhost;dbname=mysqlDataManager';

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

        // Check CRUD Key

         // CREATE
         if ($_POST['key'] === 'addNew') {
            $sql = 'SELECT id FROM  country WHERE countryName = :countryName';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['countryName' => $countryName]);

            // Check to see if country already exits
            if ($stmt->rowCount() > 0) {
                exit('Country with this name already exists!');
            } else {
                // Does not exist add country
                $sql = 'INSERT INTO country(countryName, shortDesc, longDesc) VALUES(:countryName, :shortDesc, :longDesc)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['countryName' => $countryName, 'shortDesc' => $shortDesc, 'longDesc' => $longDesc]);
                exit('Success! Your country has been added');
            }
         }

          // VIEW
        if ($_POST[key] === 'getExistingData') {
            $start =  $_POST['start'];
           $limit = $_POST['limit'];
           // When using LIMIT clause you have to bind the Variables that are passed to placeholders with type
           $sql = 'SELECT id, countryName FROM country LIMIT :start, :limit';

           $stmt = $pdo->prepare($sql);
           $stmt->bindValue(':start', $start, PDO::PARAM_INT);
           $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
           $stmt->execute();
           if ($stmt->rowCount() > 0) {
               $response = '';
               while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                   $response .= '
                       <tr>
                           <td>' .$data['id']. '</td>
                           <td id="country_' .$data['id']. '">' .$data['countryName']. '</td>
                           <td class="text-md-center d-flex flex-column flex-md-row justify-content-between">
                               <input type="button" onclick="viewOrEdit(' .$data['id']. ', \'edit\')" value="Edit" class="btn btn-success mb-3 mb-md-0">
                               <input type="button" onclick="viewOrEdit(' .$data['id']. ', \'view\')" value="View" class="btn btn-primary mb-3 mb-md-0">
                               <input type="button"  id="rowID_' .$data['id']. '" onclick="deleteRow(' .$data['id'].')" data-countryName="'.$data['countryName'].'" value="Delete" class="btn btn-danger">
                           </td>
                       </tr>
                   ';
               }
               exit($response);

           } else {
               exit('reachedMax');
           }
       }

         // GET & EDIT
        if ($_POST[key] === 'getRowData') {
            $rowID = $_POST['rowID'];

            $sql = 'SELECT countryName, shortDesc, longDesc FROM country WHERE id = :rowID';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['rowID' => $rowID]);
            $data = $stmt->fetch();

            exit(json_encode($data));
        }

        // UPDATE
        if ($_POST[key] === 'updateRow') {
            $rowID = $_POST['rowID'];

            $sql = 'UPDATE country SET countryName = :countryName, shortDesc = :shortDesc, longDesc = :longDesc WHERE id = :rowID';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['countryName' => $countryName, 'shortDesc' => $shortDesc, 'longDesc' => $longDesc, 'rowID' => $rowID]);

            exit('Success! Country has been updated');
        }

         // DELETE
         if ($_POST['key'] === 'deleteRow') {
            $rowID = $_POST['rowID'];

            $sql = 'DELETE FROM country WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $rowID]);
            // Now the page  needs to reflect change
            exit($countryName. ' has been deleted.');
        }
    }