<?php namespace Generator;

interface GeneratorInterface
{
    public function setContents($contents);

    public function fire();
}