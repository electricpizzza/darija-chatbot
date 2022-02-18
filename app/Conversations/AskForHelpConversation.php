<?php

namespace App\Conversations;

use App\Expression;
use App\Keyword;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Str;

class AskForHelpConversation extends Conversation
{

    public function askForHelpConvo()
    {
        $question = Question::create(Expression::find(1)->content);
        return $this->ask($question, function (Answer $answer) {
            if (!is_null($keyword =  $this->getKeyword($answer->getValue())))
                $this->say((string)$keyword->expression->content);
            else
                $this->say(Expression::find(0)->content);
            $this->finishConvo();
        });
    }

    public function finishConvo()
    {
        $question = Question::create("I hope thets helped.")
            // ->fallback('Unable to ask question')
            // ->callbackId('ask_reason')
            ->addButtons([
                Button::create("That's it ?")->value("done"),
                Button::create('Or do you need somthing else?')->value("help"),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == "help") {
                    $this->askForHelpConvo();
                } else {
                    $this->getUserInfoConvo();
                }
            }
        });
    }

    public function getUserInfoConvo()
    {
        $question = Question::create(Expression::find(2)->content);
        return $this->ask($question, function (Answer $answer) {
            $user_name = $answer->getValue();
            $question = Question::create(Str::replaceFirst('[name]', $user_name, Expression::find(3)->content));
            return $this->ask($question, function (Answer $answer) {
                $user_email = $answer->getValue();
                $this->say("Ok, thnak you see you later");
            });
        });
    }


    /***
     * to get the keyword from expression
     */

    public function getKeyword($expression): Keyword
    {
        // return "test";
        foreach (explode(" ", $expression) as $wrd) {
            if ($keyword = Keyword::where("value", $wrd)->first())
                return $keyword;
        }
        return new Keyword();
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
