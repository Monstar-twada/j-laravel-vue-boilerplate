<?php

namespace App\Packages\Console;
use Illuminate\Support\Str;

use Illuminate\Routing\Console\ControllerMakeCommand as BaseCommand;


class ControllerMakeCommand extends BaseCommand
{

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = null;

        if($this->option('api')){
            $stub = '/stubs/controller.api.stub';
        }

        if(is_null($stub)){
            return parent::getStub();
        }
        return __DIR__.$stub;
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub,$name)->replaceScaffold($stub)->replaceClass($stub,$name);

    }

    protected function replaceScaffold(&$stub)
    {
        $name = str_replace('Controller','',$this->getNameInput());

        $viewPathName = strtolower(str_replace('\\','.',$name));

        $stub = str_replace('dummyViewPath',$viewPathName,$stub);

        return $this;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Http\Controllers';
        if($this->option('api')){
            $namespace .=  '\Api';

        }
        return $namespace;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        if(!Str::contains(strtolower($name),'Controller')){
            $name .= "Controller";
        }

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }


}
