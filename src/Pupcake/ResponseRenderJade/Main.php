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
        $this->on("pupcake.responserender.render.start", function($event) use ($config) {
            $view_engine = strtolower($event->props('view_engine'));
            if($view_engine == "jade"){
                $path_info = pathinfo($event->props('view_path'));
                if($path_info['extension'] == 'jade'){
                    //now everything is good, start rendering
                    $renderer = new \PHPNativeJade\Renderer();
                    if(isset($config['jade_compiler'])){
                        $renderer->setNativeJadeCompiler($config['jade_compiler']);
                    }
                    $view_path = $event->props('view_path');
                    $view_diretory = $event->props('view_directory');
                    if(strlen($view_diretory) > 0){
                        $view_path = $view_diretory."/".$view_path;
                    }

                    $view_cache_enabled = $event->props('view_cache_enabled');
                    $cache_template = $view_path.".html";

                    if(!$view_cache_enabled){
                        $renderer->render($view_path, $event->props('data'));
                    }
                    else{ //cache is enabled, first look to see if the cache_template exists
                        if(is_readable($cache_template)){
                            require $cache_template;
                        }
                        else{
                            $renderer->render($view_path, $event->props('data'));
                        }
                    }
                }
            }
        });
    }

}
