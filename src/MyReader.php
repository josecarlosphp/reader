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
 * @desc        Abstract class base reader.
 */

namespace josecarlosphp\reader;

abstract class MyReader
{
    /**
	 * Para todo, segun el lector
	 *
	 * @var mixed
	 */
	protected $_reader;
    /**
	 * Indice de la posicion actual
	 *
	 * @var int
	 */
	protected $_i;
    /**
     * Contador de filas devueltas con ReadRow
     * @var int
     */
    protected $_contador = 0;
    /**
	 * Constructor
	 *
	 */
	function __construct()
    {
        $this->_reader = null;
        $this->_i = 0;
    }
    /**
	 * Abre un fichero para su lectura
	 *
	 * @param string $filepath
	 * @param bool $autoclose
	 * @return bool
	 */
	public function Open($filepath, $autoclose=false)
    {
		//Esta es una clase abstracta, $filepath se usa en las clases que heredan

		if($autoclose)
		{
			$this->Close();
		}

        $this->_i = 0;
        $this->_contador = 0;
        return false;
    }
    /**
	 * Lee una linea
	 *
	 * @return mixed
	 */
	public abstract function ReadRow();
    /**
	 * Lee todo el archivo y devuelve las lineas como un array de arrays de datos, o false si hay error
	 *
	 * @param string $filepath
	 * @param int $indexField
	 * @param mixed $valueField
	 * @return mixed
	 */
	public function ReadAll($filepath, $indexField=false, $valueField=false)
    {
        if($this->Open($filepath))
        {
            $arr = array();
			if($indexField !== false)
			{
				if($valueField !== false)
				{
					while($data = $this->ReadRow())
					{
						$arr[$data[$indexField]] = $data[$valueField];
					}
				}
				elseif(is_array($valueField))
				{
					while($data = $this->ReadRow())
					{
						foreach($valueField as $i)
						{
							$arr[$data[$indexField]][$i] = $data[$i];
						}
					}
				}
				else
				{
					while($data = $this->ReadRow())
					{
						$arr[$data[$indexField]] = $data;
					}
				}
			}
			else
			{
				while($data = $this->ReadRow())
				{
					$arr[] = $data;
				}
			}
            $this->Close();
            return $arr;
        }
        return false;
    }
    /**
	 * Cierra el fichero abierto, o la conexión, según el caso
	 *
	 * @return bool
	 */
	public abstract function Close();
    /**
	 * Posiciona en una fila a leer
	 * Devuelve el índice posicionado, o false en error
	 *
	 * @param int $offset
	 * @param int $whence
	 * @return int
	 */
	public abstract function Seek($offset, $whence = SEEK_SET);
    /**
     * Posiciona al inicio del todo, o $n pasos atrás
	 * Devuelve el índice posicionado, o false en error
     *
     * @return int
     */
    public function Rewind($n=0)
    {
        return $n > 0 ? $this->Seek(-$n, SEEK_CUR) : $this->Seek(0);
    }
	/**
     * Posiciona un paso atrás
	 * Devuelve el índice posicionado, o false en error
     *
     * @return int
     */
    public function StepBack()
    {
        return $this->Rewind(1);
    }
    /**
     * Obtiene la posición actual
     *
     * @return int
     */
    public function Tell()
    {
        return $this->_i;
    }
    /**
     * Obtiene el numero de filas leidas con ReadRow desde que se abrió por ultima vez
     *
     * @return int
     */
    public function GetCount()
    {
        return $this->_contador;
    }
}
