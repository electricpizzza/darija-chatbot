<?php

use App\Http\Controllers\BotManController;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$botman->hears('Salam', BotManController::class . "@askForHelp");

$botman->hears('Start conversation', BotManController::class . '@startConversation');
