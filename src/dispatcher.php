<?php
function dispatch($routing, $action_url)
{
    $controller_name = $routing[$action_url];

    $model = [];
    $view_name = $controller_name($model); //Wywolanie funkcji z controllers! Model - tablica do zmiennych chwilowo pusty.

    build_response($view_name, $model);
}
function build_response($view, $model)
{
    if (strpos($view, 'redirect:') === 0) { //jezeli return redirect:xxx to od nowa front controller dla danej strony
        $url = substr($view, strlen('redirect:'));
        header("Location: " . $url);
        exit;
    }
	else 
	{
        render($view, $model);
    }
}
function render($view_name, $model)
{
    global $routing;
    extract($model);
    include 'views/' . $view_name . '.php'; //wyswietl html-a
}
