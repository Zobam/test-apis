<?php

namespace App\Http\Controllers;

use App\Classes\SingletonClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CodeChallengeController extends Controller
{
    private $active_index = 0;
    private $value_sum = 0;
    private $rewrite_value_sum = 0;
    private $rewritten_rn = '';
    private $roman_input;
    private $rom_numerals = [
        'I' => 1,
        'V' => 5,
        'X' => 10,
        'L' => 50,
        'C' => 100,
        'D' => 500,
        'M' => 1000,
    ];
    public function roma_to_int(Request $request)
    {
        // singleton test
        /* $s1 = SingletonClass::getInstance();
        $s2 = SingletonClass::getInstance();
        if ($s1 === $s2) {
            return $s1->greet();
        } */
        $roman_input = strtoupper($request->rom);
        $roman_input_arr = str_split($roman_input);
        // sanitize the input
        Log::info('=======================' . $roman_input);
        $rn = $this->rom_numerals;
        $sanitized = array_filter($roman_input_arr, function ($num) use ($rn) {
            return array_key_exists($num, $this->rom_numerals);
        });
        $roman_input = implode('', $sanitized);
        $this->roman_input = $roman_input;
        Log::info('=======================' . $roman_input);

        for ($i = 0; $i < strlen($roman_input); $i++) {
            if ($i != 0 && ($this->rom_numerals[$roman_input[$i - 1]] < $this->rom_numerals[$roman_input[$i]])) {
                $this->value_sum -= $this->rom_numerals[$roman_input[$i - 1]];
                $this->value_sum += $this->rom_numerals[$roman_input[$i]] - $this->rom_numerals[$roman_input[$i - 1]];
            } else {
                $this->value_sum += $this->rom_numerals[$roman_input[$i]];
            }

            Log::info($this->value_sum);
        }
        $this->rewrite_value_sum = $this->value_sum;
        $this->rewrite_numeral();
        Log::info("Re-written roman numeral: $this->rewritten_rn");
        return view('result')->with('result', "Roman numeral $roman_input = $this->value_sum");
    }

    public function rewrite_numeral()
    {
        while ($this->rewrite_value_sum) {
            $current_rn = $this->get_highest_numeral();
            $this->rewritten_rn .= $current_rn;
            $this->rewrite_value_sum -= $this->rom_numerals[$current_rn];
        }
        // if the new length is greater than the input, return the input
        if (strlen($this->rewritten_rn) > strlen($this->roman_input)) {
            $this->rewritten_rn = $this->roman_input;
        }
    }

    public function get_highest_numeral()
    {
        /**
         * vvv = 15 => xv
         * Give a roman numeral, these are the steps to follow in order to check if it can be rewritten
         * 1. get the highest roman numeral in the given number
         * 2. if
         */
        $highest_rn = 'I';
        foreach ($this->rom_numerals as $key => $rn) {
            if (($this->rewrite_value_sum / $rn) >= 1) {
                $highest_rn = $key;
            }
        }
        return $highest_rn;
    }
    /**
     * is_last_char
     * @return bool
     */
    public function is_last_char()
    {
        return false;
    }
}
