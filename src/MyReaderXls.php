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
 * @copyright   2012-2019 José Carlos Cruz Parra
 * @license     https://www.gnu.org/licenses/gpl.txt GPL version 3
 * @desc        Class to read from an Excel file (xls).
 */

namespace josecarlosphp\reader;

class MyReaderXls extends MyReader
{
    private $_rows;
    /**
	 * Constructor
	 *
	 */
	function __construct()
    {
        parent::__construct();
        $this->_reader = new Spreadsheet_Excel_Reader();
    }
    /**
	 * Abre un fichero para su lectura
	 *
	 * @param string $filepath
	 * @return bool
	 */
	public function Open($filepath, $autoclose=false)
    {
        parent::Open($filepath, $autoclose);
        $error = '';
        if($this->_reader->read($filepath, $error))
        {
            $this->_rows = array_values($this->_reader->sheets[0]['cells']);
            return true;
        }
        return true;
    }
    /**
	 * Lee una linea
	 *
	 * @return mixed
	 */
	public function ReadRow()
    {
        $r = isset($this->_rows[$this->_i]) ? $this->_rows[$this->_i] : false;
        if($r)
        {
            $this->_i++;
            $this->_contador++;
        }
        return $r;
    }
    /**
	 * Cierra el fichero abierto
	 *
	 * @return bool
	 */
	public function Close()
    {
        return true;
    }
    /**
	 * Posiciona en una fila a leer
	 *
	 * @param int $offset
	 * @param int $whence
	 * @return int
	 */
	public function Seek($offset, $whence = SEEK_SET)
    {
        switch($whence)
        {
            case SEEK_SET:
                $this->_i = $offset;
                return true;
            case SEEK_CUR:
                $this->_i += $offset;
                return true;
        }
        return false;
    }
    /**
	 * Establece la codificacion de caracteres de salida
	 *
	 * @param string $str
	 */
	public function SetOutputEncoding($str)
    {
        $this->_reader->setOutputEncoding($str);
    }
}
