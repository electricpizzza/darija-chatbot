<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Str;

class AskForHelpConversation extends Conversation
{

    public function askForHelpConvo()
    {
        $question = Question::create("How can I help you ???");
        return $this->ask($question, function (Answer $answer) {
            if (Str::contains($answer->getValue(), 'time')) {
                $this->say("We are open 24/7");
            }
            $this->finishConvo();
        });
    }

    public function finishConvo()
    {
        $question = Question::create("I hope thets helped ")
            // ->fallback('Unable to ask question')
            // ->callbackId('ask_reason')
            ->addButtons([
                Button::create("That's it ?")->value(true),
                Button::create('Or do you need somthing else?')->value(false),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == false) {
                    $this->askForHelpConvo();
                } else {
                    $this->say("Ok see you later");
                }
            }
        });
    }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $this->askForHelpConvo();
    }
}
