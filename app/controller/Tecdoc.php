<?php
/**
 * Created by PhpStorm.
 * User: Antony
 * Date: 04.10.15
 * Time: 2:08
 */

    class Tecdoc {
        private $db;
        private $mysql;

        public function __construct() {
            
            $connectionStr = 
                "Driver={Transbase ODBC TECDOC CD 1_2015};Database=TECDOC_CD_1_2015@localhost";
            $this->db = odbc_connect($connectionStr,"tecdoc","tcd_error_0") or odbc_errormsg();

            $this->mysql = new Mysqli(
                'localhost',
                'user',
                'password',
                'mydb'
            );

            $this->mysql->set_charset('utf8');
        }

        public function export($tableName) {
            echo "$tableName - processing... ";
            flush();

            $query = "SELECT * FROM " . $tableName;

            $data = odbc_exec($this->db, $query);
            odbc_longreadlen($data, 10485760);

            while ($row = odbc_fetch_array($data)) {
                foreach ($row as $key => $value) {
                    $keys[] = "`" . $key . "`";
                    $values[] = "'" . $this->mysql->escape_string($value) . "'";
                }

                $query = 
                    "INSERT INTO {$tableName} (" . implode(",", $keys) 
                        . ") VALUES (" . implode(",", $values) . ")";
                
                $this->mysql->query($query);
                set_time_limit(3600);
                unset($keys);
                unset($values);
                unset($row);
            }
            echo "completed!<br>";
            flush();
        }

        public function exportGraphics($tableName) {
            echo "$tableName - exporting... ";
            flush();

            $query = "SELECT * FROM " . $tableName;
            @mkdir("images/" . $tableName);

            $data = odbc_exec($this->db, $query);
            odbc_longreadlen($data, 10485760);

            while($row = odbc_fetch_array($data)) {
                if($row['GRD_ID'] != "") {
                    $fileNameJp2 = "images/" . $tableName . "/" . $row['GRD_ID'] . ".jp2";
                    $file = fopen ($fileNameJp2, "w");
                    fputs($file, $row['GRD_GRAPHIC']);
                    fclose($file);
                    set_time_limit(3600);
                    unset($row);
                }
            }
            echo "completed!<br>";
            flush();
        }

        public function exportAllGraphics() {
            $result = odbc_tables($this->db);

            while (odbc_fetch_row($result)) {

                if(odbc_result($result, "TABLE_TYPE") == "TABLE") {
                    $name = odbc_result($result, "TABLE_NAME");

                    if (substr($name, 0, 12) == 'TOF_GRA_DATA')

                        if (!is_dir("images/" . $name))
                            $this->exportGraphics($name);
                }
            }
        }
    }

    $tecdoc = new Tecdoc();
    $tecdoc->export("TOF_MANUFACTURERS");
    $tecdoc->export("TOF_MODELS");
    $tecdoc->export("TOF_DES_TEXTS");
    $tecdoc->export("TOF_COUNTRY_DESIGNATIONS");
    $tecdoc->export("TOF_DESIGNATIONS");
    $tecdoc->export("TOF_TYPES");
    $tecdoc->export("TOF_ARTICLES");
    $tecdoc->export("TOF_SUPPLIERS");
    $tecdoc->export("TOF_ART_LOOKUP");
    $tecdoc->export("TOF_SEARCH_TREE");
    $tecdoc->export("TOF_LINK_GA_STR");
    $tecdoc->export("TOF_BRANDS");
    $tecdoc->exportAllGraphics();