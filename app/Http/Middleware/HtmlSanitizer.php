<?php

namespace App\Http\Middleware;

use App\Lib\Helpers\ToolboxHelper;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HtmlSanitizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|array             ...$params
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, \Closure $next, string|array ...$params): Response
    {
        $inputs = $request->all();
        /** @var callable|null */
        $cleanInput = null;
        $cleanInput = function (
            null|string|array &$input,
            string $name,
            ?string $rootName = null
        ) use (
            $params,
            &$cleanInput
        ): void {
            // * Nested element array
            if (is_array($input)) {
                array_walk_recursive($input, $cleanInput, $name);
            }
            if (!is_string($input)) {
                return;
            }
            if (in_array($name, $params) or in_array($rootName ?? $name, $params)) {
                // ? Is a json input ?
                if ($isJson = \json_decode($input, true) and is_array($isJson)) {
                    // ! Decode each json field recursive.
                    \array_walk_recursive($isJson, function (string|array &$item) {
                        if (\is_string($item)) {
                            $item = clean($item);
                            $item = ToolboxHelper::stripCkEditorSchemesOnMediaInputs($item);
                        }
                    });
                } else {
                    $input = clean($input);
                    $input = ToolboxHelper::stripCkEditorSchemesOnMediaInputs($input);
                }
            } else {
                $input = strip_tags($input);
            }
        };
        \array_walk($inputs, $cleanInput);
        $request->merge($inputs);
        return $next($request);
    }
}
