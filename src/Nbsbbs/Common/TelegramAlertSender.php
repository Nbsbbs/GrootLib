<?php

namespace Nbsbbs\Common;

class TelegramAlertSender
{
    /**
     * @var string
     */
    protected static string $telegramToken = '';

    /**
     * @var string
     */
    protected static string $telegramBot = '';

    /**
     * @param string $bot
     * @param string $token
     * @return void
     */
    public static function init(string $bot, string $token)
    {
        self::$telegramBot = $bot;
        self::$telegramToken = $token;
    }

    /**
     * @param string $message
     * @return void
     */
    public static function sendTelegramMessages(string $message): void
    {
        if (self::$telegramToken === '') {
            // no initialization
            return;
        }

        $bot_api_key = self::$telegramToken;
        $bot_username = self::$telegramBot;
        $date = new \DateTime();
        $message = $date->format('H:i:s. ').$message;

        try {
            // Create Telegram API object
            $telegram = new \Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
            $result = \Longman\TelegramBot\Request::sendMessage([
                'chat_id' => 222740160,
                'text' => $message,
            ]);
            $result = \Longman\TelegramBot\Request::sendMessage([
                'chat_id' => 494582842,
                'text' => $message,
            ]);
        } catch (\Longman\TelegramBot\Exception\TelegramException $e) {
            // log telegram errors
            // echo $e->getMessage();
        }
    }
}
