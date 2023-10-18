<?php

// GETS
$router->get('/', 'controllers/index.php');
$router->get('/about', 'controllers/about.php');
$router->get('/register', 'controllers/registration/create.php')->only('guest');
$router->get('/login', 'controllers/sessions/create.php')->only('guest');
$router->get('/game', 'controllers/game/index.php')->only('auth');
$router->get('/game/buy', 'controllers/game/buy.php')->only('auth');
$router->get('/game/sell', 'controllers/game/sell.php')->only('auth');
$router->get('/game/buyer-pay', 'controllers/game/buyer-pay.php')->only('auth');
$router->get('/game/pay-investor', 'controllers/game/pay-investor.php')->only('auth');
$router->get('/game/add-capital', 'controllers/game/add-capital.php')->only('auth');
$router->get('/game/mark-capital', 'controllers/game/mark-capital.php')->only('auth');
$router->get('/game/save', 'controllers/game/save-game.php')->only('auth');
$router->get('/game/load', 'controllers/game/load-game.php')->only('auth');
$router->get('/game/delete-save', 'controllers/game/delete-save.php')->only('auth');
$router->get('/game/date', 'controllers/game/date.php')->only('auth');

//POSTS
$router->post('/game', 'controllers/game/store.php')->only('auth');
$router->post('/register', 'controllers/registration/store.php')->only('guest');
$router->post('/sessions', 'controllers/sessions/store.php')->only('guest');

//DELETE
$router->delete('/sessions', 'controllers/sessions/destroy.php')->only('auth');
$router->delete('/game', 'controllers/game/destroy.php')->only('auth');
