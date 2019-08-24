<?php
/**
 * This file is part of josecarlosphp/reader - PHP classes to read from different sources.
 *
 * josecarlosphp/reader is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 * @see         https://github.com/josecarlosphp/reader
 * @copyright   2008-2019 José Carlos Cruz Parra
 * @license     https://www.gnu.org/licenses/gpl.txt GPL version 3
 * @desc        Class to read from a CSV file.
 */

namespace josecarlosphp\reader;

class MyReaderCsv extends MyReaderFile
{
    private $_delimiter;
    private $_enclosure;
    private $_escape;
    /**
	 * Constructor
	 *
	 * @param string $delimiter
	 * @param int length
	 * @param string $enclosure
	 * @param string $escape
	 * @return CSVReader
	 */
	function __construct($delimiter = ';', $length = 1024, $enclosure = '"', $escape = '\\')
    {
        parent::__construct($length);
        $this->_delimiter = $delimiter;
        $this->_enclosure = $enclosure;
        $this->_escape = $escape;
    }
    /**
	 * Lee una linea y la devuelve como un array numerico
	 *
	 * @return mixed
	 */
	public function ReadRow()
    {
        //El parametro escape se anadio en php 5.3.0
		$r = phpversion() < '5.3.' ?
			fgetcsv($this->_reader, $this->_length, $this->_delimiter, $this->_enclosure)
			:
			fgetcsv($this->_reader, $this->_length, $this->_delimiter, $this->_enclosure, $this->_escape);
        if($r)
        {
            $this->_i++;
            $this->_contador++;
        }
        return $r;
    }
    /**
	 * Establece el parametro delimiter
	 *
	 * @param string $val
	 */
	public function SetDelimiter($val)
    {
        $this->_delimiter = $val;
    }
    /**
	 * Establece el parametro enclosure
	 *
	 * @param string $val
	 */
	public function SetEnclosure($val)
    {
        $this->_enclosure = $val;
    }
    /**
	 * Establece el parametro escape
	 *
	 * @param string $val
	 */
	public function SetEscape($val)
    {
        $this->_escape = $val;
    }
    /**
	 * Obtiene el parametro delimiter
	 *
	 * @return string
	 */
	public function GetDelimiter()
    {
        return $this->_delimiter;
    }
    /**
	 * Obtiene el parametro enclosure
	 *
	 * @return string
	 */
	public function GetEnclosure()
    {
        return $this->_enclosure;
    }
    /**
	 * Obtiene el parametro escape
	 *
	 * @return string
	 */
	public function GetEscape()
    {
        return $this->_escape;
    }
}
