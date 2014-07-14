<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

try {
    $app['config'] = new AMP\Config(__DIR__ . '/../config/amp.ini');
} catch (AMP\Exception\FileNotFoundException $e) {
    $app['config'] = new AMP\Config();
}

try {
    $app['debug'] = $app['config']->get('debug');
} catch (AMP\Exception\ConfigValueNotFoundException $e) {
    $app['debug'] = false;
}

$app->error(function (AMP\Exception\ExceptionInterface $e) use ($app) {
    if ($app['debug'] === false) {
        return new Response($app['twig']->render('error.twig', array(
            'errorMessage' => $e->getUserFriendlyErrorMessage())));
    }
});

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => $app['config']->get('MYSQL_HOST'),
        'dbname' => $app['config']->get('MYSQL_DBNAME'),
        'user' => $app['config']->get('MYSQL_USER'),
        'password' => $app['config']->get('MYSQL_PASSWORD'),
    ),
));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array('translator.messages' => array()));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../views', 'twig.options' => array('strict_variables' => false)
));

$yaml = new Symfony\Component\Yaml\Parser();
try {
    $securityConfig = $yaml->parse(file_get_contents(__DIR__. '/../config/security.yml'));
} catch (Symfony\Component\Yaml\Exception\ParseException $e) {
    echo('Unable to parse the YAML string: ' . $e->getMessage());
}
$securityConfig['security.firewalls']['general']['users'] = $app->share(function () use ($app) {;
    return new AMP\User\UserProvider($app['db']);
});
$app->register(new Silex\Provider\SecurityServiceProvider(), $securityConfig);

$ampServiceProvider = new \AMP\AMPServiceProvider();
$app['photoUploadManager'] =  new \AMP\UploadManager(__DIR__ . '/images/photos');
$ampServiceProvider->registerDAOs($app);
$ampServiceProvider->registerForms($app);

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.twig');
});

$app->get('/login', function (Request $request) use ($app) {
    if (strpos($request->server->get('HTTP_REFERER'), '/login') === false) {
        $app['session']->set('lastUrlBeforeClickingLogin', $request->server->get('HTTP_REFERER'));
    }
    return $app['twig']->render('login.twig', array('error' => $app['security.last_error']($request),
                                                    'lastPage' =>  $app['session']->get('lastUrlBeforeClickingLogin')));
});

$app->mount('/meettheband', new AMP\Controller\MeetTheBandController());
$app->mount('/account', new AMP\Controller\AccountController());
$app->mount('/contactus', new AMP\Controller\ContactUsController());
$app->mount('/about', new AMP\Controller\AboutPageController());
$app->mount('/agile', new AMP\Controller\AgilePageController());
$app->mount('/music', new AMP\Controller\MusicPageController());
$app->mount('/photos', new AMP\Controller\PhotosController());
$app->mount('/news', new AMP\Controller\NewsPageController());

$app->run();
