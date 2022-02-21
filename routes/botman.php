<?php

use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Attachments\Video;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

$botman = resolve('botman');

$botman->hears('Hia', function ($bot) {
    $attachment = new Video('./img/video.mp4', [
        'custom_payload' => true,
    ]);

    // Build message object
    $message = OutgoingMessage::create('You can find')
        ->withAttachment($attachment);

    // Reply message object
    $bot->reply($message);
    // $bot->reply('Hello!');
});

$botman->hears('Hi', BotManController::class . "@askForHelp");
$botman->hears('Hello', BotManController::class . "@askForHelp");

$botman->hears('Start conversation', BotManController::class . '@startConversation');
