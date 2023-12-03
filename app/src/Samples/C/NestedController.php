<?php
namespace Samples\C;
use Engine\Output;

class NestedController
{
    private Output $output;

    public function __construct(
        Output $output,
    ){
        $this->output = $output;
    }

    public function main(): void
    {
        /*
         * Add this if you want to protect from hack, this string allow to call this controller only with
         * Services\Controller::load('Samples.Nested.main');
         */
        if (!defined('NESTED')) {echo "No direct access.";return;}

        $this->output->load("Samples/Nested", [],['header'=>false,'footer'=>false]);
    }
}