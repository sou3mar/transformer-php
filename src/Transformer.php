<?php
class Transformer {
    protected $from           = null;
    protected $to             = null;
    protected $replaceHistory = null;

    function __construct($str){
        $this->str = $str;
    }

    function from($from){
        $this->from = $from;
        return $this;
    }

    function to($to){
        $this->to = $to;
        return $this;
    }

    function toLower(){
        $this->str = strtolower($this->str);
        return $this;
    }
    
    function getMatchedParts(){
        preg_match_all('/\>(.*)\</', $this->str, $match);
        return $match[1];
    }
    
    function replaceHistory($from = null, $to = null){
        $obj       = new \stdClass;
        $obj->from = $from;
        $obj->to   = $to;
        $this->replaceHistory = $obj;
        return $this;
    }
    
    function getReplaceHistory(){
        return $this->replaceHistory;
    }
    
    function replaceIndex($index = 1){
        $history = $this->replaceHistory;
        $from    = $history->from;
        $to      = $history->to;
        
        $search = preg_quote($from);
        $this->str = preg_replace("/^((?:(?:.*?$search){".--$index."}.*?))$search/i", "$1$to", $this->str); 
        return $this;
    }
    
    function replaceFirst($count = 1, $latin = true){
        $word = $this->replaceHistory->from;
        $pattern = $latin === true ? "/\b$word\b/i" : "/$word/i";
        $srch = preg_match_all($pattern, $this->str, $match);
        $found = $match[0];
        $step = 0;
        for($i = 1;$i <= count($found); $i++){
            if($i > $count) break;
            if($i == 1) $this->replaceIndex($i);
            else $this->replaceIndex($i - $step);
            $step++;
        }
        return $this;
    }
    
    function highlightWord($word = null){
        $pos = stripos($this->str, $word);
        if($pos <= 0) return;
        else {
            $len = strlen($word);
            $till = $pos + $len;
            return "from $pos to $till";
        }
    }

    function process(){
        $match = $this->getMatchedParts();
        $copy = $match;
        for($i = 0;$i < count($match);$i++){
            $match[$i] = str_replace($this->from, $this->to, $match[$i]);
            if($match[$i] !== $copy[$i]) $this->str = str_replace($copy[$i], $match[$i], $this->str);
        }
        return $this;
    }

    function getAsString(){
        return $this->str;
    }

}   
?>