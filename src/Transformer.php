<?php
class Transformer {
    protected $from           = null;
    protected $to             = null;
    protected $replaceHistory = null;

    function __construct($str) {
        $this->str = $str;
    }
    
    function getTagsMatch() {
        preg_match_all('/\>(.*)\</', $this->str, $match);
        return $match[1];
    }
    
    function replaceHistory($from = null, $to = null) {
        $obj        =   new \stdClass;
        $obj->from  =   $from;
        $obj->to    =   $to;
        $this->replaceHistory   =   $obj;
        return $this;
    }
    
    function getReplaceHistory() {
        return $this->replaceHistory;
    }
    
    function getMatches($word = '', $byWord = true) {
        $pattern    =   $byWord === true ? "/\b$word\b/i" : "/$word/i";
        preg_match_all($pattern, $this->str, $match);
        return $match[0];
    }
    
    function replaceIndex($index = 1) {
        $history    =   $this->replaceHistory;
        $from       =   $history->from;
        $to         =   $history->to;
        $search     =   preg_quote($from);
        $this->str  =   preg_replace("/^((?:(?:.*?$search){".--$index."}.*?))$search/i", "$1$to", $this->str);
        return $this;
    }
    
    function replaceFirst($count = 1, $byWord = true) {
        $found  =   $this->getMatches($this->replaceHistory->from, $byWord);
        rsort($found);
        $step   =   0; 
        for($i = 1; $i <= count($found) && $i <= $count; $i++) {
            if($i === 1) $this->replaceIndex($i);
            else $this->replaceIndex($i - $step);
            $step++;
        }
        return $this;
    }

    function replaceLast($count = 1, $byWord = true) {
        $found  =   count($this->getMatches($this->replaceHistory->from, $byWord));
        $step   =   0;
        for($i = $found; $step < $found && $step !== $count; $i--) {
            $this->replaceIndex($i);
            ++$step;
        }
        return $this;
    }

    function replaceRand($count = 1, $byWord = true) {
        $found  =   $this->getMatches($this->replaceHistory->from, $byWord);
        $rnd    =   range(1, count($found));
        shuffle($rnd);
        $rnd    =   array_slice($rnd, 0, $count);
        rsort($rnd, SORT_NUMERIC);
        
        for($i = 0; $i < count($rnd);$i++) {
            $index = $rnd[$i];
            $this->replaceIndex($index);
        }

        return $rnd;
    }

    function replaceBefore($index = 1, $byWord = true) {
        $this->replaceFirst($index - 1, $byWord);
        return $this;
    }
    
    function replaceAfter($index = 1, $byWord = true) {
        $found  =   $this->getMatches($this->replaceHistory->from, $byWord);
        $this->replaceLast(count($found) - $index, $byWord);
        return $this;
    }
    
    function highlightWord($word = null) {
        $pos    =   stripos($this->str, $word);
        if($pos <= 0) return null;
        else {
            $len    =   strlen($word);
            $till   =   $pos + $len;
            return ["from" => $pos, "till" => $till];
        }
    }
    
    function removeBreaks() {
        $this->str  =   preg_replace("/\r|\n/", '', $this->str);
        return $this;
    }
    
    function process() {
        $history    =   $this->replaceHistory;
        $from       =   $history->from;
        $to         =   $history->to;
        $match      =   $this->getTagsMatch();
        $copy       =   $match;
        for($i = 0; $i < count($match);$i++) {
            $match[$i]  =   str_replace($from, $to, $match[$i]);
            if($match[$i] !== $copy[$i]) $this->str =   str_replace($copy[$i], $match[$i], $this->str);
        }
        return $this;
    }

    function getString() {
        return $this->str;
    }

}
?>