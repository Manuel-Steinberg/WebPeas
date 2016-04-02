<?php

/**
 * Class IO
 * Search for folders and files within a given root.
 */
class IO
{
    /**
     * @var array
     * holds all folders within of a given root.
     */
    private $folders = array();

    /**
     * @var array
     * holds all files within a given root.
     */
    private $files = array();

    /**
     * @var string
     * holds the root path.
     */
    private $root;

    /**
     * @var array
     * holds the default filters.
     */
    private $filters = array(
        "extension" => ""
    );

    /**
     * @var string
     * holds the relative path + the root path
     */
    private $path;

    /**
     * IO constructor.
     */
    public function IO ($slug = "", $filters) {

        // merge filters with the default ones.
        $this->filters = array_merge($this->filters, $filters);

        // get the root path.
        $this->root = $_SERVER['DOCUMENT_ROOT'];

        // check if you´re working on a DEV-subdomain.
        if ($_SERVER['ENV'] === "DEV") {

            // add DEV-folder to root path.
            $this->root .= DIRECTORY_SEPARATOR .  strtolower($_SERVER['ENV']) . DIRECTORY_SEPARATOR;
        }

        // safe relative path to root path.
        $this->path = $this->root . $slug;


        $this->searchFiles($this->path);
    }

    /**
     * @return array
     * return the folders.
     */
    public function getFolders() {

        return $this->folders;
    }

    /**
     * @param $slug
     * search all relevant files.
     */
    public function searchFiles($slug) {
        $this->folders[] = $slug;

        // scan the directory.
        $files = scandir($slug);

        foreach ($files as $file) {

            // get specific information about the file
            $fileinfo = pathinfo($slug. DIRECTORY_SEPARATOR .$file);

            // add "_notes", when you use Dreamweaver.
            // check file conditions and filters.
            if ($file != "." && $file != "..") {

                // is a folder ...
                if (!isset($fileinfo['extension'])) {
                    // generate new absolute path
                    $pathname = $slug . DIRECTORY_SEPARATOR . $fileinfo['filename'] . DIRECTORY_SEPARATOR;
                    // safe the founded fodlers.
                    $this->folders[] = $pathname;
                    $this->searchFiles($pathname);
                }
                // is a file ...
                if ($fileinfo['extension'] == $this->filters["extension"]) {
                    // safe the absolute path.
                    $pathname = $slug . DIRECTORY_SEPARATOR .  $fileinfo['basename'];

                    $this->files[] = $pathname;
                }
            }
        }
    }

    /**
     * @return array
     * return the files.
     */
    public function getFiles() {

        return array_unique($this->files);
    }
}