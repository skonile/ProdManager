<?php
#region PHP Code
declare(strict_types = 1);

$error = '';
$dbObj = null;
$dbTablesDir = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR;

// Check if PHP version is compitable.
if (version_compare(PHP_VERSION, '8.0.0', '<'))
    $error = 'Error: PHP version 8.0.0 or higher is required. You have version ' . PHP_VERSION;

// Check if mysqli and pdo extensions are installed.
if($error == '' && !extension_loaded('mysqli'))
    $error = "Mysqli php extension is required for the installation.";

if($error == '' && !extension_loaded('pdo'))
    $error = "PDO php extension is required for ProdManager to work correctly.";

if(count($_POST) > 0){
    // Get database information. 
    if($error == '' && isset($_POST)){
        $hostname = sanitizeString($_POST['db-hostname'] ?? '');
        $username = sanitizeString($_POST['db-username'] ?? '');
        $password = sanitizeString($_POST['db-password'] ?? '');
        $dbName   = sanitizeString($_POST['db-name'] ?? '');
        $adminUsername = sanitizeString($_POST['admin-username']) ?? '';
        $adminPassword = sanitizeString($_POST['admin-password']) ?? '';

        if($hostname == '' || $username == '' || $password == '' || $dbName == '' || $adminUsername == '' || $adminUsername == '')
            $error = "All form fields need to be filled.";

        if($error == '' && !preg_match('/^[a-zA-Z0-9_]+$/', $adminUsername))
            $error = "You can only use a-z, A-Z, 0-9, and _.";

        if($error == '' && strlen($adminPassword) < 6)
            $error = "The admin password has to be 6 or more charectars.";
    }

    // Connect to the database.
    if($error == ''){
        try{
            $dbObj = @(new mysqli($hostname, $username, $password, $dbName));
        } catch(Throwable $e){
            $error = "Could not connect to Database. ";
            $error .= match($e->getCode()){
                2002 => "Could not connect to the given Host({$hostname}).",
                1698, 1045 => "Invalid database username or password.",
                1049 => "Database name, {$dbName}, does not exist or not accesable via this user.",
                default => "Something went wrong with our installation."
            };
        }
    }
    
    // Add tables and data to the database.
    if($error == ''){
        $dbObj->begin_transaction();
        $files = getFilesFromDir($dbTablesDir);
        $res = execSqlFiles($files, $dbObj);
        if(!$res){
            $error = "Some thing went wrong while creating the database tables.";
            $dbObj->rollback();
        }
        $dbObj->commit();
    }

    // Add the admin user.
    if($error == '' && !addAdminUser($adminUsername, $adminPassword, $dbObj))
        $error = "The was a problem add the admin user.";
    
    // redirect user to the login page.
    if($error == '')
        header("Location: /login");
}

function sanitizeString(string $text): string{
    return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
}

function getFilesFromDir(string $dir): array{
    $files = [];
    $filesDirs = scandir($dir);
    foreach($filesDirs as $fileDir){
        if(is_file($dir . $fileDir))
            $files[] = $dir . $fileDir;
    }
    return $files;
}

function execSqlFiles(array $files, mysqli $dbObj): bool{
    foreach($files as $file){
        $res = execSqlFile($file, $dbObj);
        if(!$res)
            return false;
    }
    return true;
}

function execSqlFile(string $file, mysqli $dbObj): bool {
    $content = file_get_contents($file);
    if (!$content)
        return false;

    if ($dbObj->multi_query($content)) {
        do {
            if ($result = $dbObj->store_result())
                $result->free();
        } while ($dbObj->more_results() && $dbObj->next_result());

        if ($dbObj->errno)
            return false;

        return true;
    } else {
        return false;
    }
}

