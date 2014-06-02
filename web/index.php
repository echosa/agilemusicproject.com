<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

$app = new Silex\Application();

$app->register(new FormServiceProvider());

$app['debug'] = true;
$app['upload_folder'] = __DIR__ . '/images/photos';
$app['config'] = parse_ini_file(__DIR__ . '/../config/amp.ini', true);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html');
});

$app->get('/blog', function () use ($app) {
    return $app['twig']->render('blog.twig');
});

$app->get('/about', function () use ($app) {
    return $app['twig']->render('about.twig');
});

$app->get('/music', function () use ($app) {
    return $app['twig']->render('music.twig');
});

$app->get('/contactus', function () use ($app) {
    return $app['twig']->render('contact.twig');
});

$app->get('/agile', function () use ($app) {
    return $app['twig']->render('agile.twig');
});

$app->get('/photos', function () use ($app) {
    return $app['twig']->render('photos.twig');
    try {
        $dsn = 'mysql:host=' . $app['config']['MySQL']['host'] . '; dbname=' . $app['config']['MySQL']['database'];
        $dbh = new PDO($dsn, $app['config']['MySQL']['username'], $app['config']['MySQL']['password']);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT filename FROM photos";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    $dbh = null;
    return $app['twig']->render('photos.twig', array('results' => $results));
});

$app->get('/meettheband', function () use ($app) {
    return $app['twig']->render('meetTheBand.twig');
});

$app->get('/meettheband/update', function () use ($app) {
    $app->match('/form', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time
    $data = array(
        'name' => 'Your name',
        'email' => 'Your email',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('name')
        ->add('email')
        ->add('gender', 'choice', array(
            'choices' => array(1 => 'male', 2 => 'female'),
            'expanded' => true,
        ))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        // do something with the data

        // redirect somewhere
        return $app->redirect('...');
    }

    // display the form
    return $app['twig']->render('index.twig', array('form' => $form->createView()));
$app->get('/bandmembers', function () use ($app) {
    return $app['twig']->render('bandMembers.twig');
});
    return $app['twig']->render('meetTheBandUpdate.twig');
});

$app->get('/bandMembers', function () use ($app) {
    return $app['twig']->render('bandMembers.twig');
});


$app->run();
$app->get( '/upload', function() {
    $upload_form = <<<EOF
<html>
<body>
<form enctype="multipart/form-data" action="" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
    Upload this file:
<br><br>
<input name="image" type="file" />
<br><br>
    <input type="submit" value="Send File" />
</form>
</body>
</html>
EOF;
    return $upload_form;
});

$app->post('/upload', function(Request $request) use ($app) {
    $file_bag = $request->files;
    if ($file_bag->has('image')) {
        $image = $file_bag->get('image');
        $image->move($app['upload_folder'], $image->getClientOriginalName());
    }
    try {

        $dsn = 'mysql:host=' . $app['config']['MySQL']['host'] . '; dbname=' . $app['config']['MySQL']['database'];
        $dbh = new PDO($dsn, $app['config']['MySQL']['username'], $app['config']['MySQL']['password']);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO photos (filename) VALUES (:filename)";
        $stmt = $dbh->prepare($sql);
        $filename = $image->getClientOriginalName();
        $stmt->bindParam(':filename', $filename);
        $stmt->execute();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    $dbh = null;
    return $app->redirect('/photos');
});

$app->run();
