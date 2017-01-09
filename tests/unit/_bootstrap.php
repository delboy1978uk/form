<?php
// Here you can initialize variables that will be available to your tests

namespace Del\Form\Field;

/**
 * @param $filename
 * @return bool
 */
function is_uploaded_file($filename)
{
    return file_exists($filename);
}

/**
 * @param $filename
 * @param $destination
 * @return bool
 */
function move_uploaded_file($filename, $destination)
{
    $copied = copy($filename, $destination);
    return $copied;
}