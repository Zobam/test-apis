<?php

namespace App\Http\Controllers;

use App\Classes\SingletonClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CodeChallengeController extends Controller
{
    private $active_index = 0;
    private $value_sum = 0;
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
        $rom_numerals = [
            'I' => 1,
            'V' => 5,
            'X' => 10,
            'L' => 50,
            'C' => 100,
            'D' => 500,
            'M' => 1000,
        ];
        // sanitize the input
        Log::info('=======================' . $roman_input);
        $sanitized = array_filter($roman_input_arr, function ($num) use ($rom_numerals) {
            return array_key_exists($num, $rom_numerals);
        });
        $roman_input = implode('', $sanitized);
        Log::info('=======================' . $roman_input);

        for ($i = 0; $i < strlen($roman_input); $i++) {
            if ($i != 0 && ($rom_numerals[$roman_input[$i - 1]] < $rom_numerals[$roman_input[$i]])) {
                $this->value_sum -= $rom_numerals[$roman_input[$i - 1]];
                $this->value_sum += $rom_numerals[$roman_input[$i]] - $rom_numerals[$roman_input[$i - 1]];
            } else {
                $this->value_sum += $rom_numerals[$roman_input[$i]];
            }

            Log::info($this->value_sum);
        }
        return view('result')->with('result', "Roman numeral $roman_input = $this->value_sum");
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
