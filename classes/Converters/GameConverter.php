<?php

namespace Plu\Converters;
use \Plu\Converters as Conv;

class GameConverter implements ConverterInterface
{

    /**
     * @var ConverterInterface
     */
    private $c;

    private $app;

    public function __construct($app) {
        $this->c = new Conv\ConfigurableConverter([
            'id' => new Conv\NativeConverter(),
            'vpLimit' => new Conv\NativeConverter(),
        ]);
        $this->app = $app;
    }

    public function toJSON($data)
    {
        $base = $this->c->toJSON($data);
        if($data->turns) {
            $base['turns'] = $this->app['converter-service']->batchToJSONObject($data->turns);
        }
        if($data->pieceTypes) {
            $base['pieceTypes'] = $this->app['converter-service']->batchToJSONObject($data->pieceTypes);
        }
        if($data->orderTypes) {
            $base['orderTypes'] = $this->app['converter-service']->batchToJSONObject($data->orderTypes);
        }
        if($data->players) {
            $base['players'] = $this->app['converter-service']->batchToJSONObject($data->players);
        }
        if($data->objectives) {
            $base['objectives'] = $this->app['converter-service']->batchToJSONObject($data->objectives);
        }
        if($data->claimedObjectives) {
            $base['claimedObjectives'] = $this->app['converter-service']->batchToJSONObject($data->claimedObjectives);
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
