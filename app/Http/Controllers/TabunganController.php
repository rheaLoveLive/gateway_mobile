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
                    ->where('no_agt', '=', $data['kodeanggota'])
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
                    ->where('no_agt', '=', $data['kodeanggota'])
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
                    ->select('NAMA', 'NO_REK')
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
                $recPengirim = DBF::table('TRN_TAB', 'dBaseDsn')
                    ->select(
                        "SUM(IIF(D_K = 'K', JUMLAH, 0)) AS KREDIT",
                        "SUM(IIF(D_K = 'D', JUMLAH, 0)) AS DEBET",
                        "NO_REK"
                    )
                    ->where('no_rek', '=', $data['norek'])
                    ->groupBy('no_rek')
                    ->first();

                $recTujuan = DBF::table('TRN_TAB', 'dBaseDsn')
                    ->select(
                        "SUM(IIF(D_K = 'K', JUMLAH, 0)) AS KREDIT",
                        "SUM(IIF(D_K = 'D', JUMLAH, 0)) AS DEBET",
                        "NO_REK"
                    )
                    ->where('no_rek', '=', $data['norektujuan'])
                    ->groupBy('no_rek')
                    ->first();


                $saldoAwalPengirim = 0;
                $saldoAwalTujuan = 0;
                if (!empty($recTujuan) && !empty($recPengirim)) {

                    //mendapatkan saldo awal
                    $saldoAwalPengirim = $recPengirim['KREDIT'] - $recPengirim['DEBET'];
                    $saldoAwalTujuan = $recTujuan['KREDIT'] - $recTujuan['DEBET'];

                    // Mendapatkan saldo akhir
                    $saldoAkhirPengirim = $saldoAwalPengirim - $data['jumlah'];
                    $saldoAkhirTujuan = $saldoAwalTujuan - $data['jumlah'];

                    // dd($saldoAkhirTujuan);


                    // Data yang akan diinsert
                    $arrDataTRN = [
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

                    $arrDataMST = [
                        [
                            'no_rek' => $recPengirim['NO_REK'],
                            'saldo_awal' => $saldoAwalPengirim,
                            'saldo_akhr' => $saldoAkhirPengirim,
                            'jumlah' => $saldoAkhirPengirim,
                        ],
                        [
                            'no_rek' => $recTujuan['NO_REK'],
                            'saldo_awal' => $saldoAwalTujuan,
                            'saldo_akhr' => $saldoAkhirTujuan,
                            'jumlah' => $saldoAkhirTujuan,
                        ]
                    ];
                }

                // Lakukan insert satu per satu menggunakan `DBF::table`
                foreach ($arrDataTRN as $dataItem) {
                    $create = DBF::table('TRN_TAB', 'dBaseDsn')->create($dataItem);
                }

                if ($create == "CREATED") {

                    foreach ($arrDataMST as $dataItem) {
                        $update = DBF::table('MST_TAB', 'dBaseDsn')
                            ->where('no_rek', '=', $dataItem['no_rek'])
                            ->update($dataItem);
                    }

                    if ($update == "UPDATED") {
                        return response()->json([
                            "status" => Controller::$status['SUKSES'],
                            "message" => "SUCCESS",
                            "response_time" => $datetime,
                        ]);
                    } else {
                        return response()->json([
                            'status' => self::$status['BAD_REQUEST'],
                            'message' => 'GAGAL UPDATE DATA',
                            "response_time" => $datetime
                        ], 400);
                    }
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
                    "kodeanggota",
                    "sandi",
                    "buktitrx",
                ];

                $data = $decodedPayload['payload'];
                $dataCount = count($data);
                $bodyCount = count($bodyValid);
                if ($dataCount > $bodyCount || $dataCount < $bodyCount) {
                    return response()->json(['status' => self::$status['BAD_REQUEST'], 'message' => 'INVALID BODY', "response_time" => $datetime], 400);
                }


                $rek = DBF::table('mst_tab', 'dBaseDsn')
                    ->where('no_agt', '=', $data['kodeanggota'])
                    ->get();

                $history = [];

                foreach ($rek as $dt) {
                    $history[] = DBF::table('trn_tab', 'dBaseDsn')
                        ->where('no_rek', '=', $dt['NO_REK'])
                        ->where('sandi', '=', $data['sandi'])
                        ->where('bukti_trx', '=', $data['buktitrx'])
                        ->get();
                }

                if (!empty($history)) {
                    return response()->json([
                        "status" => Controller::$status['SUKSES'],
                        "message" => "SUCCESS",
                        "response_time" => $datetime,
                        "data" => array_merge(...$history),
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
