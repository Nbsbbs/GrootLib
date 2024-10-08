<?php

use Nbsbbs\Common\TelegramAlertSender;
use Nbsbbs\Common\ThumbnailIdentifier;
use Noobus\GrootLib\Entity\Config\ClickhouseConfig;
use Noobus\GrootLib\Storage\Clickhouse\ClientFactory;

require_once '../bootstrap.php';
$config = new ClickhouseConfig('127.0.0.1', '8123', 'test');
$clientFactory = new ClientFactory($config);
$client = $clientFactory->getClient();
$startStamp = time();

$ud = new ThumbnailIdentifier(1, 2);
$isBeingRestarted = false;
TelegramAlertSender::init($_ENV['TELEGRAM_BOT'], $_ENV['TELEGRAM_API_TOKEN']); // no time to create proper initialization

do {
    try {
        $stmt = $client->select('show databases');
        $stmt->error();
        // echo 'Ok' . PHP_EOL;
        if ($isBeingRestarted) {
            TelegramAlertSender::sendTelegramMessages('Clickhouse response OK after restart');
            $isBeingRestarted = false;
        }
    } catch (\Throwable $e) {
        if (preg_match('#Empty reply from server#s', $e->getMessage()) or preg_match('#Connection timed out#s', $e->getMessage())) {
            TelegramAlertSender::sendTelegramMessages('Watchdog is trying to restart clickhouse: "'.$e->getMessage().'"');
            echo stamp().'Error: ' . $e->getMessage() . ', trying to restart clickhouse' . PHP_EOL;
            exec('/usr/local/bin/sudo /root/bin/restart-clickhouse', $out);
            $result = implode(PHP_EOL, $out) . PHP_EOL;
            echo $result;
            // TelegramAlertSender::sendTelegramMessages("Here's what we got: " . PHP_EOL . $result);
            sleep(15);
            $isBeingRestarted = true;
        } else {
            TelegramAlertSender::sendTelegramMessages("Unknown error: " . PHP_EOL . $e->getMessage());
            echo stamp().'Error: ' . $e->getMessage() . ', no restart' . PHP_EOL;
        }
    }
    if (!$isBeingRestarted) {
        sleep(2);
        if (time() > ($startStamp + 300)) {
            die(stamp().'My job here is done.' . PHP_EOL);
        }
    }
} while (true);

function stamp(): string
{
    return (new \DateTime())->format('Y-m-d H:i:s').': ';
}
