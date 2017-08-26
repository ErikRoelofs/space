<?php

namespace Plu\Converters;
use \Plu\Converters as Conv;

class ChannelConverter implements ConverterInterface
{

    /**
     * @var ConverterInterface
     */
    private $c;

    private $app;

    public function __construct($app) {
        $this->c = new Conv\ConfigurableConverter([
            'id' => new Conv\NativeConverter(),
            'name' => new Conv\NativeConverter(),
            'public' => new Conv\BooleanConverter(),
            'created' => new Conv\DateTimeConverter(),
        ]);
        $this->app = $app;
    }

    public function toJSON($data)
    {
        $base = $this->c->toJSON($data);
        if(isset($data->users)) {
            $base['users'] = $data->users;
        }
        if(isset($data->messages)) {
            $base['messages'] = $data->messages;
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
