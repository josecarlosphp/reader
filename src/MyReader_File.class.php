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
 * @desc        Class to read from a text file.
 */

namespace josecarlosphp\reader;

class MyReader_File extends MyReader
{
    protected $_length;
    /**
	 * Constructor
	 *
	 * @return FileReader
	 */
	function __construct($length = 1024)
    {
        parent::__construct();
        $this->_length = $length;
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
        return ($this->_reader = fopen($filepath, 'r')) ? true : false;
    }
    /**
	 * Lee una linea
	 *
	 * @return mixed
	 */
	public function ReadRow()
    {
        $r = fgets($this->_reader, $this->_length);
        if($r !== false)
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
		if(is_resource($this->_reader))
		{
			return fclose($this->_reader);
		}

		return true;
    }
    /**
	 * Posiciona en una fila a leer
	 *
	 * @param int $offset
	 * @param int $whence
	 * @return int
	 */
	public function Seek($offset, $whence=SEEK_SET)
    {
		switch($whence)
		{
			case SEEK_SET:
				$target_i = $offset;
				$this->_i = 0;
				fseek($this->_reader, 0, SEEK_SET);
				break;
			case SEEK_CUR:
				$target_i = $this->_i + $offset;
				if($offset < 0)
				{
					$this->_i = 0;
					fseek($this->_reader, 0, SEEK_SET);
				}
				break;
			default:
				return false;
		}

		for($c=$this->_i; $c<$target_i; $c++)
		{
			if(!$this->ReadRow())
			{
				return false;
			}
		}

		return true;
    }
    /**
	 * Establece el parametro length
	 *
	 * @param int $val
	 */
	public function SetLength($val)
    {
        $this->_length = $val;
    }
    /**
	 * Obtiene el parametro length
	 *
	 * @return int
	 */
	public function GetLength()
    {
        return $this->_length;
    }
}
