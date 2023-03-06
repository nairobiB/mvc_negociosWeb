<?php

namespace Dao\Mnt;

use Dao\Table;

/*
CREATE TABLE `nw202101`.`categorias` (
`catid` BIGINT(8) NOT NULL AUTO_INCREMENT,
`catnom` VARCHAR(45) NULL,
`catest` CHAR(3) NULL DEFAULT 'ACT',
PRIMARY KEY (`catid`));
*/
class Categorias extends Table
{
    /**
     * Crea un nuevo registro de categoria
     *
     * @param string $catnom
     * @param string $catest
     * @return void
     */
    public static function insert(string $catnom, string $catest = "ACT"): int
    {
        $sqlstr = "INSERT INTO categorias (catnom, catest) values(?, ?);";
        $rowinserted = self::executeNonQuery(
            $sqlstr,
            array(
                "catnom" => $catnom,
                "catest" => $catest
            )
        );

        return $rowinserted;

    }
    public static function update()
    {


    }
    public static function delete()
    {


    }
    public static function findAll()
    {
        $sqlstr = "SELECT * FROM categorias;";
        return self::obtenerRegistros($sqlstr, array());

    }
    public static function findById()
    {


    }
}

?>