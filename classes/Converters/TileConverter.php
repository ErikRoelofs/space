<?php

namespace Plu\Converters;
use \Plu\Converters as Conv;

class TileConverter implements ConverterInterface
{

    /**
     * @var ConverterInterface
     */
    private $c;

    private $app;

    public function __construct($app) {
        $this->c = new Conv\ConfigurableConverter([
            'id' => new Conv\NativeConverter(),
            'boardId' => new Conv\NativeConverter(),
            'coordinates' => new Conv\DataConverter(),
        ]);
        $this->app = $app;
    }

    public function toJSON($data)
    {
        $base = $this->c->toJSON($data);
        if($data->pieces) {
            $base['pieces'] = $this->app['converter-service']->batchToJSONObject($data->pieces);
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