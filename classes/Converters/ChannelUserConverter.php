<?php

namespace Plu\Converters;
use \Plu\Converters as Conv;

class ChannelUserConverter implements ConverterInterface
{

    /**
     * @var ConverterInterface
     */
    private $c;

    private $app;

    public function __construct($app) {
        $this->c = new Conv\ConfigurableConverter([
            'id' => new Conv\NativeConverter(),
            'userId' => new Conv\NativeConverter(),
            'channelId' => new Conv\NativeConverter(),
            'lastRead' => new Conv\DateTimeConverter(),
        ]);
        $this->app = $app;
    }

    public function toJSON($data)
    {
        $base = $this->c->toJSON($data);
        if(isset($data->user)) {
            $base['user'] = $this->app['converter-service']->toJsonObject($data->user);
        }
        return $base;
    }

    public function fromJSON($obj, $data)
    {
        return $this->c->fromJSON($obj, $data);
    }

    public function toDB($data)
    {
        return $this->c->toDB($data);
    }

    public function fromDB($obj, $data)
    {
        return $this->c->fromDB($obj, $data);
    }


}
