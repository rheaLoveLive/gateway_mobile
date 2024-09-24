<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\QueryBuilderDBF\DBF;
use DateTime;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class TabunganController extends Controller
{
    public function getAllTab(Request $request)
    {
        $datetime = date("YmdHis");

        try {
            $path = storage_path('DBF/MST_TAB.DBF');

            $data = DBF::table('mst_tab', 'dBaseDsn')
                ->get();

            if (!empty($data)) {
                $response = [
                    "status" => Controller::$status['SUKSES'],
                    "message" => "SUCCESS",
                    "response_time" => $datetime,
                    "data" => $data,
                ];

                return $response;
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => self::$status['BAD_REQUEST'], 'message' => 'REQUEST TIDAK VALID' . $th->getMessage(), "response_time" => $datetime], 400);
        }
    }

    public function getAllMutasi(Request $request)
    {
        $datetime = date("YmdHis");

        try {
            $data = DBF::table('trn_tab', 'dBaseDsn')
                ->get();

            if (!empty($data)) {
                $response = [
                    "status" => Controller::$status['SUKSES'],
                    "message" => "SUCCESS",
                    "response_time" => $datetime,
                    "data" => $data,
                ];

                return $response;
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => self::$status['BAD_REQUEST'], 'message' => 'REQUEST TIDAK VALID' . $th->getMessage(), "response_time" => $datetime], 400);
        }
    }

    public function rekeningTab(Request $request)
    {
        $orderdata = $request->orderdata;
        $datetime = date('YmdHis');
        try {

            $decodedPayloadJwt = JWT::decode($orderdata, new Key(env('TOKEN_KEY'), 'HS512'));
            $decodedPayload = [
                'status' => '00',
                'error' => null,
                'payload' => (array) $decodedPayloadJwt,
            ];

            if ($decodedPayload['status'] == '00') {
                $bodyValid = [
                    "kodeanggota",
                ];

                $data = $decodedPayload['payload'];
                $dataCount = count($data);
                $bodyCount = count($bodyValid);
                if ($dataCount > $bodyCount || $dataCount < $bodyCount) {
                    return response()->json(['status' => self::$status['BAD_REQUEST'], 'message' => 'INVALID BODY', "response_time" => $datetime], 400);
                }

                $rekening = DBF::table('mst_tab', 'dBaseDsn')
                    ->where('no_rek', '=', $data['norek'])
                    ->get();

                if (!empty($rekening)) {

                    return response()->json([
                        "status" => Controller::$status['SUKSES'],
                        "message" => "SUCCESS",
                        "response_time" => $datetime,
                        "data" => array_values($rekening),
                    ]);

                } else {
                    return response()->json([
                        'status' => self::$status['BAD_REQUEST'],
                        'message' => 'DATA TIDAK ADA',
                        "data" => [],
                        "response_time" => $datetime
                    ], 400);
                }
            }



        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'REQUEST TIDAK VALID' . $th->getMessage(),
                "response_time" => $datetime
            ], 400);
        }
    }
    public function mutasiTab(Request $request)
    {
        $orderdata = $request->orderdata;
        $datetime = date('YmdHis');
        try {

            $decodedPayloadJwt = JWT::decode($orderdata, new Key(env('TOKEN_KEY'), 'HS512'));
            $decodedPayload = [
                'status' => '00',
                'error' => null,
                'payload' => (array) $decodedPayloadJwt,
            ];

            if ($decodedPayload['status'] == '00') {
                $bodyValid = [
                    "norek",
                ];

                $data = $decodedPayload['payload'];
                $dataCount = count($data);
                $bodyCount = count($bodyValid);
                if ($dataCount > $bodyCount || $dataCount < $bodyCount) {
                    return response()->json(['status' => self::$status['BAD_REQUEST'], 'message' => 'INVALID BODY', "response_time" => $datetime], 400);
                }

                $rekening = DBF::table('trn_tab', 'dBaseDsn')
                    ->where('no_rek', '=', $data['norek'])
                    ->get();

                if (!empty($rekening)) {
                    return response()->json([
                        "status" => Controller::$status['SUKSES'],
                        "message" => "SUCCESS",
                        "response_time" => $datetime,
                        "data" => array_values($rekening),
                    ]);

                } else {
                    return response()->json([
                        'status' => self::$status['BAD_REQUEST'],
                        'message' => 'DATA TIDAK ADA',
                        "response_time" => $datetime,
                        "data" => [],
                    ], 400);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'REQUEST TIDAK VALID' . $th->getMessage(),
                "response_time" => $datetime
            ], 400);
        }
    }
    public function cariRekening(Request $request)
    {
        $orderdata = $request->orderdata;
        $datetime = date('YmdHis');
        try {

            $decodedPayloadJwt = JWT::decode($orderdata, new Key(env('TOKEN_KEY'), 'HS512'));
            $decodedPayload = [
                'status' => '00',
                'error' => null,
                'payload' => (array) $decodedPayloadJwt,
            ];

            if ($decodedPayload['status'] == '00') {
                $bodyValid = [
                    "norek",
                ];

                $data = $decodedPayload['payload'];
                $dataCount = count($data);
                $bodyCount = count($bodyValid);
                if ($dataCount > $bodyCount || $dataCount < $bodyCount) {
                    return response()->json(['status' => self::$status['BAD_REQUEST'], 'message' => 'INVALID BODY', "response_time" => $datetime], 400);
                }

                $rekening = DBF::table('mst_tab', 'dBaseDsn')
                    ->select(['nama', 'no_rek'])
                    ->where('no_rek', '=', $data['norek'])
                    ->get();

                if (!empty($rekening)) {
                    return response()->json([
                        "status" => Controller::$status['SUKSES'],
                        "message" => "SUCCESS",
                        "response_time" => $datetime,
                        "data" => array_values($rekening),
                    ]);

                } else {
                    return response()->json([
                        'status' => self::$status['BAD_REQUEST'],
                        'message' => 'DATA TIDAK ADA',
                        "response_time" => $datetime,
                        "data" => [],
                    ], 400);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'REQUEST TIDAK VALID' . $th->getMessage(),
                "response_time" => $datetime
            ], 400);
        }
    }

    public function insertMutasiTab(Request $request)
    {
        $orderdata = $request->orderdata;
        $datetime = date('YmdHis');
        try {

            $decodedPayloadJwt = JWT::decode($orderdata, new Key(env('TOKEN_KEY'), 'HS512'));
            $decodedPayload = [
                'status' => '00',
                'error' => null,
                'payload' => (array) $decodedPayloadJwt,
            ];

            if ($decodedPayload['status'] == '00') {
                $bodyValid = [
                    "norek",
                    "norektujuan",
                    "jumlah",
                    "oprt",
                    "buktitrx",
                    "ao",
                    "jnssimpanan",
                    "sandi",
                    "tgltrx",
                    "jurnal",
                    "tgltran",
                ];

                $data = $decodedPayload['payload'];
                $dataCount = count($data);
                $bodyCount = count($bodyValid);
                if ($dataCount > $bodyCount || $dataCount < $bodyCount) {
                    return response()->json(['status' => self::$status['BAD_REQUEST'], 'message' => 'INVALID BODY', "response_time" => $datetime], 400);
                }

                $rekening = DBF::table('mst_tab', 'dBaseDsn')
                    ->where('no_rek', '=', $data['norek'])
                    ->get();

                if (empty($rekening)) {
                    return response()->json([
                        'status' => self::$status['BAD_REQUEST'],
                        'message' => 'NO REKENING TIDAK ADA',
                        "response_time" => $datetime
                    ], 400);
                }


                // Mengambil data saldo pengirim dan tujuan
                $recPengirim = DBF::table('MST_TAB', 'dBaseDsn')
                    ->where('no_rek', '=', $data['norek'])
                    ->first();

                $recTujuan = DBF::table('MST_TAB', 'dBaseDsn')
                    ->where('no_rek', '=', $data['norektujuan'])
                    ->first();

                // Mendapatkan saldo akhir
                $saldoAkhirPengirim = isset($recPengirim['saldo_akhr']) ? $recPengirim['saldo_akhr'] : 0;
                $saldoAkhirTujuan = isset($recTujuan['saldo_akhr']) ? $recTujuan['saldo_akhr'] : 0;

                // Data yang akan diinsert
                $arrData = [
                    [
                        'no_rek' => $data['norek'],
                        "jumlah" => $data['jumlah'],
                        "saldo" => $saldoAkhirPengirim,
                        "oprt" => $data['oprt'],
                        "bukti_trx" => $data['buktitrx'],
                        "ao" => $data['ao'],
                        "d_k" => 'D',
                        "jns_simp" => $data['jnssimpanan'],
                        "sandi" => $data['sandi'],
                        "tgl_trx" => $data['tgltrx'],
                        "jurnal" => $data['jurnal'],
                        "tgl_tran" => $data['tgltran']
                    ],
                    [
                        'no_rek' => $data['norektujuan'],
                        "jumlah" => $data['jumlah'],
                        "saldo" => $saldoAkhirTujuan,
                        "oprt" => $data['oprt'],
                        "bukti_trx" => $data['buktitrx'],
                        "ao" => $data['ao'],
                        "d_k" => 'K',
                        "jns_simp" => $data['jnssimpanan'],
                        "sandi" => $data['sandi'],
                        "tgl_trx" => $data['tgltrx'],
                        "jurnal" => $data['jurnal'],
                        "tgl_tran" => $data['tgltran']
                    ]
                ];

                // Lakukan insert satu per satu menggunakan `DBF::table`
                foreach ($arrData as $dataItem) {
                    $create = DBF::table('TRN_TAB', 'dBaseDsn')->create($dataItem);
                }

                if ($create == "CREATED") {
                    return response()->json([
                        "status" => Controller::$status['SUKSES'],
                        "message" => "SUCCESS",
                        "response_time" => $datetime,
                    ]);

                } else {
                    return response()->json([
                        'status' => self::$status['BAD_REQUEST'],
                        'message' => 'GAGAL INSERT DATA',
                        "response_time" => $datetime
                    ], 400);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'REQUEST TIDAK VALID' . $th->getMessage(),
                "response_time" => $datetime
            ], 400);
        }
    }

    public function historyTrans(Request $request)
    {
        $orderdata = $request->orderdata;
        $datetime = date('YmdHis');
        try {

            $decodedPayloadJwt = JWT::decode($orderdata, new Key(env('TOKEN_KEY'), 'HS512'));
            $decodedPayload = [
                'status' => '00',
                'error' => null,
                'payload' => (array) $decodedPayloadJwt,
            ];

            if ($decodedPayload['status'] == '00') {
                $bodyValid = [
                    "norek",
                    "sandi",
                    "buktitrx",
                ];

                $data = $decodedPayload['payload'];
                $dataCount = count($data);
                $bodyCount = count($bodyValid);
                if ($dataCount > $bodyCount || $dataCount < $bodyCount) {
                    return response()->json(['status' => self::$status['BAD_REQUEST'], 'message' => 'INVALID BODY', "response_time" => $datetime], 400);
                }

                $history = DBF::table('trn_tab', 'dBaseDsn')
                    ->where('no_rek', '=', $data['norek'])
                    ->where('sandi', '=', $data['sandi'])
                    ->where('bukti_trx', '=', $data['buktitrx'])
                    ->get();

                if (!empty($history)) {
                    return response()->json([
                        "status" => Controller::$status['SUKSES'],
                        "message" => "SUCCESS",
                        "response_time" => $datetime,
                        "data" => array_values($history),
                    ]);

                } else {
                    return response()->json([
                        'status' => self::$status['BAD_REQUEST'],
                        'message' => 'DATA TIDAK ADA',
                        "response_time" => $datetime,
                        "data" => []
                    ], 400);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => self::$status['BAD_REQUEST'],
                'message' => 'REQUEST TIDAK VALID' . $th->getMessage(),
                "response_time" => $datetime
            ], 400);
        }
    }
}
