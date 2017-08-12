<?php

namespace Plu\Converters;
use \Plu\Converters as Conv;

class OpenGameConverter implements ConverterInterface
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
            'vpLimit' => new Conv\NativeConverter(),
            'password' => new Conv\NativeConverter(),
        ]);
        $this->app = $app;
    }

    public function toJSON($data)
    {
        $base = $this->c->toJSON($data);
        unset($base['password']);
        $base['hasPassword'] = (bool) $data->password;

        $host = $this->app['user-repo']->findByIdentifier($base['userId']);
        $base['host'] = $this->app['converter-service']->toJsonObject($host);

        $players = $this->app['subscribed-player-repo']->findByOpenGame($data);
        $base['players'] = $this->app['converter-service']->batchToJsonObject($players);
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
