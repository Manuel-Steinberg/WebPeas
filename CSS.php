<?php

include_once ("IO.php");

/**
 * Class CSS
 */
class CSS {

    /**
     * @const array
     * holds specific filters.
     */
    const filters = array(
        "extension" => "css"
    );

    /**
     * @var IO
     * holds the IO-Object.
     */
    private $IO;

    private $buffer = "";

    /**
     * CSS constructor.
     * @param string $path
     */
    public function CSS($path = "") {

        // create IO class.
        $this->IO = new IO($path, self::filters);

        // concat the CSS files.
        $this->concat();
        // Compress the concated CSS file.
        $this->compress();
    }

    /**
     * concat all CSS files.
     */
    private function concat() {

        foreach ($this->IO->getFiles() as $cssFile) {
            $this->buffer .= file_get_contents($cssFile);
        }
    }

    /**
     * compress the CSS file.
     */
    private function compress() {

        // remove comments
        //$this->buffer = preg_replace('!\/\*[^*]*\*+([^\/][^*]*\*+)*\/!', '', $this->buffer);
        // remove space after colons
        //$this->buffer = str_replace(': ', ':', $this->buffer);
        // remove whitespace
        //$this->buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $this->buffer);

        $this->buffer = preg_replace('!/\*.*?\*/!s','', $this->buffer);
        $this->buffer = preg_replace('/\n\s*\n/',"\n", $this->buffer);

        // space
        $this->buffer = preg_replace('/[\n\r \t]/',' ', $this->buffer);
        $this->buffer = preg_replace('/ +/',' ', $this->buffer);
        $this->buffer = preg_replace('/ ?([,:;{}]) ?/','$1',$this->buffer);

        // trailing;
        $this->buffer = preg_replace('/;}/','}',$this->buffer);
    }

    /**
     * @return string
     * return the concated and compressed CSS file.
     */
    public function getBuffer() {
        
        return $this->buffer;
    }
}
?>
