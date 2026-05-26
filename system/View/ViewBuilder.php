<?php
namespace System\View;
use System\View\Traits\HasExtendsContent;
use System\View\Traits\HasIncludeContent;;
use App\Providers\AppServiceProvider;
use System\View\Traits\HasViewLoader;
use Exception;


class ViewBuilder
{
    use HasViewLoader,HasExtendsContent,HasIncludeContent;

    public $content;
    public $vars = [];

    public function run($dir)
    {
        $this->content = $this->viewLoader($dir);
        $this->checkExtendsContent();
        $this->checkIncludeContent();
        Composer::setViews($this->viewNameArray);
        $this->vars = Composer::getVars();
    }
}