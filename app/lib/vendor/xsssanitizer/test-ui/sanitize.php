<?php
$root_path = dirname(__DIR__) . "/";
include $root_path . '/src/FilterRunnerTrait.php';
include $root_path . '/src/FilterInterface.php';
include $root_path . '/src/AttributeFinder.php';
include $root_path . '/src/TagFinderInterface.php';
include $root_path . '/src/TagFinder/ByAttribute.php';
include $root_path . '/src/TagFinder/ByTag.php';
include $root_path . '/src/Filter/AttributeCleaner.php';
include $root_path . '/src/Filter/AttributeContentCleaner.php';
include $root_path . '/src/Filter/EscapeTags.php';
include $root_path . '/src/Filter/FilterRunner.php';
include $root_path . '/src/Filter/MetaRefresh.php';
include $root_path . '/src/Filter/RemoveAttributes.php';
include $root_path . '/src/Filter/RemoveBlocks.php';
include $root_path . '/src/Filter/AttributeContent/CompactExplodedWords.php';
include $root_path . '/src/Filter/AttributeContent/DecodeEntities.php';
include $root_path . '/src/Filter/AttributeContent/DecodeUtf8.php';
include $root_path . '/src/Sanitizer.php';

$htmlInput = $_POST['html-input'];

$Sanitizer = new Phlib\XssSanitizer\Sanitizer();
$sanitized = $Sanitizer->sanitize($htmlInput);

header('X-XSS-Protection:0');

//echo $htmlInput;
echo $sanitized;
