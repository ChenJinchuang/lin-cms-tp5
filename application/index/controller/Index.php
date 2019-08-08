<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2019/4/28
 * Time: 14:18
 */

namespace app\index\controller;


use think\Controller;

class Index extends Controller
{
    /**
     * 首次部署显示欢迎用的，部署完成后可以干掉这个index模块的整个目录
     * @return \think\Response
     */
    public function index()
    {
        return response('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>');
    }

    public function apiMdDemo()
    {
        return $this->display(
            $this->html($this->mdToHtml(file_get_contents(env('ROOT_PATH').'api-md.md')))
        );
    }
    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
    private function mdToHtml(string $content){
        return (new \ParsedownExtra)->text($content);
//        return (new \HyperDown\Parser)->makeHtml($content);
    }
    private function html(string $content){
        return "<html>
                        <header>
                            <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
                            <link href=\"https://cdn.bootcss.com/github-markdown-css/3.0.1/github-markdown.css\" rel=\"stylesheet\">
                            <style>
                                .markdown-body {
                                    box-sizing: border-box;
                                    min-width: 200px;
                                    max-width: 980px;
                                    margin: 0 auto;
                                    padding: 45px;
                                }
                            
                                @media (max-width: 767px) {
                                    .markdown-body {
                                        padding: 15px;
                                    }
                                }
                            </style>
                        </header>
                        <body class='markdown-body'>$content<body>
                     </html>";
    }
}