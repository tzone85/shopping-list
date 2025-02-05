<?php

namespace App\Core;

/**
 * Base Controller
 * 
 * @package App\Core
 */
abstract class Controller
{
    /**
     * Parameters from the matched route
     * @var array
     */
    protected array $route_params = [];

    /**
     * Class constructor
     *
     * @param array $route_params  Parameters from the route
     *
     * @return void
     */
    public function __construct(array $route_params = [])
    {
        $this->route_params = $route_params;
    }

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param string $name  Method name
     * @param array $args Arguments passed to the method
     *
     * @return void
     * @throws \Exception
     */
    public function __call(string $name, array $args)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    /**
     * Before filter - called before an action method.
     *
     * @return bool
     */
    protected function before(): bool
    {
        return true;
    }

    /**
     * After filter - called after an action method.
     *
     * @return void
     */
    protected function after(): void
    {
    }

    /**
     * Render a view
     *
     * @param string $view  The view file
     * @param array  $args  Associative array of data to display in the view
     *
     * @return void
     */
    protected function render(string $view, array $args = []): void
    {
        extract($args, EXTR_SKIP);

        $file = "../app/Views/$view";

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Redirect to a different page
     *
     * @param string $url The relative URL
     *
     * @return void
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url, true, 303);
        exit;
    }
}
