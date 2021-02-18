<?php

namespace App\Admin\Controllers;

use App\Models\Classifys;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Admin;


function GetRandStr($length)
{
    //字符组合
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $len = strlen($str) - 1;
    $randstr = '';
    for ($i = 0; $i < $length; $i++) {
        $num = mt_rand(0, $len);
        $randstr .= $str[$num];
    }
    return $randstr;
}


class ClassifysController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品分类';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Classifys());
        $grid->model()->orderBy('ord', 'desc');
        $grid->column('id', __('Id'));
        $grid->column('name', __('Class Name'));
        $grid->column('ord', __('Ord'));
        //$grid->column('c_status', __('C status'))->editable('select', [1 => '启用', 2 => '禁用']);
        // 设置text、color、和存储值
        $c_status = [
            'on'  => ['value' => 1, 'text' => '启用', 'color' => 'primary'],
            'off' => ['value' => 2, 'text' => '禁用', 'color' => 'default'],
        ];
        $grid->column('c_status')->switch($c_status);
        $grid->column('info', __('Commodity information'));
        $grid->column('appid');
        $grid->column('appsecret');
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Classifys());

        $form->text('name', __('Class Name'))->rules('required', ['不能为空']);
        $form->number('ord', __('Ord'))->default(1);

        $states = [
            'on'  => ['value' => 1, 'text' => '启用', 'color' => 'primary'],
            'off' => ['value' => 2, 'text' => '禁用', 'color' => 'default'],
        ];
        //$form->switch($c_status)->rules('required', ['请选择状态'])->default(1);;
        $form->switch('c_status', '状态')->rules('required', ['请选择状态'])->states($states);


        $form->text('info');
        $form->text('appid')->default(GetRandStr(16))->readonly();
        Admin::script('console.log("hello world");');
        $form->text('appsecret')->default(GetRandStr(16))->readonly();


        if (1) {
            $form->tools(function (Form\Tools $tools) {


                // 添加一个按钮, 参数可以是字符串, 或者实现了Renderable或Htmlable接口的对象实例
                $tools->append('<a class="btn btn-sm" onclick="$("#appid").val("2222")"><i class="fa fa-trash"></i> 随机生成</a>');
            });
        }

        $form->footer(function ($footer) {
            // 去掉`查看`checkbox
            $footer->disableViewCheck();
        });
        $form->tools(function (Form\Tools $tools) {
            // 去掉`查看`按钮
            $tools->disableView();
        });
        return $form;
    }
}
