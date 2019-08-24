<?php
/**
 * This file is part of josecarlosphp/reader - PHP classes to read from different sources.
 *
 * josecarlosphp/db is free software: you can redistribute it and/or modify
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
 * @see         https://github.com/josecarlosphp/db
 * @copyright   2012-2019 José Carlos Cruz Parra
 * @license     https://www.gnu.org/licenses/gpl.txt GPL version 3
 * @desc        Class to read from a XML file.
 */

namespace josecarlosphp\reader;

class MyReader_XML extends MyReader
{
    private $_encoding;
    private $_rows;
    /**
	 * Constructor
	 *
	 */
	function __construct($encoding = 'UTF-8')
    {
        parent::__construct();
        $this->_encoding = $encoding;
        $this->_rows = array();
    }
    /**
	 * Abre un fichero para su lectura
	 *
	 * @param string $filepath
	 * @return bool
	 */
	public function Open($filepath, $autoclose=false/*, $encoding=null*/)
    {
        parent::Open($filepath, $autoclose);
        /*if(!is_null($encoding))
        {
            $this->_encoding = $encoding;
        }*/
        $this->_rows = xml2array($filepath, 0, 'tag', $this->_encoding);
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
	 * Fuu
	 *
	 * @param string $i1
	 * @param string $i2
	 * @param string $i3
	 * @param string $i4
	 * @param string $i5
	 * @param string $i6
	 */
	public function Set($i1, $i2 = false, $i3 = false, $i4 = false, $i5 = false, $i6 = false)
    {
        for($c = 1; $c < 7; $c++)
        {
            $i = eval("return \$i{$c};");
            if($i !== false)
            {
                $this->_rows = $this->_rows[$i];
            }
        }
    }
    /**
	 * Establece la codificacion de caracteres de salida
	 *
	 * @param string $str
	 */
	public function SetEncoding($str)
    {
        $this->_encoding = $str;
    }
}
