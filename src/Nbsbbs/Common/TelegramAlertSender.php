<?php

namespace Nbsbbs\Common;

class TelegramAlertSender
{
    public static function sendTelegramMessages(string $message): void
    {
        $telegramToken = '486920176:AAEMefxmhSdimqGUCnIw0H5Wda4hnHvsYUM';
        $bot_api_key = $telegramToken;
        $bot_username = 'mixailo_bot';
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
