<?php

namespace Plu\Converters;
use \Plu\Converters as Conv;

class OrderConverter implements ConverterInterface
{

    /**
     * @var ConverterInterface
     */
    private $c;

    private $app;

    public function __construct($app) {
        $this->c = new Conv\ConfigurableConverter([
            'id' => new Conv\NativeConverter(),
            'ownerId' => new Conv\NativeConverter(),
            'turnId' => new Conv\NativeConverter(),
            'orderTypeId' => new Conv\NativeConverter(),
            'data' => new Conv\DataConverter(),
        ]);
        $this->app = $app;
    }

    public function toJSON($data)
    {
        $base = $this->c->toJSON($data);
        if($data->resolution) {
            $base['resolution'] = $this->app['converter-service']->toJSONObject($data->resolution);
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