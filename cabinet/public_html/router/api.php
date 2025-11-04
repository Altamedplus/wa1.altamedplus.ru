<?php

use APP\Controller\Api\Edna\StatusController;
use APP\Controller\Api\Nalog\FormSetController;
use APP\Controller\Api\Nalog\StatusCheckController;
use Pet\Router\Router;

Router::post('/api/edna/status', [StatusController::class, 'index']);
Router::post('/api/nalog/form/set', [FormSetController::class, 'index']);
Router::options('/api/nalog/form/set', [FormSetController::class, 'options']);
Router::get('/api/nalog/status/check', [StatusCheckController::class, 'index']);
Router::get('/api/nalog/form', [FormSetController::class, 'html']);