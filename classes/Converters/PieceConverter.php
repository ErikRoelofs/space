<?php

namespace Plu\Converters;
use \Plu\Converters as Conv;

class PieceConverter implements ConverterInterface
{

    /**
     * @var ConverterInterface
     */
    private $c;

    private $app;

    public function __construct($app) {
        $this->c = new Conv\ConfigurableConverter([
            'id' => new Conv\NativeConverter(),
            'typeId' => new Conv\NativeConverter(),
            'ownerId' => new Conv\NativeConverter(),
            'turnId' => new Conv\NativeConverter(),
            'tileId' => new Conv\NativeConverter(),
            'traits' => new Conv\TraitConverter(),
        ]);
        $this->app = $app;
    }

    public function toJSON($data)
    {
        $base = $this->c->toJSON($data);
        $conv = new TraitConverter();
        $base['traits'] = $conv->toJSON($this->app['piece-service']->getAllTraits($data));
        $base['name'] = $this->app['piece-type-repo']->findByIdentifier($base['typeId'])->name;
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
