<?php
namespace Midnite81\Prowl\Tests\Traits;

trait GenerateStrings
{
    /**
     * Generate string of a certain size
     *
     * @param $int
     * @return string
     */
    protected function stringLength($int): string
    {
        $output = '';

        for($i = 0; $i < $int; $i++) {
            $output .= 'a';
        }

        return $output;
    }
}