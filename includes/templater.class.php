<?php

class templater
{
	protected $vars;
	protected $source;
	protected $tplpath;
	protected $delim_left;
	protected $delim_right;
	private $var_regex;
	private $var_replace;

	public function __construct($tplpath='.', $delim_left='{', $delim_right='}') {
		$this->vars = array();
		$this->tplpath = $tplpath;
		$this->_set_delimiters($delim_left, $delim_right);
		$this->var_regex = '~' . preg_quote($this->delim_left, '~') . '\s*([a-z_][a-z0-9_]*(?:\.[a-z0-9_]+)*)\s*' . preg_quote($this->delim_right, '~') . '~ie';
		$this->var_replace = 'sprintf("%s", \'{$this->vars[\\\'\' . str_replace(".", "\'][\'", "\\1") . \'\\\']}\')';
    }

	private function _get_source($filename) {
		$this->source = file_get_contents($filename);
	}

	private function _set_delimiters($left, $right) {
        $this->delim_left = $left;
        $this->delim_right = $right;
    }

    public function assign($var, $value) {
        $this->vars[$var] = $value;
    }

    private function _parse($source) {
        $strings = explode("\n", $source);
        $parsed = '';
        for ($i=0, $num=sizeof($strings); $i<$num; ++$i)
            $parsed .=  'print "' . addcslashes($strings[$i], '"\\$') . '\n";' . "\n";
        $parsed = preg_replace($this->var_regex, $this->var_replace, $parsed);
        $parsed = preg_replace('~<!--.*?-->\s*~s', '', $parsed);
        return $parsed;
    }

    public function get_parsed($filename, $print=false, $debug=false) {
        if ( !file_exists("{$this->tplpath}/$filename") )
            return false;
        $this->_get_source("{$this->tplpath}/$filename");
        if ( $debug ) {
            print $this->_parse($this->source);
        }
        elseif ( $print ) {
            eval($this->_parse($this->source));
        }
        else {
            ob_start();
            eval($this->_parse($this->source));
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
    }
}

?>