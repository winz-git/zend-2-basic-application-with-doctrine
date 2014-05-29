<?php
/**
 * Created by PhpStorm.
 * User: winston.c
 * Date: 14/03/14
 * Time: 5:06 PM
 */
namespace Application\Library;

class Export {

    private static $_instance = null;

    private function __construct(){}

    public static function getInstance(){
        if(null === self::$_instance){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function toCSV($data=array(), $filename= 'file.csv', $delimiter=';'){
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment;filename="'. $filename. '"');
        header("Pragma: no-cache");
        header("Expires: 0");

        // Write CSV To Disk
        $csv_data = $data;
        $csv_file_name = $filename;
        //$h = @fopen($csv_file_name, 'w');
        $h = @fopen('php://output', 'w');

        if (false !== $h) {
            if (sizeof($csv_data)) {
                foreach ($csv_data as $csv_row) {
                    array_walk_recursive($csv_row, function(&$item) {
                        $item = preg_replace('/\s+/', ' ', preg_replace('/[\r\n\t]*/', '', $item));
                        $item = trim(strip_tags($item));
                    });
                    fputcsv($h, $csv_row, $delimiter, '"');
                }
            }

            //ob_flush();
            //flush();
            fclose($h);
        }
        return (file_exists($csv_file_name) ? $csv_file_name : '');
    }

    public function cleanDownloadedFile($download_path = '/../../../downloads'){
        // Delete Temp CSV Files - Older Than 1 Day
        $temp_csv_files = glob($download_path . '/*.csv');
        if (is_array($temp_csv_files) && sizeof($temp_csv_files)) {
            foreach ($temp_csv_files as $temp_csv_file) {
                if (is_file($temp_csv_file) && time() - filemtime($temp_csv_file) >= 24*60*60) { // 1 Day Old
                    unlink($temp_csv_file);
                }
            }
        }
    }
}