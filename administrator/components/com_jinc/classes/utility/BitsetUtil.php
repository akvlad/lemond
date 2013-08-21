<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fay
 * Date: 15.08.13
 * Time: 1:27
 * To change this template use File | Settings | File Templates.
 */

class BitsetUtil {
    public $bits = array();
    private $chars;
    private $digits;
    function __construct(){
        $this->chars = array('A' => 10, 'B' => 11, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15);
        $this->digits =array( 10 => 'A', 11 => 'B', 12 => 'C', 13 => 'D', 14 => 'E', 15 => 'F');
    }

    private function HexToDec($hex){
        if(preg_match('/^[0-9]$/',$hex)){
            return (int)$hex;
        }
        else return $this->chars[$hex];
    }

    private function HexToDecDbl($hex){
        return $this->HexToDec($hex[1])*16+$this->HexToDec($hex[0]);
    }

    private function DecToHex($dec){
        if($dec < 10){
            return (string)$dec;
        }
        return $this->digits($dec);
    }
    private function DecToHexDbl($dec){
        $res = $this->DecToHex($dec % 16);
        $dec = $dec >> 4;
        $res.= $this->DecToHex($dec % 16);
        return $res;
    }

    function init ($hexStr){
        for($i=0;$i<strlen($hexStr);$i+=2){
            $this->bits[$i/2]= $this->HexToDecDbl(substr($hexStr,$i,2));
        }
    }

    function initFromArray ($arr,$len){
        $bytes=ceil($len/8);
        for($i=0;$i<$bytes;++$i)
            $this->bits[$i]=0;
        foreach($arr as $k => $v){
            $this->setBit($k);
        }
    }

    function setBit($i){
        $byte = floor($i / 8);
        $bit = $i % 8;
        $this->bits[$byte] = $this->bits[$byte] | (1 << $bit);
    }

    function logicalAnd (BitsetUtil $c){
        $res = new BitsetUtil();
        foreach($this->bits as $k => $v){
            $res->bits[$k] = $v & $c->bits[$k];
        }
        return $res;
    }

    function logicalOr (BitsetUtil $c){
        $res = new BitsetUtil();
        foreach($this->bits as $k => $v){
            $res->bits[$k] = $v | $c->bits[$k];
        }
        return $res;
    }

    function toHexStr(){
        $res='';
        for($i=0;$i<count($this->bits);++$i){
            $res.=$this->DecToHexDbl($this->bits[$i]);
        }
        return $res;
    }
    function equal(BitsetUtil $bitset){
        foreach($this->bits as $k => $v){
            if($bitset->bits[$k] != $v) return false;
        }
        return true;
    }
}