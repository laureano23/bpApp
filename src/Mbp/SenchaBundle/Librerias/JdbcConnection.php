<?php
/**
 * PHP Jasper Report Utlis
 * 
 * PHP version 5
 * 
 * LICENSE
 *
 * PHP-JRU is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published 
 * by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 * 
 * PHP-JRU is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty 
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License 
 * along with PHP-JRU; if not, write to the Free Software Foundation, Inc., 
 * 51 Franklin St, Fifth Floor, Boston, MA 0110-1301, USA
 *
 * @author    Robert Alexander Bruno Monterrey <robert.alexander.bruno@gmail.com>
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU/GPL
 * @version   SVN:$id
 */

/**
 * clase que permite optener una connecion jdbc 
 *
 * @author    Robert Alexander Bruno Monterrey <robert.alexander.bruno@gmail.com>
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU/GPL
 * @version   SVN:$id
 */
class JdbcConnection {
	/*
	 * Conexion jdbc
	 * 
	 * @var  $connection 
	 */
	private $connection;
	/*
	 * Driver jdbc de la conexion
	 * 
	 * @var  String $driver 
	 */
	private $driver;
	/*
	 * string o url de la conexion jdbc 
	 * 
	 * @var  String $connectionString 
	 */
	private $connectionString;
	/*
	 * usuario de la conexion  jdbc
	 * 
	 * @var  String $user 
	 */
	private $user;
	
	
	/*
	 * contructor de la clase inicia los valores
	 */
	public function __construct($driver, $connectionString, $user, $password){
		$this->connection = new Java("org.altic.jasperReports.JdbcConnection");
		$this->connection->setDriver($driver);
		$this->connection->setConnectString($connectionString);
		$this->connection->setUser($user);
		$this->connection->setPassword($password);
		return $this->getConnection();			
	}

	/*
	 * obtienes conneccion a la base de datos
	 */
	public function getConnection(){
		return $this->connection->getConnection();	
	}
	
	/*
	 * Retorna el driver de la conexion jdbc
	 */
	public function getDriver(){
		return $this->driver;
	}
	
	/*
	 * Retorna el string de la conexion jdbc
	 */
	public function getConnectString(){
		return $this->connectionString;
	}
	
	/*
	 * Retorna el usuario de la conexion jdbc
	 */
	public function getUser(){
		return $this->user;
	}
	
}
?>