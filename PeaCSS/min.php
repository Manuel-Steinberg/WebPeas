<?php
/**
 * Class CSS
 */
class CSS {

    private $buffer = "";

    /**
     * CSS constructor.
     * @param string $file
     */
    public function CSS($file = "") {

        $this->buffer .= file_get_contents($file);

        // Compress the concated CSS file.
        //$this->compress();
    }



    /**
     * compress the CSS file.
     */
    private function compress() {

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


$css = new CSS("a+b.css");

/**
 * Ideally, you wouldn't need to change any code beyond this point.
 */
$buffer = $css->getBuffer();

// Enable GZip encoding.
ob_start("ob_gzhandler");
// Enable caching
header('Cache-Control: public');
// Expire in one day
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
// Set the correct MIME type, because Apache won't set it for us
header("Content-type: text/css");
// Write everything out
echo($buffer);
?>