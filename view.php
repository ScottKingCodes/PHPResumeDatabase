<?php

session_start();
require_once "pdo.php";
require_once "validation.php";

if ( ! isset($_SESSION['name']) ) {
    die("Not logged in");
}


if (isset($_REQUEST['profile_id']))
{
    $profile_id = htmlentities($_REQUEST['profile_id']);
    $stmt = $pdo->prepare("
        SELECT * FROM profile 
        WHERE profile_id = :profile_id
    ");
    $stmt->execute([
        ':profile_id' => $profile_id, 
    ]);
    $profile = $stmt->fetch(PDO::FETCH_OBJ);

    $stmt = $pdo->prepare("
        SELECT * FROM position 
        WHERE profile_id = :profile_id
    ");
    $stmt->execute([
        ':profile_id' => $profile_id, 
    ]);
    $position = [];
    while ( $row = $stmt->fetch(PDO::FETCH_OBJ) ) 
    {
        $position[] = $row;
    }
    $positionLen = count($position);

    $education = [];
    $stmt = $pdo->prepare("
        SELECT * FROM education 
        LEFT JOIN institution ON education.institution_id=institution.institution_id
        WHERE profile_id = :profile_id
    ");
    $stmt->execute([
        ':profile_id' => $profile_id, 
    ]);
    while ( $row = $stmt->fetch(PDO::FETCH_OBJ) ) 
    {
        $education[] = $row;
    }
    $educationLen = count($education);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Scott King Resumes</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

        <script
            src="https://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
            crossorigin="anonymous">
        </script>

        <script
            src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
            integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
            crossorigin="anonymous">
        </script>
    </head>
    <body>
        <div class="container">
            <h1>Viewing Resume</h1>
            <div class="row">
                <div class="col-sm-2">First Name:</div>
                <div class="col-sm-4">
                    <?php echo $profile->first_name; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">Last Name:</div>
                <div class="col-sm-4">
                    <?php echo $profile->last_name; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">Email:</div>
                <div class="col-sm-4">
                    <?php echo $profile->email; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">Headline:</div>
                <div class="col-sm-4">
                    <?php echo $profile->headline; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">Summary:</div>
                <div class="col-sm-8">
                    <?php echo $profile->summary; ?>
                </div>
            </div>
            <?php if($educationLen > 0) : ?>
                <div class="row">
                    <div class="col-sm-2">Educations:</div>
                    <div class="col-sm-8">
                        <ul>
                            <?php for($i=1; $i<=$educationLen; $i++) : ?>
                                <li><?php echo $education[$i-1]->year; ?>: <?php echo $education[$i-1]->name; ?></li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            <?php if($positionLen > 0) : ?>
                <div class="row">
                    <div class="col-sm-2">Positions:</div>
                    <div class="col-sm-8">
                        <ul>
                            <?php for($i=1; $i<=$positionLen; $i++) : ?>
                                <li><?php echo $position[$i-1]->year; ?>: <?php echo $position[$i-1]->description; ?></li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            <p><a href="index.php">Done</a></p>
        </div>
    </body>
</html>