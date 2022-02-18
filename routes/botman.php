<?php

use App\Http\Controllers\BotManController;

$botman = resolve('botman');

// $botman->hears('Hi', function ($bot) {
//     $bot->reply('Hello!');
// });

$botman->hears('Hi', BotManController::class . "@askForHelp");
$botman->hears('Hello', BotManController::class . "@askForHelp");

$botman->hears('Start conversation', BotManController::class . '@startConversation');
