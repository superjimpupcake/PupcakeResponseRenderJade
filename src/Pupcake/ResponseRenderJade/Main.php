<?php
/**
 * ResponseRenderJade plugin
 */
namespace Pupcake\ResponseRenderJade;

use Pupcake;

class Main extends Pupcake\plugin
{
    public function load($config = array())
    {
        $this->on("pupcake.responserender.render.start", function($event){
            $response = $event->props('response');
            $response->send("hello here");
        });
    }
}
