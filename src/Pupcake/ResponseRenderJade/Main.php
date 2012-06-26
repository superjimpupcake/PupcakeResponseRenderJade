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
        if(!isset($config['jade_compiler'])){
            throw new ConfigurationException("Missing Jade compiler, Please set jade_compiler in the configuration!");
        }

        $this->on("pupcake.responserender.render.start", function($event) use ($config) {
            $view_engine = strtolower($event->props('view_engine'));
            if($view_engine == "jade"){
                $path_info = pathinfo($event->props('view_path'));
                if($path_info['extension'] == 'jade'){
                    if(!isset($config['jade_compiler'])){
                        throw new ConfigurationException("Missing jade compiler, please set jade_compiler in configuration!");
                    }
                    //now everything is good, start rendering
                    $renderer = new \PHPNativeJade\Renderer();
                    $renderer->setNativeJadeCompiler($config['jade_compiler']);
                    $view_path = $event->props('view_path');
                    $view_diretory = $event->props('view_directory');
                    if(strlen($view_diretory) > 0){
                        $view_path = $view_diretory."/".$view_path;
                    }

                    $view_cache_enabled = $event->props('view_cache_enabled');

                    if(!$view_cache_enabled){
                        $renderer->render($view_path, $event->props('data'));
                    }

                    $cache_template = $path_info['filename'].".html";
                    if(is_readable($cache_template)){
                        require $cache_template;
                    }
                }
            }
        });
    }

}
