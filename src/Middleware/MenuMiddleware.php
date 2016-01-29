<?php

namespace Vexilo\Utilities\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Menu;
use Config;

class MenuMiddleware
{
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Build the menú according to the configuration array
     * @param Menu $menu
     * @param array $navArray
     * @param $id
     *
     * @return mixed
     */
    private function buildMenu($menu, $navArray)
    {

        foreach ($navArray as $index => $nav) {
            $label = '';
            $url = false;
            $attribs = false;

            if (array_key_exists('label', $nav)) {
                $label = $nav['label'];
            }
            if (array_key_exists('url', $nav)) {
                $url = $nav['url'];
            } else {
                $options = [];

                if (array_key_exists('route', $nav)) {
                    $options['route'] = $nav['route'];
                }

                if (array_key_exists('action', $nav)) {
                    $options['action'] = $nav['action'];
                }
            }

            if ($url) {
                $menuItem = $menu->add($label, $url);
            } else {
                $menuItem = $menu->add($label, $options);
            }

            if (array_key_exists('icon', $nav)) {
                $menuItem->data('icon', $nav['icon']);
            }

            if (array_key_exists('hidden', $nav)) {
                $menuItem->data('hidden', $nav['hidden']);
            }

            // If have pages call the same function recursevely with the last inserted menu item
            if (array_key_exists('pages', $nav) && count($nav['pages'])) {
                $this->buildMenu($menuItem, $nav['pages']);
            }
        }

        return $menu;

    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $menuName Name of the menu
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $menuName = null)
    {
        $user = $this->auth->user();

        $navConfig = Config::get('nav');

        // Find if exists a menu configuration for this param
        if (!is_null($menuName) && isset($navConfig[$menuName]) && count($navConfig[$menuName])) {
            // Build the menu
            $navArray = $navConfig[$menuName];
            Menu::make($menuName, function ($menu) use ($user, $navArray) {
                $menu = $this->buildMenu($menu, $navArray);
            });
        }
        return $next($request);
    }
}
