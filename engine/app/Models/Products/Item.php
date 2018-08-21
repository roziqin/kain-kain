<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use DB;
use Request;

class Item extends Model {

    //
    protected $table = 'product_item';
    protected $primaryKey = 'item_id';
    protected $fillable = array('item_id','item_id_product', 'item_barcode', 'item_perroll', 'item_totalroll', 'item_totalmeter');

   
    public function scopeDtProduct($query) {
        $aColumns = array('item_id', 'item_id_product', 'item_barcode', 'item_perroll', 'item_totalroll', 'item_totalmeter', 'barcode_number');
        $rResult = $query->groupBy('item_id')
        		->leftJoin('product_barcode as barcode', 'item_barcode', '=', 'barcode_id')
                ->select(DB::raw("SQL_CALC_FOUND_ROWS item_id,item_id_product,item_barcode,item_perroll,item_totalroll,item_totalmeter,barcode_number"));
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "item_id";
        /*
         * Paging
         */
        $sLimit = '';
        if (Request::has('iDisplayStart') && Request::get('iDisplayLength') != '-1') {
            $rResult = $rResult->skip(Request::get('iDisplayStart'))->take(Request::get('iDisplayLength'));
//            $sLimit = "LIMIT " . intval(Request::get('iDisplayStart')) . ", " .
//                    intval(Request::get('iDisplayLength'));
        }
        /*
         * Ordering
         */
        $sOrder = "";
        if (Request::has('iSortCol_0')) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval(Request::get('iSortCol_0')); $i++) {
                if (Request::get('bSortable_' . intval(Request::get('iSortCol_' . $i))) == "true") {
//                    $sOrder .= "`" . $aColumns[intval(Request::get('iSortCol_' . $i))] . "` " .
//                            (Request::get('sSortDir_' . $i) === 'asc' ? 'asc' : 'desc') . ", ";
                    $rResult = $rResult->orderBy($aColumns[intval(Request::get('iSortCol_' . $i))], (Request::get('sSortDir_' . $i) === 'asc' ? 'asc' : 'desc'));
                }
            }
            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if (Request::has('sSearch') && Request::get('sSearch') != "") {
            //           $sWhere = "WHERE (";
            $rResult = $rResult->orWhere(function($query) use ($aColumns) {
                for ($i = 0; $i < count($aColumns); $i++) {
//                    $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . Request::get('sSearch') . "%' OR ";
                    if ($aColumns[$i] == 'item_id') {
                        $aColumns[$i] = 'item_id';
                    }
                    $query->orWhere($aColumns[$i], 'LIKE', '%' . Request::get('sSearch') . '%');
                }
            });
//            $sWhere = substr_replace($sWhere, "", -3);
//            $sWhere .= ')';
            //          dd($sWhere);
        }
        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (Request::has('bSearchable_' . $i) && Request::get('bSearchable_' . $i) == "true" && Request::get('sSearch_' . $i) != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $rResult = $rResult->where($aColumns, 'LIKE', '%' . Request::get('sSearch_' . $i) . '%');
            }
        }
//        $rResult = DB::select("SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
//		FROM   product
//		$sWhere
//		$sOrder
//		$sLimit
//		");
        $rResult = $rResult->get();
        $sQuery = DB::select("
		SELECT FOUND_ROWS() as row
	");
        $iFilteredTotal = $sQuery[0]->row;
        $aResultTotal = DB::select("
		SELECT COUNT(`item_id`) as countid
		FROM   product_item
	");
        $iTotal = $aResultTotal[0]->countid;
        /*
         * Output
         */
        $output = [
            "sEcho" => intval(Request::get('sEcho')),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => []
        ];
        foreach ($rResult->toArray() as $aRow) {
            $row = [];
            for ($i = 0; $i < count($aColumns); $i++) {
                if ($aColumns[$i] == "version") {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[$aColumns[$i]] == "0") ? '-' : $aRow[$aColumns[$i]];
                } else if ($aColumns[$i] != ' ') {
                    /* General output */
                    $row[$aColumns[$i]] = $aRow[$aColumns[$i]];
                }
            }
            $output['aaData'][] = $row;
        }
        return $output;
    }
}
