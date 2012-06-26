<?php
/**
 * ResponseRenderJade plugin
 */
namespace Pupcake\ResponseRenderJade;

use Pupcake;
use Everzet;

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
                    $dumper = new Everzet\Jade\Dumper\PHPDumper();
                    $dumper->registerVisitor('tag', new Everzet\Jade\Visitor\AutotagsVisitor());
                    $dumper->registerFilter('javascript', new Everzet\Jade\Filter\JavaScriptFilter());
                    $dumper->registerFilter('cdata', new Everzet\Jade\Filter\CDATAFilter());
                    $dumper->registerFilter('php', new Everzet\Jade\Filter\PHPFilter());
                    $dumper->registerFilter('style', new Everzet\Jade\Filter\CSSFilter());

                    $parser = new Everzet\Jade\Parser(new Everzet\Jade\Lexer\Lexer());
                    $jade   = new Everzet\Jade\Jade($parser, $dumper);

                    $view_path = $event->props('view_path');
                    $view_diretory = $event->props('view_directory');
                    if(strlen($view_diretory) > 0){
                        $view_path = $view_diretory."/".$view_path;
                    }

                    $view_cache_enabled = $event->props('view_cache_enabled');

                    if(!$view_cache_enabled){
                        $output = $jade->render($view_path);
                        file_put_contents($view_path.".php", $output);
                    }

                    if(is_readable($view_path.".php")){
                        extract($event->props('data'));
                        require $view_path.".php";
                    }
                }
            }
        });
    }

}
