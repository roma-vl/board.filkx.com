<?php

namespace App\States\Adverts;

use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class BaseState extends State
{
    public static string $className;

    /**
     * @throws InvalidConfig
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->allowTransition(Draft::class, Moderation::class)
            ->allowTransition(Moderation::class, Active::class)
            ->allowTransition(Moderation::class, ModerationFail::class)
            ->allowTransition(Active::class, Closed::class);
    }
}
