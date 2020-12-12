<?php


namespace Framework\lib;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


//Load Composer's autoloader
require APP_PATH . 'vendor/autoload.php';

class LoggerModel
{
    public $logger;
    public $_logs_name;

    public function __construct($logs_name = 'logs')
    {
        $this->_logs_name = $logs_name;
    }

    public function InitializeLogger(): array
    {
        $dateFormat = "Y-m-d H:i:s a";
        $output = "[%datetime%] %channel%.%level_name%: %message% %context%*\n";
        $formatter = new LineFormatter($output, $dateFormat);


        $logs_stream = self::getLogStream($this->_logs_name);

        $stream = new StreamHandler($logs_stream, \Monolog\Logger::DEBUG);
        $stream->setFormatter($formatter);
        $this->logger = new Logger('logs.'.ucfirst($this->_logs_name));
        $this->logger->pushHandler($stream);
        $this->logger->pushHandler(new FirePHPHandler());

        return ['logger' => $this->logger, 'logs_stream' => $logs_stream];
    }


    public static function getLogStream($logs_name): string
    {
        return LOGS_PATH.strtoupper($logs_name).'-'.date('F-Y').".log";
    }
}