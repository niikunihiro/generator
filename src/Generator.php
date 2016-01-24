<?php namespace Generator;

use Generator\File;
use SplFileObject;

/**
 * Class Generator
 * @package Nielsen\Generator
 */
class Generator implements GeneratorInterface
{
    /** @var string Contents */
    private $contents;

    /**
     * Generator constructor.
     * @param SplFileObject $file
     */
    public function __construct(SplFileObject $file)
    {
        $this->file = $file;
    }

    /**
     * @param $contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * @return int
     */
    public function fire()
    {
        return $this->file->fwrite($this->contents);
    }

    /**
     * @param array $data
     */
    public function replace(array $data)
    {
        array_map(function ($key, $val) {
            $this->contents = str_replace($key, $val, $this->contents);
        }, array_keys($data), array_values($data));
    }
}