<?php

namespace App\Services;

class PageRankService
{
    public function __construct(private readonly PageFetcher $fetcher) {}

    public function getAssignedPageRank(string $domain): string
    {
        $url = 'http://www.google.com/search?client=navclient-auto&ch='
            .$this->checkHash($this->hashUrl($domain))
            .'&features=Rank&q=info:'.$domain.'&num=100&filter=0';

        $html = $this->fetcher->fetch($url);

        if (preg_match('/Rank_([0-9]+):([0-9]+):([0-9]+)/si', $html, $matches)) {
            return $matches[3];
        }

        return '0';
    }

    private function strToNum(string $str, int $check, int $magic): int
    {
        $int32Unit = 4294967296;
        $length = strlen($str);

        for ($i = 0; $i < $length; $i++) {
            $check *= $magic;

            if ($check >= $int32Unit) {
                $check = ($check - $int32Unit * (int) ($check / $int32Unit));
                $check = ($check < -2147483648) ? ($check + $int32Unit) : $check;
            }

            $check += ord($str[$i]);
        }

        return $check;
    }

    private function hashUrl(string $string): int
    {
        $check1 = $this->strToNum($string, 0x1505, 0x21);
        $check2 = $this->strToNum($string, 0, 0x1003F);

        $check1 >>= 2;
        $check1 = (($check1 >> 4) & 0x3FFFFC0) | ($check1 & 0x3F);
        $check1 = (($check1 >> 4) & 0x3FFC00) | ($check1 & 0x3FF);
        $check1 = (($check1 >> 4) & 0x3C000) | ($check1 & 0x3FFF);

        $t1 = (((($check1 & 0x3C0) << 4) | ($check1 & 0x3C)) << 2) | ($check2 & 0xF0F);
        $t2 = (((($check1 & 0xFFFFC000) << 4) | ($check1 & 0x3C00)) << 0xA) | ($check2 & 0xF0F0000);

        return ($t1 | $t2);
    }

    private function checkHash(int $hashNum): string
    {
        $checkByte = 0;
        $flag = 0;

        $hashStr = sprintf('%u', $hashNum);
        $length = strlen($hashStr);

        for ($i = $length - 1; $i >= 0; $i--) {
            $re = (int) $hashStr[$i];

            if ($flag % 2 === 1) {
                $re += $re;
                $re = (int) ($re / 10) + ($re % 10);
            }

            $checkByte += $re;
            $flag++;
        }

        $checkByte %= 10;

        if ($checkByte !== 0) {
            $checkByte = 10 - $checkByte;

            if ($flag % 2 === 1) {
                if ($checkByte % 2 === 1) {
                    $checkByte += 9;
                }
                $checkByte >>= 1;
            }
        }

        return '7'.$checkByte.$hashStr;
    }
}
