<?php

namespace AppBundle;

/**
 * Contains all events thrown in the App bundle.
 */
final class AppEvents
{
    /**
     * The onPlay event occurs when a (usually user provided) number is
     * checked against the mystery number.
     *
     * This event provide this value.
     *
     * @Event("AppBundle\Event\PlayEvent")
     *
     * @var string
     */
    const onPlay = 'mystery-number.play';
}
