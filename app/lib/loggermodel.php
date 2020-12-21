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
    public $_logs_dir;

    public function __construct($logs_name, $logs_dir = '')
    {
        $this->_logs_name = $logs_name;
        $this->_logs_dir = $logs_dir;
    }

    public static function Instance($logs_name, $logs_dir = ''): LoggerModel
    {
        return new self($logs_name, $logs_dir);
    }

    public static function getLogStream($logs_name, $logs_dir = ''): string
    {
        $stream = LOGS_PATH;
        $stream .= $logs_dir ? $logs_dir.DS : '';
        $stream .= strtoupper($logs_name).'-'.date('F-Y').".log";
        return $stream;
    }


    public function InitializeLogger(): Logger
    {
        $dateFormat = "Y-m-d H:i:s a";
        $output = "*[%datetime%] %channel%.%level_name%: %message% %context%\n";
        $formatter = new LineFormatter($output, $dateFormat);


        $logs_stream = self::getLogStream($this->_logs_name, $this->_logs_dir);

        $stream = new StreamHandler($logs_stream, \Monolog\Logger::DEBUG);
        $stream->setFormatter($formatter);
        $this->logger = new Logger('logs.'.ucfirst($this->_logs_name));
        $this->logger->pushHandler($stream);
        $this->logger->pushHandler(new FirePHPHandler());

        return $this->logger;
    }
}