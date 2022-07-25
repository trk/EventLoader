<?php

namespace ProcessWire;

class EventLoader extends WireData implements Module
{
    /**
     * Module info (about module)
     *
     * @return array
     */
    public static function getModuleInfo(): array
    {
        return [
            'title' => 'Event Loader',
            'version' => 1,
            'summary' => 'Event Loader module for ProcessWire CMS/CMF by ALTI VE BIR.',
            'href' => 'https://www.altivebir.com',
            'author' => 'İskender TOTOĞLU | @ukyo(community), @trk (Github), https://www.altivebir.com',
            'requires' => [
                'ProcessWire>=3.0.100',
                'PHP>=7.1.0'
            ],
            'permissions' => [],
            'icon' => 'clock-o'
        ];
    }

    /**
     * Run event files from configs or given path
     */
    public static function load(string $root, string $prefix = '', array $options = [])
    {
        $files = self::finder($root, $prefix, $options);
        foreach ($files as $file) {
            $event = require $file;
            if (!(is_array($event) && isset($event['events']) && is_array($event['events']))) {
                continue;
            }
            if (isset($event['run']) && $event['run'] or !isset($event['run'])) {
                foreach ($event['events'] as $name => $e) {
                    if ($e instanceof \Closure) {
                        wire()->addHook($name, $e);
                    } else if (is_array($e) && isset($e['fn']) && (isset($e['run']) && $e['run'] or !isset($e['run']))) {
                        $type = in_array($e['type'], ['before', 'after', 'method', 'property']) ? 'addHook' . ucfirst($e['type']) : 'addHook';
                        $options = isset($e['options']) && is_array($e['options']) ? $e['options'] : [];
                        wire()->{$type}($name, $e['fn'], $options);
                    }
                }
            }
        }
    }

    /**
     * Event file finder
     *
     * @return array
     */
    protected static function finder(string $root, string $prefix = '', array $options = []): array
    {
        $options = array_merge([
            'subdir' => 'configs/events',
            'pattern' => '*.php'
        ], $options);
        $root = rtrim($root, '/');
        $options['subdir'] = ltrim(rtrim($options['subdir'], '/'), '/');
        return glob("{$root}/{$options['subdir']}/{$prefix}{$options['pattern']}", 0 | GLOB_BRACE | GLOB_NOSORT);
    }
}
