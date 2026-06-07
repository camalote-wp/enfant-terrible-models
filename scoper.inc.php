<?php
$wpExcludesClassesFile = __DIR__ . '/vendor/sniccowp/php-scoper-wordpress-excludes/generated/exclude-wordpress-classes.json';
$wpExcludesFunctionsFile = __DIR__ . '/vendor/sniccowp/php-scoper-wordpress-excludes/generated/exclude-wordpress-functions.json';
$wpExcludesConstantsFile = __DIR__ . '/vendor/sniccowp/php-scoper-wordpress-excludes/generated/exclude-wordpress-constants.json';
$wpExcludesInterfacesFile = __DIR__ . '/vendor/sniccowp/php-scoper-wordpress-excludes/generated/exclude-wordpress-interfaces.json';

$excludesClasses = array_merge(
    json_decode(file_get_contents($wpExcludesClassesFile), true),
    json_decode(file_get_contents($wpExcludesInterfacesFile), true)
);

$excludesFunctions = json_decode(file_get_contents($wpExcludesFunctionsFile), true);
$excludesConstants = json_decode(file_get_contents($wpExcludesConstantsFile), true);

return [
    'prefix' => 'CamaloteWP\\ZorzalModels\\Vendor',
    'output-dir' => 'vendor-scoped',
    'finders' => [
        Isolated\Symfony\Component\Finder\Finder::create()
            ->files()
            ->in(__DIR__ . '/.tmp-scope/vendor')
            ->exclude([
                'bin',
            ]),
    ],
    'exclude-classes' => $excludesClasses,
    'exclude-functions' => $excludesFunctions,
    'exclude-constants' => $excludesConstants,
    'patchers' => [
        /**
         * Patch PSR-4 autoload entries
         */
        static function (string $filePath, string $prefix, string $content): string {
            if (str_contains($filePath, 'autoload_psr4.php') || str_contains($filePath, 'autoload_classmap.php')) {
                // Match any vendor namespace starting from top-level
                $content = preg_replace_callback(
                    '/\'([A-Za-z0-9_\\\\]+)\'\s*=>\s*array\((.*?)\)/s',
                    function ($matches) use ($prefix) {
                        $namespace = $matches[1];
                        $paths = $matches[2];

                        // Ignore already-prefixed namespaces
                        if (str_starts_with($namespace, $prefix)) {
                            return $matches[0];
                        }

                        // Return patched line with prefixed namespace
                        return "'" . $prefix . '\\\\' . $namespace . "' => array(" . $paths . ")";
                    },
                    $content
                );
            }
            return $content;
        },

        static function (string $filePath, string $prefix, string $content): string {
            // Only target the specific files that need the helper rewrite
            $isHelperFile = str_contains($filePath, 'illuminate') && str_contains($filePath, 'helpers.php');
            $isSpatieResolver = str_contains($filePath, 'spatie') && str_contains($filePath, 'StructuresResolver.php');

            if (!$isHelperFile && !$isSpatieResolver) {
                return $content;
            }

            // Fix the function_exists checks in helpers.php
            if ($isHelperFile) {
                $content = preg_replace_callback(
                    '/function_exists\s*\(\s*\'([^\']+)\'\s*\)/',
                    function ($matches) use ($prefix) {
                        return "function_exists('" . $prefix . "\\\\" . $matches[1] . "')";
                    },
                    $content
                );
                // Clean up any accidentally doubled prefixes or stray '$'
                $content = str_replace('$\\' . $prefix, '\\' . $prefix, $content);
            }

            // Forcefully qualify collect() calls in Spatie
            if ($isSpatieResolver) {
                $content = preg_replace(
                    '/(?<!->|::|function\s|use\sfunction\s)\bcollect\s*\(/',
                    '\\' . $prefix . '\\collect(',
                    $content
                );
            }

            return $content;
        },
    ],
];