<?php

namespace App\Lib\Helpers;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ToolboxHelper
{
    /**
     * @param array<int|string, mixed> $array1
     * @param array<int|string, mixed> $array2
     *
     * @return array<int|string, mixed>
     */
    public static function arrayMergeRecursiveDistinct(array &$array1, array &$array2): array
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = self::arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * Validate recaptcha
     *
     * @param \Illuminate\Http\Request $request
     * @param boolean                  $isV3
     * @return void
     */
    public static function validateRecaptcha(Request $request, bool $isV3 = true)
    {
        Validator::validate($request->all(), [
            'g-recaptcha-response' => [
                'required', 'string',
                // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed, Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed
                function ($attribute, $value, $fail) use ($isV3) {
                    try {
                        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                            'secret' => config('services.google.recaptcha.private'),
                            'response' => \strval($value),
                            'remoteip' => app('clientIp')
                        ]);
                        if ($e = $response->toException()) {
                            throw $e;
                        }
                        if ($isV3) {
                            if (
                                !$response->json('success') or
                                !$response->json('challenge_ts') or
                                Carbon::parse($response->json('challenge_ts'))->lessThan(\now('utc')->subMinutes(5)) or
                                $response->json('hostname') !== \parse_url(config('app.url'), \PHP_URL_HOST) or
                                !$response->json('score')
                            ) {
                                throw new \RuntimeException();
                            }
                            if (\floatval($response->json('score')) < 0.8) {
                                $fail("Vous n'avez pas obtenu un score assez élevé lors de la validation du recaptcha");
                            }
                            return;
                        }
                        if (
                            !$response->json('success') or
                            !$response->json('challenge_ts') or
                            Carbon::parse($response->json('challenge_ts'))->lessThan(\now('utc')->subMinutes(5)) or
                            $response->json('hostname') !== \parse_url(config('app.url'), \PHP_URL_HOST)
                        ) {
                            throw new \RuntimeException();
                        }
                    } catch (\Exception $e) {
                        $fail('Erreur lors de la validation du formulaire veuillez réssayer');
                    } //end try
                }
            ]
        ], [], ['g-recaptcha-response' => 'reCaptcha']);
    }

    /**
     * Strip scheme (https) on oembed urls
     * Only embed allowed are for MP4 self hosted videos right now
     *
     * https is force by ckeditor MediaElements which natively supports
     * only cloud hosted medias (youtube, spotify etc..)
     *
     * @param string $input
     * @return string
     */
    public static function stripCkEditorSchemesOnMediaInputs(string $input): string
    {
        $input = \preg_replace('/(?<=<oembed url=")(https:\/\/)(?=.*?">)/m', '', $input);
        return $input;
    }

    /**
     * Assert That no Back Office url is present on txt
     *
     * @param string $field
     * @param string $text
     * @return string
     * @throws \Illuminate\Validation\ValidationException If url is a bo url.
     */
    public static function assertNoInternalBoUrls(string $field, string $text): string
    {
        $routes = Route::getRoutes();
        // * Url replace and email replace.
        $text = preg_replace_callback(
            // phpcs:ignore Generic.Files.LineLength.TooLong
            '/(?P<url>(?:http[s]?:\/\/.)?(?:www\.)?[-a-zA-Z0-9@%._\+~#=]{2,256}\.[a-z]{2,6}\b(?:[-a-zA-Z0-9@:%_\+.~#?&\/\/=]*))/',
            function ($matches) use ($routes, $field) {
                $match = collect($matches)->first() ?? '';
                $url   = $match;
                try {
                    $request = Request::create($url);
                    // If a route is match then it do not throws exception.
                    if (
                        \parse_url(config('app.url'), \PHP_URL_HOST) === \parse_url($url, \PHP_URL_HOST) &&
                        trim($routes->match($request)->getPrefix() ?? '', '/') === 'bo'
                    ) {
                        // Removes the matched url.
                        throw ValidationException::withMessages([
                            $field => trans(
                                'crud.libs.toolbox.assertNoInternalBoUrls',
                                ['attribute' => $field]
                            )
                        ]);
                    }
                    return $match;
                } catch (NotFoundHttpException $e) {
                    return $match;
                }
            },
            $text
        );
        return $text;
    }

    /**
     * Assert That only external url is present on txt
     *
     * @param string $field
     * @param string $text
     * @return string
     * @throws \Illuminate\Validation\ValidationException If url is a bo url.
     */
    public static function assertNoInternalUrls(string $field, string $text): string
    {
        self::assertNoInternalBoUrls($field, $text);
        $routes = Route::getRoutes();
        // * Url replace and email replace.
        $text = preg_replace_callback(
            // phpcs:ignore Generic.Files.LineLength.TooLong
            '/(?P<url>(?:http[s]?:\/\/.)?(?:www\.)?[-a-zA-Z0-9@%._\+~#=]{2,256}\.[a-z]{2,6}\b(?:[-a-zA-Z0-9@:%_\+.~#?&\/\/=]*))/',
            function ($matches) use ($routes, $field) {
                $match = collect($matches)->first() ?? '';
                $url   = $match;
                try {
                    $request = Request::create($url);
                    // If a route is match then it do not throws exception.
                    if (
                        \parse_url(config('app.url'), \PHP_URL_HOST) === \parse_url($url, \PHP_URL_HOST) &&
                        $routes->match($request)
                    ) {
                        // Removes the matched url.
                        throw ValidationException::withMessages([
                            $field => trans(
                                'crud.libs.toolbox.assertNoInternalUrls',
                                ['attribute' => $field, 'appname' => config('app.name')]
                            )
                        ]);
                    }
                    return $match;
                } catch (NotFoundHttpException $e) {
                    return $match;
                }
            },
            $text
        );
        return $text;
    }

    /**
     * Mutltibyte string replace
     *
     * @param array|string $search
     * @param array|string $replace
     * @param array|string $subject
     * @param integer      $count
     * @return array|string
     */
    public static function mbReplace(
        array|string $search,
        array|string $replace,
        array|string $subject,
        int &$count = 0
    ): array|string {
        if (!is_array($search) && is_array($replace)) {
            return false;
        }
        if (is_array($subject)) {
            // Call mb_replace for each single string in $subject .
            foreach ($subject as &$string) {
                $string = &self::mbReplace($search, $replace, $string, $count);
            }
        } elseif (is_array($search)) {
            if (!is_array($replace)) {
                foreach ($search as &$string) {
                    $subject = self::mbReplace($string, $replace, $subject, $count);
                }
            } else {
                $n = max(count($search), count($replace));
                while ($n--) {
                    $subject = self::mbReplace(current($search), current($replace), $subject, $count);
                    next($search);
                    next($replace);
                }
            }
        } else {
            $parts   = mb_split(preg_quote($search), $subject);
            $count   = count($parts) - 1;
            $subject = implode($replace, $parts);
        } //end if
        return $subject;
    }

    /**
     * Asserts that the field is unique
     *
     * @param string                                  $table
     * @param string                                  $field
     * @param string|integer|float|boolean|null|array $value
     * @param integer|null                            $id
     * @param callable|null                           $query
     * @return void
     * @throws \Illuminate\Validation\ValidationException If the field is not unique.
     */
    public static function assertFieldIsUnique(
        string $table,
        string $field,
        string|int|float|bool|null|array $value,
        ?int $id = null,
        ?callable $query = null
    ) {
        if (!$query) {
            Validator::make([$field => $value], [
                self::mbReplace('.', '\.', $field) => Rule::unique($table, $field)->ignore($id),
            ], [
                self::mbReplace('.', '\.', "{$field}.unique")
                => trans('crud.libs.toolbox.assertFieldIsUnique', ['attribute' => $field, 'value' => $value])
            ])->validate();
            return;
        }
        Validator::make([$field => $value], [
            $field => Rule::unique($table)->where($query)->ignore($id),
        ], [
            $field . '.unique' => trans(
                'crud.libs.toolbox.assertFieldIsUnique',
                ['attribute' => $field, 'value' => $value]
            )
        ])->validate();
    }

    /**
     * Get original polyfill with autotranslate behavior
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $attribute
     * @return mixed
     */
    public static function getOriginal(Model $model, string $attribute): mixed
    {
        if (\in_array('Spatie\Translatable\HasTranslations', \class_uses_recursive($model))) {
            return $model->getTranslation(
                $attribute,
                $model->getLocale(),
                $model->useFallbackLocale()
            );
        }
        return $model->getOriginal($attribute);
    }

    /**
     * Dynamic model query
     *
     * @param string                                          $defaultLocale
     * @param \Illuminate\Contracts\Database\Eloquent\Builder $q
     * @param string                                          $column
     * @param string                                          $value
     * @return \Illuminate\Contracts\Database\Eloquent\Builder
     */
    public static function queryColumnWithLocales(
        string $defaultLocale,
        Builder $q,
        string $column,
        string $value
    ): Builder {
        $currentLocale = app()->currentLocale();
        $locales       = collect(config('app.locales'))
            ->mapWithKeys(fn (string $locale) => [$locale => $locale]);
        if (!$locales->has($defaultLocale)) {
            // * Remove default locale.
            $locales->pull($defaultLocale);
        }
        if (!$locales->has($currentLocale)) {
            // * Remove current locale.
            $locales->pull($currentLocale);
        }

        return $q->where(function (Builder $q) use ($defaultLocale, $currentLocale, $locales, $column, $value) {
            // * Query default locale
            $q->whereRaw("JSON_EXTRACT({$column}, '$.{$defaultLocale}') = '{$value}'");
            if ($defaultLocale !== $currentLocale) {
                // * Query current locale
                $q->whereRaw("JSON_EXTRACT({$column}, '$.{$currentLocale}') = '{$value}'");
            }
            // * Query any other locale
            $locales->each(fn ($locale) => $q->orWhereRaw(
                "JSON_EXTRACT({$column}, '$.{$locale}') = '{$value}'"
            ));
        });
    }

    /**
     * Generates an array from a dot separated string
     *
     * @param string $path
     * @param mixed  $value
     * @param string $separator
     * @return array
     */
    public static function dotStringToNestedArray(string $path, mixed $value, string $separator = '.'): array
    {
        $arr  = $value;
        $keys = array_reverse(explode($separator, $path));

        foreach ($keys as $key) {
            $arr = [$key => $arr];
        }
        return $arr;
    }

    /**
     * Convert deeply an object to array
     *
     * @param array|object $input
     * @return array
     * @throws \RuntimeException If $input var is a scalar value.
     */
    public static function objectToArray($input): array
    {
        // Only process if it's an object or array being passed to the function.
        if (is_object($input) or is_array($input)) {
            $ret = (array)$input;
            foreach ($ret as &$item) {
                // Recursively process EACH element only if not scalar.
                $item = (is_object($item) or is_array($item)) ?
                    self::objectToArray($item) : $item;
            }
            return $ret;
        } else {
            throw new \RuntimeException('Cannot handle $input of type ' . \gettype($input));
        }
    }

    /**
     * Convert deeply an array to object
     *
     * @param array|object $input
     * @return object
     * @throws \RuntimeException If $input var is a scalar value.
     */
    public static function arrayToObject($input): object
    {
        // Only process if it's an object or array being passed to the function.
        if (is_object($input) or is_array($input)) {
            $ret = (object)$input;
            foreach ($ret as &$item) {
                // Recursively process EACH element only if not scalar.
                $item = (is_object($item) or is_array($item)) ?
                    self::arrayToObject($item) : $item;
            }
            return $ret;
        } else {
            throw new \RuntimeException('Cannot handle $input of type ' . \gettype($input));
        }
    }

    /**
     * Validate picture
     *
     * @param string      $attribute
     * @param string|null $value
     * @param callback    $fail
     * @param object      $sizes
     * @return void
     */
    public static function validatePicture(string $attribute, string|null $value, callable $fail, object $sizes)
    {
        if (!($useStorage = Storage::exists($value)) and !File::exists($value)) {
            $fail(trans(':attribute n\'existe pas dans le système de fichier', [
                'attribute' => $attribute
            ]));
            return;
        }
        /** @var string */
        $fileFullPath = self::getUploadedFileFullPath($value, $useStorage);
        if (
            !\in_array(self::getUploadedFileMimeType($value, $useStorage), [
                'image/jpeg', 'image/jpg', 'image/png', 'image/gif'
            ])
        ) {
            $fail(trans(':attribute doit être une image de type \'image/jpeg,image/jpg,image/png,image/gif\'', [
                'attribute' => $attribute
            ]));
        }
        if (!($dimensions = \getimagesize($fileFullPath)) or !isset($dimensions[0]) or !isset($dimensions[1])) {
            $fail(trans(':attribute impossible de valider les dimensions', [
                'attribute' => $attribute
            ]));
        } else {
            $width  = intval($dimensions[0]);
            $height = intval($dimensions[1]);

            $xMessage = trans(
                ":attribute la largeur doit être comprise entre {$sizes->minWith}px et {$sizes->maxWith}px"
            );
            if ($sizes->minWith === $sizes->maxWith) {
                $xMessage = trans(":attribute la largeur doit être de {$sizes->minWith}px");
            }
            $yMessage = trans(
                ":attribute la hauteur doit être comprise entre {$sizes->minWith}px et {$sizes->maxWith}px"
            );
            if ($sizes->minHeight === $sizes->maxHeight) {
                $yMessage = trans(":attribute la hauteur doit être de {$sizes->minWith}px");
            }

            if (($width < $sizes->minWith) or ($width > $sizes->maxWith)) {
                $fail($xMessage);
            }
            if (($height < $sizes->minHeight) or ($height > $sizes->maxHeight)) {
                $fail($yMessage);
            }
        } //end if
    }

    /**
     * Get the file full path
     *
     * @param string  $value
     * @param boolean $useStorage
     * @return string
     */
    private static function getUploadedFileFullPath(string $value, bool $useStorage): string
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter */
        $disk = Storage::disk();
        return !$useStorage ? (realpath($value) ?: $value) : $disk->path($value);
    }

    /**
     * Get the file mimetype
     *
     * @param string  $value
     * @param boolean $useStorage
     * @return string|false
     */
    private static function getUploadedFileMimeType(string $value, bool $useStorage)
    {
        return !$useStorage ? File::mimeType($value) : Storage::mimeType($value);
    }
}
