<?php

class Tones
{

    private $tones;

    private $ladder = ['C','C#','D','D#','E','F','F#','G','G#','A','A#','B'];

    /**
     *
     * @param string $tones_file
     *            CSV-formatted file name to read the tone to frequency data from (optional)
     */
    public function __construct($tones_file = 'tones.csv')
    {
        $this->read_file($tones_file);
    }

    private function read_file($tones_file, $delim = ';')
    {
        $tones = array();
        $row = 1;
        if (($handle = fopen($tones_file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, $delim)) !== FALSE) {
                $tones[$data[0]] = $data[1];
            }
            fclose($handle);
        }
        $this->tones = $tones;
    }

    /**
     * Returns the frequence of the desired tone in Hertz
     *
     * @param string $tone
     *            Base tone name with optional modifier (# or b)
     * @param number $octave
     * @return number
     */
    public function get_frequency($tone, $octave = 4)
    {
        // Replace german 'H' by 'B'
        if ($tone{0} == 'H') {
            $tone{0} = 'B';
        }

        // Replace is/es by #/b
        $tone = str_replace(['is','es'], ['#','b'], $tone);

        // Descend in ladder if tone is lowered with 'b'
        if (strlen($tone) > 1 && $tone{1} == 'b') {
            $newtone = $this->getNextLower($tone, $octave);
            $tone = $newtone[0];
            $octave = $newtone[1];
        }

        // Ascend in ladder if tone is increased with '#'
        if (strlen($tone) > 1 && $tone{1} == '#') {
            $newtone = $this->getNextHigher($tone, $octave);
            $tone = $newtone[0];
            $octave = $newtone[1];
        }

        return (isset($this->tones[$tone . $octave])) ? $this->tones[$tone . $octave] : 0;
    }

    private function getNextLower($tone, $octave)
    {
        $index = array_search($tone{0}, $this->ladder);
        if ($index > 0) {
            $index --;
        } else {
            $index = sizeof($this->ladder) - 1;
            $octave --;
        }
        $tone = $this->ladder[$index];
        return [$tone,$octave];
    }

    private function getNextHigher($tone, $octave)
    {
        $index = array_search($tone{0}, $this->ladder);
        if ($index < sizeof($this->ladder) - 1) {
            $index ++;
        } else {
            $index = 0;
            $octave ++;
        }
        $tone = $this->ladder[$index];
        return [$tone,$octave];
    }
}
