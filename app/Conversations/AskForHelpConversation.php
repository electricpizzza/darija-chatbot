<?php

namespace App\Conversations;

use App\Expression;
use App\Keyword;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Attachments\Video;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;
use Illuminate\Support\Str;

class AskForHelpConversation extends Conversation
{

    public function startConvo()
    {
        $question = Question::create(Expression::find(1)->content);
        $this->askForHelpConvo($question);
    }

    public function askForHelpConvo($question)
    {
        return $this->ask($question, function (Answer $answer) use ($question) {
            // try {
            if (!is_null($keyword =  $this->getKeyword($answer->getValue()))) {
                if ($keyword->expression->attachement) {
                    switch ($keyword->expression->attachement->type) {
                        case 'image':
                            $attachment = new Image($keyword->expression->attachement->link, [
                                'custom_payload' => true,
                            ]);
                            break;
                        case 'video':
                            $attachment = new Video($keyword->expression->attachement->link, [
                                'custom_payload' => true,
                            ]);
                            break;
                        default:
                            # code...
                            break;
                    }


                    $message = OutgoingMessage::create((string)$keyword->expression->content)
                        ->withAttachment($attachment);
                    $this->say($message);
                } else
                    $this->say((string)$keyword->expression->content);
            } else
                $this->say(Expression::find(0)->content);
            $this->finishConvo();
            // } catch (Exception $ex) {
            //     $this->say(Expression::find(0)->content);
            //     $this->askForHelpConvo($question);
            // }
        });
    }



    public function finishConvo()
    {
        $question = Question::create("I hope thats helped.")
            // ->fallback('Unable to ask question')
            // ->callbackId('ask_reason')
            ->addButtons([
                Button::create("That's it ?")->value("done"),
                Button::create('Or do you need somthing else?')->value("help"),
            ]);

        return $this->ask($question, function (Answer $answer) use ($question) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() == "help") {
                    $this->startConvo();
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
        // $this->comment('Message');
        foreach (explode(" ", $expression) as $wrd) {
            if ($keyword = Keyword::where("value", "$wrd")->first())
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
        $this->startConvo();
    }
}
