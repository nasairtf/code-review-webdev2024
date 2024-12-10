<?php

use Doctum\Doctum;
use Doctum\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

// Define source directories to include
$includeDirs = [
    'classes',
    'public_html',
    'tests',
];

// Define source directories to exclude
$excludeDirs = [
    'legacy',
    'jpgraph',
    'vendor',
];

// Exclude specific directories/files
$finder = Finder::create()
    ->in($includeDirs)
    ->exclude($excludeDirs)
    ->name('*.php');

// Optional: Git versioning support (omit if not needed)
//$versions = GitVersionCollection::create(__DIR__)
//    ->addFromTags('*')
//    ->add('main', 'Main development branch');

return new Doctum($finder, [
    'title'                => 'WebDev2024 Documentation',
    'build_dir'            => __DIR__ . '/docs/doctum',
    'cache_dir'            => __DIR__ . '/.doctum-cache',
    'default_opened_level' => 2,
//    'versions'             => $versions, // Optional
]);