function addAdminUser($username, $password, mysqli $dbObj): bool{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $dbObj->prepare("INSERT INTO user (first_name, last_name, username, email, password, level) 
                            VALUES ('admin', 'admin', ?, 'admin@prodmanager.com', ?, 'admin')");
    if ($stmt === false) {
        return false;
    }

    $stmt->bind_param('ss', $username, $hashedPassword);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}
#endregion
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProdManager Installer</title>
    <style>
        *{
            font-family: Arial, Helvetica, sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            color: #2e2e2e;
        }

        body{
            font-size: 16px;
        }

        .error{
            border: 1px solid rgba(200, 0, 0, .8);
            background-color: rgba(200, 0, 0, .2);
            color: #2e2e2e;
            padding: 15px 20px;
        }

        .installer-cont{
            background-color: #2e2e2e;
            width: 100%;
            height: 100vh;
            overflow: auto;
        }

        .installer-cont-inner{
            width: 80%;
            margin: 50px auto 0 auto;
            padding: 30px 50px;
            background-color: #fff;
        }

        .logo-cont{
            width: 100%;
            height: 80px;
            overflow: hidden;
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-cont img{
            height: 80px;
            width: 354px;
        }

        form{
            overflow: auto;
        }

        .form-row{
            overflow: auto;
        }

        form .form-row-col{
            width: 35%;
            float: left;
            padding: 10px 10px;
        }

        form .form-row-col:first-child{
            width: 30%;
            font-weight: 700;
            padding-top: 20px;
        }

        input{
            width: 100%;
            padding: 10px 7px;
            border: 1px solid rgb(126, 126, 126);
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="installer-cont">
        <div class="installer-cont-inner">
            <div class="logo-cont">
                <div class="logo">
                    <img src="/asserts/img/logo/logo-installer.png" alt="ProdManager LOGO" class="logo-img">
                </div>
            </div>
            <div class="error-cont">
                <?php if($error): ?>
                    <div class="error">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-cont">
                <form method="post" action="/install/" id="installer-id">
                    <div class="form-row">
                        <div class="form-row-col"><label for="db-hostname">Database Hostname:</label></div>
                        <div class="form-row-col"><input type="text" name="db-hostname" id="db-hostname" placeholder="localhost" value="<?= $hostname ?>"></div>
                        <div class="form-row-col">The domain name or IP Address of the server where the Database is hosted.</div>
                    </div>
                    <div class="form-row">
                        <div class="form-row-col"><label for="db-username">Database Username:</label></div>
                        <div class="form-row-col"><input type="text" name="db-username" id="db-username" placeholder="root" value="<?= $username ?>"></div>
                        <div class="form-row-col">The Username for your database.</div>
                    </div>
                    <div class="form-row">
                        <div class="form-row-col"><label for="db-password">Database User Password:</label></div>
                        <div class="form-row-col"><input type="password" name="db-password" id="db-password" placeholder="root" value="<?= $password ?>"></div>
                        <div class="form-row-col">The password for your database user.</div>
                    </div>
                    <div class="form-row">
                        <div class="form-row-col"><label for="db-name">Database Name:</label></div>
                        <div class="form-row-col"><input type="text" name="db-name" id="db-name" placeholder="prodmanager" value="<?= $dbName ?>"></div>
                        <div class="form-row-col">The database you will be using for this project.</div>
                    </div>
                    <div class="form-row">
                        <div class="form-row-col"><label for="admin-username">ProdManager Admin Username:</label></div>
                        <div class="form-row-col"><input type="text" name="admin-username" id="admin-username" placeholder="admin" value="<?= $adminUsername ?>"></div>
                        <div class="form-row-col">The default administrator username.</div>
                    </div>
                    <div class="form-row">
                        <div class="form-row-col"><label for="admin-username">ProdManager Admin Password:</label></div>
                        <div class="form-row-col"><input type="password" name="admin-password" id="admin-password" placeholder="admin" value="<?= $adminPassword ?>"></div>
                        <div class="form-row-col">The default administrator password.</div>
                    </div>
                    <div class="form-row">
                        <input type="submit" value="Submit">
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</body>
</html>
