<?php
namespace luoluolzb;
use \Monolog\{Logger, Handler};

class MyLogger extends Logger
{
    public function __construct()
    {
        parent::__construct('MyLogger');
        $this->pushHandler(new Handler\StreamHandler('app.log', Logger::WARNING));
    }
}
