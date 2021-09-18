<?php
function loadTemplate($templateFileName, $variables = [])
{
    extract($variables);

    ob_start();
    include  __DIR__ . '/../templates/' . $templateFileName;

    return ob_get_clean();
}
try {
    include __DIR__ . '/../includes/DatabaseConnection.php';
    include __DIR__ . '/../classes/DatabaseTable.php';
    include __DIR__ . '/../classes/controllers/JokeController.php';

    $jokesTable = new DatabaseTable($pdo, 'joke', 'id');
    $authorsTable = new DatabaseTable($pdo, 'author', 'id');
    $jokeController = new JokeController($jokesTable, 
    $authorsTable);

    $action = $_GET['action'] ?? 'home';
    // $page = $jokeController->$action();
    // $output = $page['output'];
    // if (isset($page['variables'])) extract($page['variables']);
    // ob_start();
    // // echo ('page :');
    // // var_dump($page);
    // // var_dump($page['variables']);
    // // echo $page['template'];
    // // echo 'what';
    // include __DIR__.'/../templates/'.$page['template'];
    if ($action == strtolower($action)) {
        $page = $jokeController->$action();
    }
    else {
        http_response_code(301);
        header('location: index.php?action=' .strtolower($action));
    }

    $title = $page['title'];
    $output = loadTemplate($page['template'],$page['variables']??[]);

    } catch (PDOException $e) {
        $title = 'An error has occurred';
    
        $output = 'Database error: ' . $e->getMessage() . ' in '
         . $e->getFile() . ':' . $e->getLine();
    }
    
    include  __DIR__ . '/../templates/layout.html.php';
    