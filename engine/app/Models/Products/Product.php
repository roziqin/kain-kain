<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use DB;
use Request;

class Product extends Model {

    //class Absensi extends Model {
    //
    protected $table = 'product';
    protected $fillable = array('id_category', 'product_sku', 'slug', 'product_name', 'product_description', 'product_price', 'product_price_roll', 'product_cm','product_lebar','product_berat', 'product_status');

    public function category() {
        return $this->belongsTo('App\Models\Products\Category', 'id_category', 'id');
    }

    public function attribute() {
        return $this->hasMany('App\Models\Products\Attribute', 'id_product');
    }

    public function image() {
        return $this->hasMany('App\Models\Products\Gambar', 'id_product');
    }

    public function metaproduct() {
        return $this->hasOne('App\Models\Products\metaProduct','id_product');
    }

    public function scopeDtProduct($query) {
        $aColumns = array('id', 'product_name', 'name', 'product_price', 'path_thumb', 'product_status');
        $rResult = $query->groupBy('product.id')->leftJoin('product_category as category', 'category.id', '=', 'product.id_category')
                ->leftJoin('product_img as gambar', 'product.id', '=', 'gambar.id_product')
                ->select(DB::raw("SQL_CALC_FOUND_ROWS product.id,product.product_name,product.product_price,product.product_status,gambar.path_thumb,IFNULL(category.name,'') AS name"));
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "id";
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
                    if ($aColumns[$i] == 'id') {
                        $aColumns[$i] = 'product.id';
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
		SELECT COUNT(`id`) as countid
		FROM   product
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
