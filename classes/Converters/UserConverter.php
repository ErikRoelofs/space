<?php

namespace Plu\Converters;
use \Plu\Converters as Conv;

class UserConverter implements ConverterInterface
{

    /**
     * @var ConverterInterface
     */
    private $c;

    private $app;

    public function __construct($app) {
        $this->c = new Conv\ConfigurableConverter([
            'id' => new Conv\NativeConverter(),
            'username' => new Conv\NativeConverter(),
            'password' => new Conv\PasswordConverter(),
            'email' => new Conv\RestrictedConverter(new Conv\NativeConverter(), new Conv\Restrictions\BackendOnlyRestriction()),
            'registrationDate' => new Conv\RestrictedConverter(new Conv\DateTimeConverter(), new Conv\Restrictions\BackendOnlyRestriction()),
            'confirmed' => new Conv\RestrictedConverter(new Conv\BooleanConverter(), new Conv\Restrictions\BackendOnlyRestriction()),
            'roles' => new Conv\ExplodeConverter(),
        ]);
        $this->app = $app;
    }

    public function toJSON($data)
    {
        $base = $this->c->toJSON($data);
        $base['name'] = $base['username'];
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
        $base = $this->c->fromDB($obj, $data);
        $base->name = $base->username;
        return $base;
    }

}
