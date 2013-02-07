<?php
class Hashit_Task
{
    public function run($arguments)
    {
        echo $arguments[0]." ==> ".Hash::make($arguments[0]);
    }
}