<?php

namespace Plu\Converters;
use \Plu\Converters as Conv;

class TurnConverter implements ConverterInterface
{

    /**
     * @var ConverterInterface
     */
    private $c;

    private $app;

    public function __construct($app) {
        $this->c = new Conv\ConfigurableConverter([
            'id' => new Conv\NativeConverter(),
            'gameId' => new Conv\NativeConverter(),
            'number' => new Conv\NativeConverter(),
            'logs' => new Conv\NativeConverter()
        ]);
        $this->app = $app;
    }

    public function toJSON($data)
    {
        $base = $this->c->toJSON($data);
        $base['orders'] = $this->app['converter-service']->batchToJSONObject($data->orders);
        $base['tiles'] = $this->app['converter-service']->batchToJSONObject($data->tiles);
        $base['logs'] = $this->app['converter-service']->batchToJSONObject($data->logs);
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
